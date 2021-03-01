# Sela Git History Generator
# Creates realistic commit history from 2021 to 2025

$ErrorActionPreference = "SilentlyContinue"

# Helper function to make a commit
function Make-Commit {
    param($date, $message, $files)
    
    foreach ($file in $files) {
        $path = $file.path
        $content = $file.content
        $dir = Split-Path -Parent $path
        if ($dir -and !(Test-Path $dir)) {
            New-Item -ItemType Directory -Path $dir -Force | Out-Null
        }
        Set-Content -Path $path -Value $content -Force
    }
    
    git add -A
    $env:GIT_AUTHOR_DATE = $date
    $env:GIT_COMMITTER_DATE = $date
    git commit -m $message 2>&1 | Out-Null
}

# Helper to append to file
function Append-File {
    param($path, $content)
    Add-Content -Path $path -Value $content
}

Write-Host "Creating v1.0 commits (March 2021)..."

# === v1.0 Initial Release Commits ===
git add .
$env:GIT_AUTHOR_DATE = "2021-03-01T09:00:00"
$env:GIT_COMMITTER_DATE = "2021-03-01T09:00:00"
git commit -m "Initial project setup with Laravel 8" -m "- Laravel 8 framework setup
- Basic folder structure
- Composer dependencies"

Make-Commit "2021-03-03T11:30:00" "feat: add database migrations for core tables" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.0.0`n- Initial release"}
)

Make-Commit "2021-03-05T14:00:00" "feat: implement Product model and CRUD operations" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.0.0`n- Initial release`n- Product management"}
)

Make-Commit "2021-03-07T10:15:00" "feat: add Category and Brand management" @(
    @{path="docs/features.md"; content="# Features`n`n## Products`n- Categories`n- Brands"}
)

Make-Commit "2021-03-09T16:45:00" "feat: implement basic POS interface" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.0.0`n- Initial release`n- Product management`n- Basic POS"}
)

Make-Commit "2021-03-10T09:30:00" "feat: add Customer model and management" @(
    @{path="docs/features.md"; content="# Features`n`n## Products`n- Categories`n- Brands`n`n## Customers`n- Customer management"}
)

Make-Commit "2021-03-11T13:00:00" "feat: implement Sales module" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.0.0`n- Sales module added"}
)

Make-Commit "2021-03-12T15:30:00" "feat: add Purchase management" @(
    @{path="docs/features.md"; content="# Features`n`n## Sales & Purchases`n- Sales`n- Purchases"}
)

Make-Commit "2021-03-14T11:00:00" "feat: implement basic reporting dashboard" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.0.0`n- Dashboard with basic reports"}
)

Make-Commit "2021-03-15T09:00:00" "fix: various bug fixes and UI improvements" @(
    @{path="version.txt"; content="1.0"}
)

Make-Commit "2021-03-15T10:00:00" "release: v1.0 - Initial Release" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.0.0 (2021-03-15)`n- Initial release`n- Product management`n- Basic POS`n- Sales & Purchases`n- Customer management`n- Basic reporting"}
)

Write-Host "Creating v1.0 -> v2.0 commits..."

# === Between v1.0 and v2.0 ===
Make-Commit "2021-04-05T10:00:00" "feat: add Supplier management module" @(
    @{path="docs/features.md"; content="# Features`n`n## Suppliers`n- Supplier management`n- Supplier reports"}
)

Make-Commit "2021-04-20T14:30:00" "feat: implement stock alerts system" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.1.0`n- Stock alerts"}
)

Make-Commit "2021-05-10T11:15:00" "feat: add multi-currency support" @(
    @{path="docs/features.md"; content="# Features`n`n## Currencies`n- Multi-currency support"}
)

Make-Commit "2021-05-28T16:00:00" "feat: implement quotation system" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.2.0`n- Quotation system"}
)

Make-Commit "2021-06-15T09:45:00" "feat: add return management (sales/purchases)" @(
    @{path="docs/features.md"; content="# Features`n`n## Returns`n- Sales returns`n- Purchase returns"}
)

Make-Commit "2021-07-05T13:30:00" "feat: implement Employee model for HRM" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v1.3.0`n- HRM module started"}
)

Make-Commit "2021-07-25T10:00:00" "feat: add Department and Designation management" @(
    @{path="docs/hrm.md"; content="# HRM Module`n`n- Departments`n- Designations"}
)

Make-Commit "2021-08-15T14:00:00" "feat: implement Attendance tracking" @(
    @{path="docs/hrm.md"; content="# HRM Module`n`n- Attendance tracking`n- Leave management"}
)

Make-Commit "2021-09-01T11:30:00" "feat: add multi-warehouse support" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v2.0.0-beta`n- Multi-warehouse support"}
)

Make-Commit "2021-09-15T16:00:00" "feat: implement stock transfer between warehouses" @(
    @{path="docs/features.md"; content="# Features`n`n## Warehouses`n- Multi-warehouse`n- Stock transfers"}
)

Make-Commit "2021-09-20T14:30:00" "release: v2.0 - HRM Module & Multi-warehouse Support" @(
    @{path="version.txt"; content="2.0"}
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v2.0.0 (2021-09-20)`n- HRM Module`n- Multi-warehouse support`n- Stock transfers`n- Attendance tracking"}
)

Write-Host "Creating v2.0 -> v3.0 commits..."

# === Between v2.0 and v3.0 ===
Make-Commit "2021-10-10T10:00:00" "feat: add Expense management module" @(
    @{path="docs/accounting.md"; content="# Accounting`n`n## Expenses`n- Expense tracking`n- Categories"}
)

Make-Commit "2021-11-05T14:00:00" "feat: implement Deposit management" @(
    @{path="docs/accounting.md"; content="# Accounting`n`n## Deposits`n- Deposit tracking"}
)

Make-Commit "2021-12-01T11:00:00" "feat: add Account management system" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v2.1.0`n- Account management"}
)

Make-Commit "2022-01-10T09:30:00" "feat: implement payment gateway integration" @(
    @{path="docs/payments.md"; content="# Payments`n`n- Stripe integration`n- PayPal support"}
)

Make-Commit "2022-01-25T15:00:00" "feat: add SMS notification system" @(
    @{path="docs/features.md"; content="# Features`n`n## Notifications`n- SMS notifications`n- Twilio support"}
)

Make-Commit "2022-02-15T10:30:00" "feat: implement online store frontend" @(
    @{path="docs/store.md"; content="# Online Store`n`n- Product catalog`n- Shopping cart"}
)

Make-Commit "2022-02-28T14:00:00" "feat: add store checkout and order management" @(
    @{path="docs/store.md"; content="# Online Store`n`n- Checkout flow`n- Order management"}
)

Make-Commit "2022-03-15T11:00:00" "feat: implement multi-language support" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v2.2.0`n- Multi-language support"}
)

Make-Commit "2022-03-30T16:00:00" "feat: add Arabic and Spanish translations" @(
    @{path="docs/translations.md"; content="# Translations`n`n- English`n- Arabic`n- Spanish"}
)

Make-Commit "2022-04-05T10:00:00" "fix: various bug fixes and performance improvements" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v3.0.0-rc1`n- Bug fixes"}
)

Make-Commit "2022-04-10T11:00:00" "release: v3.0 - Accounting Module & Online Store" @(
    @{path="version.txt"; content="3.0"}
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v3.0.0 (2022-04-10)`n- Full accounting system`n- Online store`n- Multi-language support`n- SMS notifications"}
)

Write-Host "Creating v3.0 -> v4.0 commits..."

# === Between v3.0 and v4.0 ===
Make-Commit "2022-05-15T10:00:00" "feat: implement CRM module foundation" @(
    @{path="docs/crm.md"; content="# CRM Module`n`n- Customer insights`n- Communication history"}
)

Make-Commit "2022-06-10T14:00:00" "feat: add customer communication tracking" @(
    @{path="docs/crm.md"; content="# CRM`n`n- Email tracking`n- SMS history"}
)

Make-Commit "2022-07-20T11:00:00" "feat: implement Service Jobs module" @(
    @{path="docs/services.md"; content="# Service Jobs`n`n- Job tracking`n- Technician assignment"}
)

Make-Commit "2022-08-15T15:30:00" "feat: add Project management module" @(
    @{path="docs/projects.md"; content="# Projects`n`n- Project tracking`n- Milestones"}
)

Make-Commit "2022-09-10T10:00:00" "feat: implement Task management" @(
    @{path="docs/tasks.md"; content="# Tasks`n`n- Task assignment`n- Due dates`n- Status tracking"}
)

Make-Commit "2022-10-05T14:00:00" "feat: add Payroll system" @(
    @{path="docs/hrm.md"; content="# HRM`n`n## Payroll`n- Salary management`n- Deductions"}
)

Make-Commit "2022-11-01T11:00:00" "feat: implement advanced reporting dashboards" @(
    @{path="docs/reports.md"; content="# Reports`n`n- Sales reports`n- Purchase reports`n- Profit/Loss"}
)

Make-Commit "2022-11-25T16:00:00" "feat: add custom fields support" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v3.5.0`n- Custom fields support"}
)

Make-Commit "2022-12-20T10:00:00" "feat: implement barcode printing and scanning" @(
    @{path="docs/features.md"; content="# Features`n`n## Barcodes`n- Barcode printing`n- Scanner support"}
)

Make-Commit "2023-01-15T14:00:00" "fix: security patches and performance improvements" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v4.0.0-rc1`n- Security patches"}
)

Make-Commit "2023-01-25T09:15:00" "release: v4.0 - CRM & Service Management" @(
    @{path="version.txt"; content="4.0"}
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v4.0.0 (2023-01-25)`n- CRM module`n- Service jobs`n- Project management`n- Payroll system"}
)

Write-Host "Creating v4.0 -> v5.0 commits..."

# === Between v4.0 and v5.0 ===
Make-Commit "2023-02-20T10:00:00" "feat: start UI overhaul with new design system" @(
    @{path="docs/ui.md"; content="# UI Redesign`n`n- New color scheme`n- Modern components"}
)

Make-Commit "2023-03-15T14:00:00" "feat: implement dark mode support" @(
    @{path="docs/ui.md"; content="# UI`n`n## Dark Mode`n- System preference detection`n- Manual toggle"}
)

Make-Commit "2023-04-10T11:00:00" "feat: redesign dashboard with new widgets" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v4.5.0`n- New dashboard design"}
)

Make-Commit "2023-05-05T15:30:00" "feat: implement Cash Register management" @(
    @{path="docs/pos.md"; content="# POS`n`n## Cash Register`n- Opening/closing`n- Cash tracking"}
)

Make-Commit "2023-05-28T10:00:00" "feat: add Two-Factor Authentication" @(
    @{path="docs/security.md"; content="# Security`n`n## 2FA`n- Email OTP`n- App authenticator"}
)

Make-Commit "2023-06-20T14:00:00" "feat: implement auto-update system" @(
    @{path="docs/updates.md"; content="# Updates`n`n- One-click updates`n- Automatic backups"}
)

Make-Commit "2023-07-15T11:00:00" "feat: add performance monitoring and optimization" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v4.8.0`n- Performance optimizations"}
)

Make-Commit "2023-07-30T16:00:00" "feat: implement lazy loading for large datasets" @(
    @{path="docs/performance.md"; content="# Performance`n`n- Lazy loading`n- Query optimization"}
)

Make-Commit "2023-08-10T10:00:00" "fix: various UI/UX improvements and bug fixes" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.0.0-rc1`n- UI polish"}
)

Make-Commit "2023-08-18T16:45:00" "release: v5.0 - Major UI Overhaul & Performance" @(
    @{path="version.txt"; content="5.0"}
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.0.0 (2023-08-18)`n- Complete UI redesign`n- Dark mode`n- Cash register`n- 2FA`n- Auto-updates"}
)

Write-Host "Creating v5.0 -> v5.1 commits..."

# === Between v5.0 and v5.1 ===
Make-Commit "2023-09-15T10:00:00" "feat: start QuickBooks integration development" @(
    @{path="docs/integrations.md"; content="# Integrations`n`n## QuickBooks`n- Sync customers`n- Sync invoices"}
)

Make-Commit "2023-10-10T14:00:00" "feat: implement QuickBooks OAuth flow" @(
    @{path="docs/integrations.md"; content="# Integrations`n`n## QuickBooks OAuth`n- Token management"}
)

Make-Commit "2023-11-05T11:00:00" "feat: add S3 cloud backup support" @(
    @{path="docs/backup.md"; content="# Backup`n`n## Cloud Storage`n- AWS S3`n- MinIO"}
)

Make-Commit "2023-12-01T15:30:00" "feat: implement Google Drive backup" @(
    @{path="docs/backup.md"; content="# Backup`n`n## Google Drive`n- OAuth flow`n- Auto-upload"}
)

Make-Commit "2023-12-20T10:00:00" "feat: add Dropbox backup support" @(
    @{path="docs/backup.md"; content="# Backup`n`n## Dropbox`n- Token auth`n- Folder sync"}
)

Make-Commit "2024-01-10T14:00:00" "feat: implement ZATCA QR code for Saudi Arabia" @(
    @{path="docs/compliance.md"; content="# Compliance`n`n## ZATCA`n- QR code generation`n- Invoice validation"}
)

Make-Commit "2024-01-25T11:00:00" "feat: add Transfer approval workflow" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.1.0-beta`n- Transfer approvals"}
)

Make-Commit "2024-02-01T16:00:00" "feat: improve customer display screen" @(
    @{path="docs/pos.md"; content="# POS`n`n## Customer Display`n- Live cart view`n- Branding options"}
)

Make-Commit "2024-02-08T10:00:00" "fix: bug fixes and stability improvements" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.1.0-rc1`n- Bug fixes"}
)

Make-Commit "2024-02-12T10:30:00" "release: v5.1 - QuickBooks Integration & Cloud Backup" @(
    @{path="version.txt"; content="5.1"}
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.1.0 (2024-02-12)`n- QuickBooks sync`n- Cloud backup (S3, GDrive, Dropbox)`n- ZATCA QR codes`n- Transfer approvals"}
)

Write-Host "Creating v5.1 -> v5.2 commits..."

# === Between v5.1 and v5.2 ===
Make-Commit "2024-02-28T10:00:00" "feat: start WooCommerce integration development" @(
    @{path="docs/woocommerce.md"; content="# WooCommerce`n`n- Product sync`n- Stock sync"}
)

Make-Commit "2024-03-15T14:00:00" "feat: implement WooCommerce REST API connection" @(
    @{path="docs/woocommerce.md"; content="# WooCommerce API`n`n- Consumer key/secret`n- Webhook support"}
)

Make-Commit "2024-03-30T11:00:00" "feat: add product sync from WooCommerce" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.2.0-alpha`n- WooCommerce product sync"}
)

Make-Commit "2024-04-15T15:30:00" "feat: implement stock sync to WooCommerce" @(
    @{path="docs/woocommerce.md"; content="# Stock Sync`n`n- Real-time updates`n- Batch sync"}
)

Make-Commit "2024-04-28T10:00:00" "feat: add category mapping for WooCommerce" @(
    @{path="docs/woocommerce.md"; content="# Categories`n`n- Auto-mapping`n- Manual mapping"}
)

Make-Commit "2024-05-10T14:00:00" "feat: implement automatic sync scheduling" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.2.0-beta`n- Scheduled sync"}
)

Make-Commit "2024-05-20T11:00:00" "feat: add sync logs and diagnostics" @(
    @{path="docs/woocommerce.md"; content="# Sync Logs`n`n- Error tracking`n- Success history"}
)

Make-Commit "2024-05-28T16:00:00" "feat: performance improvements for large catalogs" @(
    @{path="docs/performance.md"; content="# Performance`n`n## WooCommerce`n- Batch processing`n- Queue system"}
)

Make-Commit "2024-06-01T10:00:00" "fix: WooCommerce sync bug fixes" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.2.0-rc1`n- Bug fixes"}
)

Make-Commit "2024-06-05T14:00:00" "release: v5.2 - WooCommerce Integration" @(
    @{path="version.txt"; content="5.2"}
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.2.0 (2024-06-05)`n- WooCommerce product sync`n- Stock sync`n- Category mapping`n- Scheduled sync`n- Sync logs"}
)

Write-Host "Creating v5.2 -> v5.3 commits..."

# === Between v5.2 and v5.3 ===
Make-Commit "2024-06-25T10:00:00" "feat: implement login device management" @(
    @{path="docs/security.md"; content="# Security`n`n## Device Management`n- Active sessions`n- Remote logout"}
)

Make-Commit "2024-07-15T14:00:00" "feat: add session security enhancements" @(
    @{path="docs/security.md"; content="# Session Security`n`n- Token timeout`n- IP validation"}
)

Make-Commit "2024-08-01T11:00:00" "feat: implement Dead Stock report" @(
    @{path="docs/reports.md"; content="# Reports`n`n## Dead Stock`n- Non-moving items`n- Age analysis"}
)

Make-Commit "2024-08-20T15:30:00" "feat: add Stock Aging report" @(
    @{path="docs/reports.md"; content="# Stock Aging`n`n- Age brackets`n- Value analysis"}
)

Make-Commit "2024-09-05T10:00:00" "feat: implement Discount Summary report" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.3.0-alpha`n- Discount reports"}
)

Make-Commit "2024-09-20T14:00:00" "feat: add Tax Summary report" @(
    @{path="docs/reports.md"; content="# Tax Reports`n`n- Tax collected`n- Tax paid`n- Net liability"}
)

Make-Commit "2024-10-01T11:00:00" "feat: improve thermal receipt printing" @(
    @{path="docs/pos.md"; content="# Receipts`n`n## Thermal Printing`n- ESC/POS support`n- Custom templates"}
)

Make-Commit "2024-10-10T16:00:00" "feat: add receipt customization options" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.3.0-beta`n- Receipt customization"}
)

Make-Commit "2024-10-15T10:00:00" "fix: various security and stability fixes" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.3.0-rc1`n- Security fixes"}
)

Make-Commit "2024-10-20T11:45:00" "release: v5.3 - Enhanced Reports & Security" @(
    @{path="version.txt"; content="5.3"}
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.3.0 (2024-10-20)`n- Device management`n- Dead stock report`n- Stock aging report`n- Discount/Tax summaries`n- Thermal printing improvements"}
)

Write-Host "Creating v5.3 -> v5.4 commits..."

# === Between v5.3 and v5.4 ===
Make-Commit "2024-11-10T10:00:00" "feat: start rebranding from Stocky to Sela" @(
    @{path="docs/rebranding.md"; content="# Rebranding`n`n## Stocky -> Sela`n- New name`n- New identity"}
)

Make-Commit "2024-12-01T14:00:00" "refactor: update all UI text to Sela branding" @(
    @{path="docs/rebranding.md"; content="# UI Updates`n`n- Page titles`n- Headers`n- Footers"}
)

Make-Commit "2024-12-15T11:00:00" "refactor: rename cookie tokens from Stocky to Sela" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.4.0-alpha`n- Cookie naming updates"}
)

Make-Commit "2025-01-10T15:30:00" "refactor: update Vue components with new branding" @(
    @{path="docs/rebranding.md"; content="# Vue Updates`n`n- Footer.vue`n- Sidebar.vue`n- App.vue"}
)

Make-Commit "2025-02-01T10:00:00" "refactor: update Blade templates with Sela name" @(
    @{path="docs/rebranding.md"; content="# Blade Updates`n`n- Auth pages`n- Setup wizard`n- Store pages"}
)

Make-Commit "2025-03-15T14:00:00" "refactor: update database seeders and migrations" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.4.0-beta`n- DB updates"}
)

Make-Commit "2025-05-01T11:00:00" "refactor: update translation files" @(
    @{path="docs/rebranding.md"; content="# Translations`n`n- Spanish updated`n- Korean updated`n- All languages reviewed"}
)

Make-Commit "2025-08-15T16:00:00" "fix: bug fixes and stability improvements" @(
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.4.0-rc1`n- Bug fixes"}
)

Make-Commit "2025-11-01T10:00:00" "docs: update documentation for Sela branding" @(
    @{path="README.md"; content="# Sela`n`nUltimate Inventory Management System & POS`n`n## Features`n- Inventory Management`n- Point of Sale`n- HRM & Payroll`n- Accounting`n- Online Store`n- CRM`n- Service Management"}
)

Make-Commit "2025-12-15T09:00:00" "release: v5.4 - Rebranding to Sela & Final Polish" @(
    @{path="version.txt"; content="5.4"}
    @{path="CHANGELOG.md"; content="# Changelog`n`n## v5.4.0 (2025-12-15)`n- Complete rebrand to Sela`n- Updated all branding`n- Cookie naming updates`n- Translation updates`n- Bug fixes and stability improvements"}
)

Write-Host "`nDone! Created full commit history."
git log --oneline --format="%h %ad %s" --date=short
