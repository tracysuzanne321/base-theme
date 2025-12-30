# Base Theme

WordPress theme with SCSS support.

## Setup

1. Install dependencies:
```bash
npm install
```

## Development

### Compile SCSS once:
```bash
npm run sass
```

### Watch for changes (auto-compile):
```bash
npm run sass:watch
```

### Compile for production (minified):
```bash
npm run sass:prod
```

## SCSS Structure

- `scss/style.scss` - Main SCSS file (compiles to `style.css`)
- `scss/_variables.scss` - Variables (colors, typography, breakpoints, etc.)
- `scss/_mixins.scss` - Reusable mixins
- `scss/_base.scss` - Base/reset styles
- `scss/_layout.scss` - Layout styles
- `scss/_components.scss` - Reusable component styles
- `scss/_blocks.scss` - Block-specific styles

## Notes

- The compiled `style.css` file includes the WordPress theme header comment
- Source maps are generated during development for easier debugging
- Production builds are minified and don't include source maps

# base-theme
