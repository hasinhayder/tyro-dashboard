# Dashboard UI

## Core Principle

The UI is what 100% of users see. Inconsistent theming, broken dark mode, or misbehaving notifications degrades trust across every Tyro Dashboard application. CSS custom property names are part of the public API.

## CSS Custom Property System

### Design Token Architecture
All visual design is expressed through CSS custom properties defined in `shadcn-theme.blade.php`:

- Colors: `--background`, `--foreground`, `--card`, `--popover`, `--primary`, `--secondary`, `--muted`, `--accent`, `--destructive`, `--border`, `--input`, `--ring`
- Semantic: `--success`, `--warning`, `--info`, `--danger`
- Layout: `--radius`, `--card-shadow`

Each color has a `-foreground` variant for text contrast.

### Hardcoded Colors Are Forbidden
Never use a hex value directly in a component style. Always reference a CSS custom property. This is what makes the Dashboard Colors settings tab work. A hardcoded `#09090b` in a component bypasses the entire theming system.

### Custom Property Naming
- Names are part of the public API. Consumer applications and plugins may reference them in custom styles.
- Renaming or removing a custom property breaks consumer-published styles.
- Adding new custom properties is safe.
- Naming convention: `--{category}[-{variant}][-{state}]` in kebab-case.

## Light/Dark Theming

### Implementation
- `<html class="light">` is the default on page load
- `.dark` class on `<html>` overrides all custom properties for dark mode
- Theme toggle button in the topbar calls `toggleTheme()`
- State stored in `localStorage('tyro-dashboard-theme')`
- System preference (`prefers-color-scheme`) is the fallback when no user preference is stored
- OS-level changes are listened to only when no explicit user preference exists

### Dark Mode Values
Every light-mode custom property has a dark-mode override under `.dark { ... }`. Both must be defined. A property that exists in light mode but not dark mode will render incorrectly in dark mode.

## Sidebar

### Color Override
Sidebar config colors from `config('tyro-dashboard.branding.*')` use `!important` in `styles.blade.php` to override theme variables specifically for the sidebar:
- `--sidebar` from `sidebar_bg`
- `--sidebar-foreground` from `sidebar_text`
- `--sidebar-primary` from `sidebar_primary`
- `--sidebar-accent` from `sidebar_accent`
- `--sidebar-border` from `sidebar_header_border`

### Behavior
- Collapsible sidebar: toggled via config `collapsible_sidebar`, collapses to 25px with expand button
- Accordion sections: via `sidebar_accordion_compact`, section titles expand/collapse on click
- Accordion open count: `sidebar_accordion_open_sections` controls how many sections are open by default
- Mobile: sidebar slides in/out with overlay, hamburger button in topbar
- Sidebar logo: separate from main app logo, set via system settings

## Admin Bar

- Fixed-position bar at the top of the page
- Pushes entire layout down via `--admin-bar-height` CSS variable
- Config controls: `enabled`, `message`, `bg_color`, `text_color`, `align` (left/center/right), `height`
- Config-driven bar is persistent (from `.env`)
- Programmatic bar via `AdminNotice::show()` is runtime-only
- Both mechanisms must not conflict — programmatic takes precedence over config

## Notifications

### Two Modes
- **Legacy:** Inline `alert-{type}` divs with auto-dismiss. Simple, no JavaScript dependency beyond the auto-dismiss timer.
- **Toast:** Fixed-position container with slide-in animation, close buttons, auto-dismiss. Configurable position (`top-right` or `bottom-right`).

### Mode Configuration
- `config('tyro-dashboard.notifications.notification_style')` — `'legacy'` or `'toast'`
- Legacy mode exists for backward compatibility. New features should target toast mode.
- A notification sent in one mode must not leak into the other mode's display area.

### Flash Message Keys
- `success`, `error`, `warning`, `info` — set via `->with('success', '...')` in controllers
- `flash-messages.blade.php` consumes session flash and renders in the configured style
- Auto-dismiss respects `config('tyro-dashboard.notifications.auto_dismiss_seconds')`

## Global Modal

- JS-driven modal: `#globalModal`
- Variants: `confirm`, `alert`, `prompt`, `danger`, `info`, `success`
- Initialized once on page load, shown as needed
- Never create page-specific modals when the global modal can handle the interaction
- The modal must be keyboard-accessible (Escape to close, focus trap)

## Theme Toggle

- Sun/moon icon button in the topbar
- Calls `toggleTheme()` which flips `light`/`dark` class and updates localStorage
- Icon updates immediately to reflect current theme
- No page reload required

## Font

- Inter font family via `fonts.bunny.net` CDN
- System font fallback stack
- Font loading is in the `<head>` of each layout
- CDN URL is configurable for consumers who want to self-host

## JavaScript Client-Side System

The `scripts.blade.php` partial contains ~460 lines of vanilla JavaScript managing the entire client-side UI.

### Theme Management
- `toggleTheme()` — flips `light`/`dark` class on `<html>`, persists to `localStorage('tyro-dashboard-theme')`
- System preference detection via `prefers-color-scheme` media query as fallback
- OS-level changes are listened to only when no explicit user preference is stored

### Sidebar
- Collapse/expand with localStorage persistence of collapsed state
- Accordion sections with compact mode (`sidebar_accordion_compact`)
- Open sections count controlled by `sidebar_accordion_open_sections` config
- Mobile: hamburger toggle with overlay

### Global Modal
- `showModal(options)` — generic modal with title, message, type, confirm/cancel callbacks
- Variants: `showConfirm()`, `showAlert()`, `showSuccess()`, `showDanger()`, `showInfo()`, `showPrompt()`
- `confirmDelete(url, message)` — convenience wrapper for destructive confirmations
- Escape key and overlay click dismissal
- Focus trap within modal

### Toast Notifications
- `showToast(message, type, duration)` — creates a toast notification element
- `dismissToast(element)` — removes a toast with animation
- Types: `success`, `error`, `warning`, `info`
- Auto-dismiss respects `config('tyro-dashboard.notifications.auto_dismiss_seconds')`
- Legacy alert auto-dismiss (for backward-compatible notification mode)

### Vertical Tabs
- Tab switching with localStorage persistence
- Used in settings page sidebar navigation

### User Dropdown
- Click-toggle dropdown in topbar
- Click-outside-to-close behavior
