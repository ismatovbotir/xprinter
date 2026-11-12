# Product template — fill in and send back

File: `products.csv` (separator `;`, opens in Excel and Google Sheets).
The 12 rows are the current demo products — fix, delete or add rows.

## Columns

| Column | Required | What to write |
|---|---|---|
| `model` | **yes** | Exact model number, e.g. `XP-Q890K` |
| `category` | **yes** | `receipt`, `label`, `mobile`, `wristband` — or a new name (e.g. `a4`, `scanner`), I will create it |
| `paper_width` | no | `58 mm`, `80 mm`, `2 inch (57 mm)`, `3 inch (80 mm)`, `4 inch (108 mm)` |
| `print_speed` | no | e.g. `250 mm/s` |
| `dpi` | no | `203` or `300` |
| `interface` | no | e.g. `USB`, `USB + Serial + LAN`, `USB + Ethernet`, `USB + Bluetooth` |
| `connectivity` | no | mobile only: `Bluetooth`, `Wi-Fi`, `Wired` |
| `autocutter` | no | receipt only: `yes` / `no` |
| `use_case` | no | Who buys it: `supermarket`, `cafe`, `Uzum sellers`… — used for descriptions |
| `notes` | no | Anything else: `bestseller`, `budget`, `new`, `discontinued`… |

Only `model` and `category` are needed. Empty spec cells I will fill from the
official Xprinter datasheets and mark them for you to check.

## How seeding works

- `products.json` is the **source the seeder reads**: model, category, specs, names and
  descriptions in uz/ru/en, OLX price range and a `verify` note for specs to double-check.
- `products.csv` is the same list in short form for editing in Excel. After changing it,
  ask Claude to regenerate the descriptions in `products.json`.
- Run: `php artisan db:seed --class=CatalogSeeder` — safe to re-run. Products are matched by
  model slug and updated; their specs are replaced with the JSON values. Products not in the
  file are left untouched (never deleted).
- Generic Chinese clones (XP-80, XP-80T/80A/80Q/80 Ultra, XP-58) are intentionally excluded.
