# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A WordPress plugin (`modularity-shape-divider`) that adds a "Shape Divider" module to the [Modularity](https://github.com/helsingborg-stad/modularity) module system. It renders an SVG (uploaded via ACF) as a decorative divider between page sections, with options to overflow into neighboring modules, flip/mirror, recolor, and remove margins/height.

The plugin only runs inside a Municipio/Modularity WordPress site — it depends on globals and hooks from those systems (`\Modularity\Module`, `Municipio/blade/view_paths`, `Modularity/Display/*` filters) that don't exist standalone.

## Build commands

```sh
npm install && npm run build     # production build (webpack, minified, hashed filenames)
npm run build:dev                # development build (unminified)
npm run watch                    # webpack --watch for local development
composer install                 # PHP autoloader / dependencies
```

There is no test suite and no lint script configured (`npm test` is a placeholder that exits with an error).

`php build.php` (CLI only) is the CI/deploy entrypoint: it runs `npm install`, updates browserslist, `npm run build`, and `composer install --prefer-dist`. Passing `--cleanup` afterwards deletes dev-only files (`.git`, `node_modules`, `composer.json`, `webpack.config.js`, etc.) to produce a production-ready plugin folder — don't run `--cleanup` against a working checkout.

Assets are built from `source/js` and `source/sass` into `dist/`, with webpack's `WebpackManifestPlugin` writing `dist/manifest.json`. PHP reads that manifest at runtime via `CacheBust::name()` (`source/php/Helper/CacheBust.php`) to enqueue the correctly hashed `dist/css/*.css` / `dist/js/*.js` file — so PHP-side asset URLs always go through `CacheBust`, never a hardcoded filename. If `dist/manifest.json` is missing, `CacheBust` echoes a `WP_DEBUG`-only warning telling the developer to build assets.

## Architecture

**Bootstrap (`modularity-shape-divider.php`)**: defines path/URL constants (`MODULARITY_SHAPE_DIVIDER_*`), registers a custom PSR-4 autoloader (`source/php/Vendor/Psr4ClassLoader.php`) for the `ModularityShapeDivider` namespace, wires up ACF field auto-export/import via `AcfExportManager`, registers the module's Blade view path for Modularity 3.0's component library, and instantiates `ModularityShapeDivider\App`.

**`App.php`** is the composition root:
- Instantiates `Admin\Settings` (currently inert — the settings page registration is commented out).
- Hooks `registerModule()` on WP `init`, which calls the global `modularity_register_module()` (provided by the Modularity plugin/theme) pointing at `MODULARITY_SHAPE_DIVIDER_MODULE_PATH` with class name `ShapeDivider`.
- Filters `Municipio/blade/view_paths` to inject this plugin's Blade view directory (before child-theme views if a child theme is active, otherwise first in the list).
- Adds `aria-hidden="true"` to the module's wrapper markup via the `Modularity/Display/BeforeModule` filter, since the divider is purely decorative.

**`source/php/Module/ShapeDivider.php`** extends `\Modularity\Module` and is the actual module implementation, following Modularity's module lifecycle methods: `init()` (labels), `data()` (build view data), `template()` (Blade template name), `style()`/`script()` (conditional asset enqueue via `CacheBust`). Key behavior in `data()`:
- Pulls ACF field values via `get_fields()`, camelCased by `\Modularity\Helper\FormatObject::camelCase`.
- Loads the uploaded SVG file's raw contents from disk.
- If `replaceSvgColors` is set, rewrites `color`/`fill`/`stroke` attributes in the raw SVG markup via regex (`replaceSvgColors()`) — either to `currentColor` or a custom hex value — so the SVG can be recolored via CSS.
- If any of `EXTRA_SETTINGS` (`noTopMargin`, `noBottomMargin`, `noHeight`, `flipVertically`, `flipHorizontally`) is truthy, registers a `Modularity/Display/BeforeModule::classes` filter scoped to this module instance's `$ID` to append the corresponding CSS class (`no-top-margin`, `overlap-up`/`overlap-down`, `flip-horizontally`, etc.).

**ACF fields** (`source/php/AcfFields/php/shape-divider-module.php`, JSON mirror in `AcfFields/json/`) define the module's editor UI: SVG file upload, color select (theme colors or custom), replace-embedded-colors toggle, and the extra-settings tab (margins, height, flip, overlap direction). The PHP file is the source of truth; `AcfExportManager` (configured in `modularity-shape-divider.php`) keeps the JSON in sync and re-imports on load — when changing fields, prefer editing via the WP admin ACF UI (which regenerates both files) over hand-editing the exported PHP/JSON.

**Rendering**: `source/php/Module/views/shape-divider.blade.php` — a minimal Blade template that outputs the wrapper div with the raw SVG code, plus an inline `<style>` block that sets `color: var(--color-{{ $color }})` when a theme color (not `none`/`custom`) is selected. Blade rendering itself is handled by `Public.php`'s `shape_divider_render_blade_view()` helper (via `ComponentLibrary\Init`), though the module's own `template()`/data flow through Modularity's own Blade pipeline (`Municipio/blade/view_paths`) rather than calling this helper directly.

**Styling**: `source/sass/modularity-shape-divider.scss` handles the full-bleed overflow layout (the divider breaks out of the content container width using `calc()` against `--scrollbar`/`--container-width` CSS custom variables defined by the parent theme), plus the flip/margin/overlap modifier classes toggled from PHP.

**JS**: `source/js/modularity-shape-divider.js` exists as a webpack entry point but is currently empty.

## Key conventions

- PHP namespace root `ModularityShapeDivider` maps to both the plugin root and `source/php/` (see the two `addPrefix` calls in `modularity-shape-divider.php`) — so e.g. `ModularityShapeDivider\Module\ShapeDivider` resolves to `source/php/Module/ShapeDivider.php`.
- Never hardcode built asset filenames in PHP — always resolve through `CacheBust::name()` against `dist/manifest.json`.
- The module only registers (`registerModule()`) if the host site has Modularity active (`function_exists('modularity_register_module')`), and ACF fields only register if ACF is active (`function_exists('acf_add_local_field_group')`) — guard any new integration points the same way.
