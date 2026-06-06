# User Experience

## Core Principle

Tyro Dashboard is operational software. Users come here to scan, decide, configure, and recover quickly. UI work must improve clarity, confidence, and speed without turning admin pages into marketing pages or one-off visual experiments.

## Admin UX Priorities

Design admin surfaces in this order:

1. Make the current state obvious.
2. Make the primary next action easy to find.
3. Make risky actions deliberate and reversible where possible.
4. Make errors actionable.
5. Keep repeated workflows fast.

Do not optimize for novelty over predictability. A user who knows one Tyro Dashboard page should be able to understand the next one.

## Information Architecture

- Put high-frequency controls near the data or state they affect.
- Group related controls by workflow, not by implementation detail.
- Keep page titles, breadcrumbs, sidebar labels, and route purpose aligned.
- Avoid duplicate navigation paths unless there is a clear user role or workflow reason.
- Do not hide required context in hover-only UI; hover can enhance, but not carry critical meaning.
- Keep destructive or irreversible actions visually and spatially distinct from routine actions.

## Layout And Density

- Admin pages should be dense enough for scanning, but not cramped.
- Prefer tables, definition lists, compact forms, and clear panels for operational data.
- Use cards for actual panels, repeated items, or bounded tools; avoid cards inside cards.
- Do not build landing-page-style hero sections for admin tasks.
- Keep headings proportional to their container. Hero-scale text does not belong inside compact admin panels.
- Long values such as emails, URLs, file paths, tokens, IDs, and class names must wrap or truncate intentionally without breaking layout.

## Actions And Controls

- Use a single primary action per view or form section when possible.
- Use buttons for commands, links for navigation, toggles for binary state, selects for option sets, and checkboxes for independent choices.
- Icon-only buttons need an accessible label and should have a tooltip when the icon is not universally obvious.
- Prefer the existing global modal for confirmations instead of page-specific modal implementations.
- Confirmation copy must name the object and consequence, especially for destructive actions.
- Disabled actions should explain why when the reason is not obvious.

## States And Feedback

Every interactive admin feature should account for:

- Empty state: explain what is missing and offer the relevant next action.
- Loading state: preserve layout dimensions where possible to avoid jarring shifts.
- Success state: confirm what changed, using existing flash/toast patterns.
- Error state: say what failed and what the user can do next.
- Permission state: do not show unusable controls when authorization rules say the user cannot act.

For background work, prefer visible status and refresh affordances over silent uncertainty. If a task may take time, make that expectation clear.

## Forms

- Match validation rules between server behavior and UI hints.
- Mark required fields clearly.
- Keep labels visible; placeholders are not labels.
- Preserve user-entered values after validation errors.
- Place field errors next to the field they describe.
- Use help text for constraints that prevent avoidable errors, such as allowed formats, size limits, or external requirements.
- For secret values, never reveal stored secrets. Use masked status, replacement fields, or explicit "set new value" flows.

## Tables And Lists

- Default sorting should match the most likely admin intent.
- Provide search, filters, or pagination before lists become difficult to scan.
- Keep row actions consistent across resources.
- Avoid expensive per-row queries or checks during render.
- Show meaningful empty and filtered-empty messages.
- For bulk actions, require a clear selected-count state and confirmation for destructive operations.

## Responsive Behavior

- Admin pages must remain usable on mobile, even when the desktop workflow is primary.
- Tables should either scroll horizontally within a stable container or collapse into a readable mobile pattern.
- Sticky headers, sidebars, and action bars must not cover form fields, flash messages, or modal content.
- Touch targets must be large enough to use without precision pointing.
- Test long labels and generated values on narrow screens before considering a UI complete.

## Accessibility

- Preserve semantic HTML before adding JavaScript behavior.
- Keep keyboard access for navigation, forms, dropdowns, modals, tabs, and destructive confirmations.
- Maintain visible focus states.
- Use color plus text, icon, or shape for status; color alone is not enough.
- Ensure text and icon contrast works in both light and dark themes.
- Respect reduced-motion preferences for animations that are not essential.

## Visual System

- Use existing Tyro Dashboard CSS variables, component classes, spacing, border radius, and notification patterns.
- New UI should work in light mode, dark mode, and configured brand colors.
- Do not hardcode colors; read `rules/dashboard-ui.md`.
- Do not introduce a separate design system, icon set, utility framework, or JavaScript UI library for a narrow feature.
- Keep icon style consistent with surrounding Tyro Dashboard UI.

## Upgrade Safety

User experience improvements in the framework are still public-surface changes when they affect layouts, sections, class names, data attributes, JavaScript globals, publishable views, or component markup. Preserve extension points and published override compatibility.

When UX changes alter behavior that consumers may rely on, prefer additive markup, optional config, or a documented migration path.

## UX Review Checklist

Before finishing UI work, check:

- Does the page make current state and next action obvious?
- Are empty, loading, success, error, and unauthorized states covered?
- Does it work with long content and on mobile?
- Does it work in light mode, dark mode, and custom theme colors?
- Are destructive actions clearly separated and confirmed?
- Is the keyboard path intact for menus, forms, tabs, and modals?
- Did the change reuse existing Tyro Dashboard patterns instead of creating a second pattern?
