<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class LicenseService
{
    /**
     * The Public Key used to verify license signatures.
     * In a real build, this would be hardcoded/obfuscated here.
     */
    protected static $publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0QFj7UpmNmATof+gikwz
jtbHSEthJ0udVI5rBR9LvP6d28vNRlEbMFK3EzpjXqLmns/aVRArDYsSjotPs9TU
aEPvTwDZh0hTA7pPVwVvpeRyBpNrVQYw0fnV681cn6llXbO/JEOu8YZezmBQX2NR
NyPsG+eMM0E1iR6Y8MWLXHvMsvR4wc5napIJ/uCc4+a2B3w4IDNqXucmwGIz+/fV
2OrZ2Zb/abXhH+ezkfdsBXUs8v5kqKKudsRM7+qFI1Jlh7D/H21JVF6WUvIE0+c5
Gig8eswEAAXteNW//ZZ/W3pOVxHMvtoAM955EzluNyBlntQ+JWL5z6LsS9ZpQ+4C
LwIDAQAB
-----END PUBLIC KEY-----
EOD;

    /**
     * Get the current machine's Hardware Fingerprint.
     */
    public function getSystemFingerprint()
    {
        // Try to get from Cache first to save process time
        return Cache::rememberForever('server_hwid', function () {
            $parts = [];

            // 1. CPU ID (PowerShell)
            try {
                $cpuCmd = 'powershell -NoProfile -Command "Get-CimInstance -ClassName Win32_Processor | Select-Object -ExpandProperty ProcessorId"';
                $cpu = shell_exec($cpuCmd);
                if ($cpu && preg_match('/[A-Z0-9]+/i', trim($cpu))) {
                    $parts[] = trim($cpu);
                } else {
                    $parts[] = 'CPU_UNKNOWN';
                }
            } catch (\Exception $e) {
                $parts[] = 'CPU_ERROR';
            }

            // 2. Disk Serial (PowerShell)
            try {
                $diskCmd = 'powershell -NoProfile -Command "Get-CimInstance -ClassName Win32_DiskDrive | Select-Object -ExpandProperty SerialNumber"';
                $disk = shell_exec($diskCmd);
                if ($disk) {
                    $lines = explode("\n", trim($disk));
                    foreach($lines as $line) {
                        $val = trim($line);
                        if (!empty($val)) {
                            $parts[] = $val;
                            break;
                        }
                    }
                } else {
                    $parts[] = 'DISK_UNKNOWN';
                }
            } catch (\Exception $e) {
                $parts[] = 'DISK_ERROR';
            }

            $rawData = implode('|', $parts);
            // Hash it
            return strtoupper(substr(hash('sha256', $rawData . 'SELA_V1_SALT'), 0, 16));
        });
    }

    /**
     * Verify the server.lic file.
     * @return bool|string True if valid, or error message string.
     */
    public function verifyLicense()
    {
        if (!Storage::exists('server.lic')) {
            return "License file not found.";
        }

        try {
            $content = Storage::get('server.lic');
            $data = json_decode(base64_decode($content), true);

            if (!$data || !isset($data['payload']) || !isset($data['signature'])) {
                return "Invalid license format.";
            }

            // 1. Verify Signature
            // We must verify the RAW payload (decoded), because that's what was signed
            $valid = openssl_verify(base64_decode($data['payload']), base64_decode($data['signature']), self::$publicKey, OPENSSL_ALGO_SHA256);
            if ($valid !== 1) {
                return "License signature invalid.";
            }

            // 2. Decode Payload
            $payload = json_decode(base64_decode($data['payload']), true);
            
            // 3. Check Expiry
            if (isset($payload['expiry']) && strtotime($payload['expiry']) < time()) {
                return "License expired on " . $payload['expiry'];
            }

            // 4. Check Hardware ID
            $currentHWID = $this->getSystemFingerprint();
            if (isset($payload['hwid']) && $payload['hwid'] !== $currentHWID) {
                return "License is bound to another machine (ID mismatch).";
            }

            return true;
        } catch (\Exception $e) {
            Log::error("License Check Error: " . $e->getMessage());
            return "License verification failed.";
        }
    }
}
