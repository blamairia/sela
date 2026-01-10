<?php

// Sela POS - License Generator
// Run: php license_generator.php <HWID> <Expiry>

// 1. Generate Keys if they don't exist
if (!file_exists('private.key')) {
    echo "⚙️  Generating NEW 2048-bit RSA Key Pair...\n";
    
    // Fix for XAMPP Windows
    $opensslConfigPath = "C:/xampp/php/extras/ssl/openssl.cnf";
    if (!file_exists($opensslConfigPath)) {
        // Fallback to Apache bin location
        $opensslConfigPath = "C:/xampp/apache/bin/openssl.cnf";
    }
    
    $config = array(
        "digest_alg" => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
        "config" => $opensslConfigPath
    );
    $res = openssl_pkey_new($config);
    if (!$res) {
        die("❌ openssl_pkey_new failed: " . openssl_error_string());
    }
    
    // Export Private Key - PASS CONFIG HERE TOO
    if (!openssl_pkey_export($res, $privKey, null, $config)) {
        die("❌ openssl_pkey_export failed: " . openssl_error_string());
    }
    file_put_contents('private.key', $privKey);

    // Extract Public Key
    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];
    file_put_contents('public.key', $pubKey);

    echo "✅ Keys created: private.key (KEEP SECRET) & public.key (EMBED IN APP)\n\n";
} else {
    $privKey = file_get_contents('private.key');
}

// 2. Get Arguments
$hwid = $argv[1] ?? 'E7BC85FF662E6E09'; // Default to your machine ID
$expiry = $argv[2] ?? '2030-12-31';
$maxClients = $argv[3] ?? 3; // Default 3 Terminals

echo "------------------------------------------------\n";
echo "🔐 SELA LICENSE GENERATOR\n";
echo "Target HWID: $hwid\n";
echo "Expiry Date: $expiry\n";
echo "Max Clients: $maxClients\n";
echo "------------------------------------------------\n";

// 3. Create Payload
$payload = [
    'hwid' => $hwid,
    'expiry' => $expiry,
    'max_clients' => (int)$maxClients,
    'generated_at' => date('Y-m-d H:i:s'),
    'type' => 'server_license'
];

$jsonPayload = json_encode($payload);
$encodedPayload = base64_encode($jsonPayload);

// 4. Sign Payload
openssl_sign($jsonPayload, $signature, $privKey, OPENSSL_ALGO_SHA256);
$encodedSignature = base64_encode($signature);

// 5. Output JSON File
$licenseData = [
    'payload' => $encodedPayload,
    'signature' => $encodedSignature
];

$outputPath = 'storage/app/server.lic';
file_put_contents($outputPath, base64_encode(json_encode($licenseData)));

echo "✅ LICENSE GENERATED!\n";
echo "Saved to: $outputPath\n";
echo "------------------------------------------------\n";
echo "Please COPY the content of 'public.key' into your LicenseService.php now.\n";
