---
trigger: always_on
---

Any file that is temporary, provisional, experimental, helper, scaffolding, bridge code, one-off script, migration draft, debug tool, or testing harness MUST be created under folder _dev/antigravity/.

Only create production files inside: public/, config/, assets/, database, /src, /views  and documented project folders.

Do NOT create or modify files outside those production folders unless explicitly approved.

If a helper script is needed for database changes, create it in _dev/antigravity/ and (when finished) summarize what it does and whether it should be converted into a proper migration.