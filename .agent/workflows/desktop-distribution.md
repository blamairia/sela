---
description: How to organize and create desktop distribution experiments for Stocky
---

# Desktop Distribution Workflow

## Project Organization

### Folder Structure
```
Stocky v5.4/
├── app/                    # Source code (DO NOT MODIFY in experiments)
├── public/
├── ...
├── desktop/                # Working PHP Desktop bundle (gitignored)
│   └── phpdesktop-chrome-130.1-php-8.3/
│       ├── www/            # Copied Stocky files
│       └── phpdesktop-chrome.exe
├── experiments/            # Testing different protections (gitignored)
│   ├── 01_desktop_basic/
│   ├── 02_phpbolt/
│   ├── 03_integrity/
│   ├── 04_licensing/
│   └── 05_full_package/
├── build/                  # Build scripts (gitignored)
└── installer/              # Inno Setup files (gitignored)
```

### Git Branches (Recommended)
- `main` - Production code only
- `dev` - Development work
- `feature/desktop-distribution` - All desktop packaging work

## How to Copy Files to PHP Desktop

### What to Copy
```
COPY these folders:
- app/
- bootstrap/
- config/
- database/
- public/
- resources/views/
- routes/
- storage/
- vendor/
- .env
- artisan
- composer.json
```

### What to EXCLUDE (Sensitive)
```
DO NOT include in distribution:
- app/Console/Commands/GenerateLicense.php (license generator)
- app/Console/Commands/GenerateIntegrity.php (checksum generator)
- build/ folder
- desktop/ folder
- installer/ folder
- .git/ folder
- node_modules/
- tests/
- .env.example
- DESKTOP_DISTRIBUTION.md
```

### Manual Copy Steps
1. Clear the destination: `phpdesktop-chrome-130.1-php-8.3\www\`
2. Copy folders listed above
3. Delete sensitive files from destination
4. Test by running `phpdesktop-chrome.exe`

## What Needs Encryption (phpBolt)
- `app/Http/Controllers/` - 130 files
- `app/Models/` - 121 files
- `app/Services/` - 10 files
- `app/Http/Middleware/`
- `routes/` - 4 files

## What Needs Integrity Checks
- `public/js/bundle/*.js` - Vue.js bundles
- `public/images/logo*` - Branding
- `public/favicon.ico`
- `settings.json` - PHP Desktop config

## Current Status
- [x] 01_desktop_basic - PHP Desktop working
- [ ] 02_phpbolt - PHP encryption
- [ ] 03_integrity - Asset checksums
- [ ] 04_licensing - License system
- [ ] 05_full_package - Combined
