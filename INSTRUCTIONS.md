# HOBYtracker UI Redesign — Installation Instructions

## What's in this ZIP

These files implement the UI redesign described in the feature branch planning:
- Bootstrap offcanvas sidebar replaces SmartMenus entirely
- Ambassador and staff list pages replaced with card-based mobile-friendly layouts
- Ambassador and staff detail pages replaced with iOS-style sectioned cards
- New design system (CSS custom properties, Inter font, HOBY navy/yellow palette)
- All DataTables pages (bed checks, dietary, requirements, etc.) are untouched

## Files changed

```
public/assets/css/app.css          — full CSS rewrite
public/assets/js/app.js            — SmartMenus removed, card filter added
templates/base.html.twig           — SmartMenus CSS/JS links removed
templates/partials/nav.html.twig   — full replacement with Bootstrap offcanvas
templates/ambassador/index.html.twig  — card list (replaces DataTable)
templates/ambassador/show.html.twig   — sectioned detail (replaces Columnizer)
templates/user/index.html.twig        — card list (replaces DataTable)
templates/user/show.html.twig         — sectioned detail (replaces Columnizer)
```

## Installation

From the **root of your project** (`~/Projects/hobytracker` or wherever your repo lives):

```bash
# 1. Make sure you're on the UI redesign feature branch
git checkout ui-redesign   # or whatever you named it

# 2. Unzip this file into your project root
#    (the -o flag overwrites existing files)
unzip -o hobytracker-ui-redesign.zip

# 3. That's it — no composer or database changes needed.
#    Reload the app in your browser.
```

If you want to preview before committing:

```bash
# Start your local dev server if you have one
symfony serve
# Then visit http://localhost:8000
```

## Notes

- **No PHP changes.** All 8 files are CSS, JS, or Twig templates only.
- The `app.css.map` and `app.scss` files in `public/assets/css/` are now stale
  (the CSS is no longer compiled from SCSS). They won't cause any errors but
  you can delete them to keep things clean:
  ```bash
  rm public/assets/css/app.css.map public/assets/css/app.scss
  ```
- The `user.getRequirementNotes` call in `user/show.html.twig` uses the getter
  method name directly — if Twig complains, change it to `user.requirementNotes`.
- The `app.user.consolidatedFirstName[0:1]` slice in `nav.html.twig` is used to
  build the user's initials chip. If you see a Twig error here, wrap it:
  `{{ app.user.consolidatedFirstName|slice(0,1)|upper }}`
