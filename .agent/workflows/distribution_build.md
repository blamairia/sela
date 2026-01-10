---
description: How to build, license, and encode the application for desktop distribution.
---

# 📦 Desktop Distribution Build Workflow

This workflow details the exact steps to transform the open-source Laravel app into a secured, licensed Desktop application using IonCube and the "Gatekeeper" system.

## 1. 🔑 Pre-requisites & Key Generation
**Goal**: Create the RSA keys that will lock the application to the license.

1.  **Generate Keys** (One-time setup per major release):
    ```bash
    php license_generator.php generate_keys
    ```
    *   `private.key` -> Keep safe! Used to sign licenses.
    *   `public.key` -> Embedded in `App\Services\LicenseService.php`.

2.  **Verify Public Key**:
    *   Open `app/Services/LicenseService.php`.
    *   Ensure the `$publicKey` heredoc matches the content of `public.key`.

## 2. 🧹 Preparation
**Goal**: Clean the workspace before encoding.

1.  **Clear Caches**:
    ```bash
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    ```
2.  **Remove Dev Files** (Optional but recommended):
    *   Exclude `.git/` folder.
    *   Exclude `tests/` folder (unless needed by app).
    *   Exclude `license_generator.php` (Don't ship the generator!).
    *   Exclude `private.key` (CRITICAL: NEVER SHIP THIS).

## 3. 🛡️ Encoding (The "Ultimate PHP Encoder")
**Goal**: Obfuscate the logic so the licensing check cannot be bypassed.

1.  **Launch GUI**: Open "Ultimate PHP Encoder" (IonCube Wrapper).
2.  **Source**: `C:\xampp\htdocs\Stocky v5.4`
3.  **Target**: `C:\xampp\htdocs\Stocky-Encoded-v5.4` (or your build folder).
4.  **PHP Version**: **8.2** (Must match the target runtime).
5.  **Files to Encode**:
    *   ✅ `app/` (Contains Middleware & Service - **Mandatory**)
    *   ✅ `routes/` (Protects the API endpoints - **Mandatory**)
    *   (Optional) `config/` (If you want to hide DB creds, but be careful with caching).
6.  **Files to Copy (Unencoded)**:
    *   `vendor/`, `public/`, `resources/`, `storage/`, `bootstrap/`.
    *   `.env` (The installer will generate this, but for testing copy it).

## 4. 🚀 Post-Encoding Setup
**Goal**: Configure the encoded app to run.

1.  **Environment**:
    *   Ensure `storage/app` exists.
    *   Set `APP_URL` in `.env` correctly (e.g., `http://localhost:8080`).
2.  **License File**:
    *   Generate a license for the target machine:
        ```bash
        php license_generator.php <HWID> <EXPIRY>
        ```
    *   Place it in `storage/app/server.lic`.
3.  **Permissions**:
    *   Ensure the web server has **Read** access to `server.lic`.
    *   Ensure the web server has **Write** access to `storage/logs`.

## 5. 🧪 Verification Checklist
*   [ ] Access `/license/check` -> Should return JSON or View (Not 403).
*   [ ] Access Dashboard -> Should load if license is valid.
*   [ ] Delete `server.lic` -> Access Dashboard -> Should show **403 Forbidden**.

---

## 🆘 Troubleshooting
*   **"License file not found"**: Run `php artisan config:clear` in the encoded folder.
*   **"403 Forbidden" Loop**: Ensure `license/*` routes are exempted in `EnsureClientAuthorized.php`.
*   **"Corrupted" Error**: Check PHP version mismatch between Encoder and Runtime.
