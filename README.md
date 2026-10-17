# Basecoat

<img src="screenshot.png" alt="Basecoat screenshot" width="100%" />

[![WordPress requires](https://img.shields.io/badge/WordPress%20requires-6.8%2B-21759b)](https://github.com/quentin-ld/basecoat)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-8892BF)](https://www.php.net/)
[![Version](https://img.shields.io/badge/version-2.0.1-2E77BC)](https://github.com/quentin-ld/basecoat)
[![License](https://img.shields.io/badge/license-GPL--2.0--or--later-brightgreen)](https://www.gnu.org/licenses/gpl-2.0.html)

Basecoat is a starter, not a theme you ship. It is a complete standalone block
theme — `theme.json`, templates, parts, the editor styles — built so a new
project begins from one consistent set of choices instead of a blank slate.

It is for building block themes on. The conventions it carries are the ones the
other themes here follow, which is why it is the reference they are read against.

## Compatibility

### Requirements

<!-- harness:compat:start -->
- WordPress **6.8+** (tested up to **6.8**)
- PHP **8.1+**
- Version **2.0.1**
<!-- harness:compat:end -->

## Installation

Basecoat is a starting point, so it is used once per project rather than
installed from a directory:

1. Copy this directory into `wp-content/themes/` under the name the new site
   will use, and edit the `Theme Name:`, `Text Domain:` and version headers in
   `style.css`. Rename the text domain's `.pot` in `languages/` to match.
2. Run `composer install`, `npm install` and `bin/harness setup` — see the
   Development section below.
3. Activate it.

To try it as it stands, copy the directory in and activate it: it needs no
parent and nothing else installed.

<!-- harness:start -->
## Development

### Requirements

- PHP **8.1+** · WordPress **6.8+** · Composer · Node.js LTS + npm
- Docker. Every command runs through `bin/harness`, which resolves the backend
  for you — DDEV first, then LocalWP, then the host. Nothing needs installing.

### Setup

```bash
composer install
npm install
bin/harness setup   # one-time: dependencies and this project's toolchain
```

### Key commands

| Command | What it does |
|---------|--------------|
| `npm run test:all` | every linter, and the suites the project has |
| `npm run build:all` | `test:all`, then the `.pot`, then the bundle |
| `composer run verify:php` | the pre-push gate: standards and static analysis |
| `composer run verify:all` | the same, plus the integration suite where the project has one |
| `composer run lint:wpcs` / `npm run lint` / `npm run lint:css` | WPCS, ESLint, Stylelint — the `:fix` variants rewrite |
| `composer run lint:pcp` | Plugin Check, where the project ships through WordPress.org |
| `composer run make:pot` | regenerate `languages/basecoat.pot` |
| `npm run zip` | the distributable archive |

**Tests.** This project is a theme, and the harness carries no test layer for themes: `test`, `integration`, `coverage`, `mutation`, `counterfactual`, `test:js`, and `e2e` each print one sentence and exit 0. A `tests/` directory the project already has stays in its repository and is not run; lint, the build and the hooks are unchanged.

Assets: `npm start` to watch, `npm run build` for a one-shot bundle.

Anything without a script above goes through the runtime directly:

```bash
bin/harness doctor          # backend, tools, graft, manifest — run this first
bin/harness help            # every command
```

**Never call `php`, `composer`, `node`, `npm` or `wp` directly.** The backend is
resolved per project, and a direct call fails in a way that looks like a broken
project rather than a missing environment.
<!-- harness:end -->

## Support & Contribution

Basecoat is developed in this repository. Read `AGENTS.md` first — it carries
the project facts, the conventions and the gates, and it is where the
block-theme shape is written down for the themes built from this one.
