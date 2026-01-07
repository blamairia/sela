---
description: Workflow for desktop distribution experiments (obfuscation, licensing, compilation) in isolated branches
---

# Desktop Distribution Experiments Workflow

This workflow manages experiments for desktop distribution, obfuscation, licensing, and code compilation in **isolated Git branches** without touching the main branch or polluting the local codebase.

## Branch Naming Convention

Use the following branch naming pattern:
- `exp/desktop-nativephp` - NativePHP experiments
- `exp/desktop-electron` - Electron wrapper experiments  
- `exp/obfuscate-yakpro` - YakPro obfuscation
- `exp/obfuscate-ioncube` - IonCube encoding
- `exp/license-custom` - Custom licensing system
- `exp/compile-php-compiler` - PHP compilation tools
- `exp/compile-zzz` - Other compilation approaches

## Experiment Directories (Auto-ignored)

All experiments should live in `/experiments/` directory:
```
/experiments/
├── desktop/           # Desktop packaging (NativePHP, Electron, etc.)
├── obfuscation/       # Code obfuscation tools output
├── licensing/         # License system implementations
├── compilation/       # Compiled PHP output
└── builds/            # Final build outputs
```

---

## Starting a New Experiment

### Step 1: Ensure clean main branch
// turbo
```powershell
git checkout main
git pull origin main
```

### Step 2: Create experiment branch
```powershell
# Replace <experiment-type> and <tool-name> with your experiment
git checkout -b exp/<experiment-type>-<tool-name>
```

### Step 3: Enable experiment directories in .gitignore
// turbo
```powershell
# Add experiments directory to .gitignore tracking exceptions
# This allows the experiment branch to track /experiments/ while main ignores it

# Create the experiments directory structure
New-Item -ItemType Directory -Force -Path "experiments/desktop"
New-Item -ItemType Directory -Force -Path "experiments/obfuscation"
New-Item -ItemType Directory -Force -Path "experiments/licensing"
New-Item -ItemType Directory -Force -Path "experiments/compilation"
New-Item -ItemType Directory -Force -Path "experiments/builds"
```

### Step 4: Add experiment-specific .gitignore overrides
Create `/experiments/.gitignore` with:
```
# Allow tracking in experiment branches
!*
# But ignore build artifacts
*.exe
*.msi
*.dmg
*.deb
*.rpm
*.zip
*.tar.gz
vendor/
node_modules/
```

### Step 5: Document your experiment
Create `experiments/README.md` with experiment goals and notes.

---

## Working on an Existing Experiment

### Step 1: Switch to experiment branch
```powershell
git checkout exp/<experiment-name>
```

### Step 2: Sync with main (if needed)
```powershell
git rebase main
# Or merge if you prefer:
# git merge main
```

---

## Completing an Experiment

### Step 1: Document results
Update `experiments/README.md` with findings, pros/cons, and recommendation.

### Step 2: If successful and ready to merge
```powershell
# Create a clean feature branch from the experiment
git checkout -b feature/desktop-<chosen-approach>

# Cherry-pick or squash commits as needed
# Then create PR to main
```

### Step 3: Archive unsuccessful experiments
```powershell
# Keep the branch for reference but mark as archived
git branch -m exp/<name> archive/exp-<name>
git push origin archive/exp-<name>
git push origin --delete exp/<name>
```

---

## Cleanup: Removing Experiment Data

### After finishing experiments (local cleanup)
// turbo
```powershell
# Remove experiments directory
Remove-Item -Recurse -Force "experiments" -ErrorAction SilentlyContinue

# Remove any build artifacts
Remove-Item -Recurse -Force "dist" -ErrorAction SilentlyContinue
Remove-Item -Recurse -Force "build" -ErrorAction SilentlyContinue
```

### Reset .gitignore to main state
// turbo
```powershell
git checkout main -- .gitignore
```

---

## Quick Reference: Experiment-Specific Setup

### NativePHP Experiment
```powershell
git checkout -b exp/desktop-nativephp
composer require nativephp/laravel
php artisan native:install
# Work in experiments/desktop/nativephp/
```

### Electron Experiment
```powershell
git checkout -b exp/desktop-electron
cd experiments/desktop
npx -y create-electron-app@latest electron-wrapper
# Work in experiments/desktop/electron-wrapper/
```

### YakPro Obfuscation Experiment
```powershell
git checkout -b exp/obfuscate-yakpro
# Clone YakPro-po to experiments/obfuscation/
git clone https://github.com/nicksagona/YakPro-po experiments/obfuscation/yakpro
# Configure and run obfuscation
```

### IonCube Experiment
```powershell
git checkout -b exp/obfuscate-ioncube
# Download IonCube encoder to experiments/obfuscation/ioncube/
# Work on license integration
```

### Custom Licensing Experiment
```powershell
git checkout -b exp/license-custom
# Work in experiments/licensing/
# Implement license validation, activation, etc.
```

---

## .gitignore Management

The main `.gitignore` should **always** ignore:
```gitignore
# Desktop experiments (ignored on main, tracked on exp/* branches)
/experiments/
/dist/
/build/
*.msi
*.exe
*.dmg
```

On experiment branches, create `/experiments/.gitignore` to track config files while ignoring large binaries.

---

## Safety Rules

1. **NEVER** commit experiment code directly to `main`
2. **ALWAYS** create a new branch before starting work
3. **KEEP** experiments isolated in `/experiments/` directory
4. **DOCUMENT** every experiment with README and findings
5. **CLEAN UP** branches after decisions are made
6. **BACKUP** successful experiments before archiving

---

## Recommended Experiment Order

1. **Obfuscation** - Test code protection first (YakPro → IonCube)
2. **Licensing** - Implement license system on obfuscated code
3. **Compilation** - Test compiled PHP approaches
4. **Desktop Packaging** - Package final protected code (NativePHP → Electron)
