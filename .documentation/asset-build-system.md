# Asset Build System

Directorist uses three separate webpack pipelines to handle different parts of the codebase. By default, all pipelines output compiled assets to the assets/build/ directory. The Gutenberg blocks pipeline is an exception and outputs compiled assets to its own directory.

---

## Pipelines

### 1. Legacy (`webpack.legacy.dev.js` / `webpack.legacy.prod.js`)

This is the legacy build system, responsible for handling Vue.js-based JavaScript and SCSS. The files used in this system are not compatible with the new build system; therefore, it is retained to ensure backward compatibility. Existing sources should be gradually migrated to the new (default) build system. Once all legacy sources have been successfully transitioned, this build system can be safely deprecated and removed.

**Features:**
- Babel transpilation with `@wordpress/default` preset
- Vue single-file component (`.vue`) support via `vue-loader`
- SCSS compilation with PostCSS autoprefixing
- RTL stylesheet generation via `@automattic/webpack-rtl-plugin`
- Source maps in development; disabled in production
- Builds zip file for production.

**Production Build:** 

`webpack.legacy.prod.js` extends the dev config and adds a `FileManagerPlugin` post-build step that:

1. Copies `assets/`, `blocks/`, `languages/`, `includes/`, `templates/`, `views/`, and all root `.php`/`.txt` files into `__build/directorist/directorist/`
2. Deletes the `assets/src` directory from the copy (strips source files from the release)
3. Archives the folder to `__build/directorist.zip`
4. Cleans up the intermediate `__build/directorist/` directory

---

### 2. Default (`webpack.config.js`)

This is the new and default build system for the project. It supports React, TypeScript, and modern SCSS, and extends the default webpack configuration provided by `@wordpress/scripts`.

**Features:**
- Inherits all `@wordpress/scripts` defaults (TypeScript, React JSX, asset file generation)
- Path alias `@` resolves to `assets/src/js/react/`

---

### 3. Blocks (`blocks/webpack.config.js`)

Used exclusively for Gutenberg blocks. Passes through `@wordpress/scripts` default config without modification.

**Source directories:**
- `blocks/src/` — block definitions (compiled to `blocks/build/`)
- `blocks/common/` — shared block assets (compiled to `blocks/assets/`)

---

## NPM Scripts

### Development (watch mode)

| Command | What it does |
| ------- | ------------ |
| `npm start` | Runs `start-legacy` and `start-default` concurrently — the standard dev command |
| `npm run start-legacy` | Watches legacy entry points via `webpack.legacy.dev.js` |
| `npm run start-default` | Watches default entry points via `wp-scripts start` (`webpack.config.js`) |
| `npm run start:blocks` | Watches `blocks/src/` → `blocks/build/` |
| `npm run start:blocks-common` | Watches `blocks/common/` → `blocks/assets/` |

> **Note:** `start:blocks` and `start:blocks-common` are not included in the default `npm start`. Run them separately only when working on Gutenberg blocks.

### Production builds

| Command | What it does |
| ------- | ------------ |
| `npm run build` | Full production build: runs `pot` → `build:blocks` → `build:blocks-common` → `build-default` → `build-legacy` |
| `npm run build-legacy` | Compiles legacy files and builds the plugin zip file into `__build/directorist.zip` |
| `npm run build-default` | Compiles the default entry points via `wp-scripts build` |
| `npm run build:blocks` | Compiles `blocks/src/` → `blocks/build/` |
| `npm run build:blocks-common` | Compiles `blocks/common/` → `blocks/assets/` |

### Other

| Command | What it does |
| ------- | ------------ |
| `npm run pot` | Generates the `.pot` translation file via `pot.js` |
| `npm run format` | Runs `wp-scripts format` over `assets/src/` |

---

## Assets File Structure

```
├── assets         
│   ├── build/            # Build directory for assets
│   ├── icons/            # Icons for assets
│   ├── images/           # Images for assets
│   ├── other/            # Miscellaneous files
│   ├── sample-data/      # Sample files
│   ├── src/              # Source directory for assets
│   ├── vendor-css/       # Vendor CSS files
│   └── vendor-js/       # Vendor JS files
│
├── blocks/               # Gutenberg Blocks
│
└── __build/                # Created only during `build-legacy`
    └─ directorist.zip    # Distributable plugin archive
```
