---
description: Standard protocol for high-quality, tested feature development. Use this for ALL complex coding tasks.
---

# 🛡️ Senior Engineer Development Protocol

**Goal**: Deliver bug-free, edge-case-proof code through rigorous planning, testing, and professional version control.

## 1. 📝 Phase 1: Deep Planning & Analysis
*   **Analyze Request**: Break down the feature into atomic components.
*   **Identify Edge Cases**: Explicitly list what could go wrong (e.g., "License expired yesterday", "Header missing", "Database down").
*   **Update Artifacts**:
    *   `task.md`: Add granular steps.
    *   `implementation_plan.md`: Detail the technical approach.
*   **STOP & ASK**: Present the plan and edge cases to the user. Do not proceed without "LGTM".

## 2. 🧪 Phase 2: Test-Driven Design (TDD)
*   *Before* writing the feature code, plan/write the **PHPUnit Tests**.
*   **Coverage Requirements**:
    *   Happy Path (It works).
    *   Edge Cases (It fails safely).
    *   Security Checks (It blocks unauthorized access).
*   **Command**: `php artisan make:test FeatureNameTest`

## 3. 💻 Phase 3: Implementation & Git Protocol
*   **Write Code**: Implement the logic to satisfy the tests.
*   **Refactor**: Ensure code is clean, readable, and follows SOLID principles.
*   **Git Standards (Senior Level)**:
    *   **Atomic Commits**: One logical change per commit. Never mix formatting changes, bug fixes, and features in one commit.
    *   **Conventional Commits**: You must strictly use these prefixes:
        *   `feat: ...` for a new feature.
        *   `fix: ...` for a bug fix.
        *   `refactor: ...` for code improvement without functionality change.
        *   `test: ...` for adding/fixing tests.
        *   `docs: ...` for documentation only.
        *   `chore: ...` for build scripts, configs (no production code change).
    *   **Message Quality**:
        *   **Subject**: Imperative mood ("Add", not "Added"), max 50 chars.
        *   **Body**: (If needed) Explain *why* the change was made, not *what*.
    *   **No Broken Builds**: Never commit code that fails tests.

## 4. ✅ Phase 4: Verification & Feedback
*   **Run Tests**: `php artisan test --filter FeatureName`
*   **Manual Verify**: Check `tinker`, Logs, or UI if applicable.
*   **Report**: Show the user proof of success (Test results, Screenshots).
*   **STOP & ASK**: Get confirmation before moving to the next feature.

## 5. 📦 Phase 5: Finalization ("The Click")
*   Update `task.md` to [x].
*   Clean up temporary test files (if needed).
*   Ensure the codebase is stable for the next agent.
