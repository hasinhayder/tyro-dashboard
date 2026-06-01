# CRUD Resources

## Core Principle

The dynamic CRUD system is the primary reason developers choose Tyro Dashboard over competitors. It must work correctly for the simplest model (3 fields, no relationships) and the most complex (50 fields, 8 relationships, MySQL enums). Every edge case is someone's application.

## Resource Definition

### Two Definition Methods
- **Trait-based:** Add `HasCrud` to an Eloquent model. The framework auto-discovers fields from `$fillable`, database schema, and relationships.
- **Config-based:** Define the resource in `config('tyro-dashboard.resources')` as an array with `model`, `title`, `icon`, `fields`, `roles`, `readonly`.
- **Both must work independently.** A resource defined both ways should resolve consistently.
- **Trait-based is preferred** for model-owned resources (e.g., `Post`, `Product`). Config-based is for cross-cutting resources that don't correspond to a single model.

### Resource Resolution
- `ResourceController::getResourceConfig($resourceKey)` first checks config array, then scans models for `HasCrud`
- The scanner uses reflection to find classes with `getResourceConfig()` and `getResourceKey()` methods
- Resolution is cached — do not re-scan on every request
- `getResourceKey()` defaults to `Str::plural(Str::snake(class_basename($model)))`

## Field Auto-Detection

### Detection Pipeline (Fixed Order)
1. **Database enum** — If the column is a MySQL enum, it becomes a `select` field with enum values as options
2. **Name pattern** — `*_id` → select, `email` → email, `password` → password, `is_*`/`has_*` → boolean, `description`/`content`/`body`/`notes` → textarea, `price`/`amount`/`cost` → number, `image`/`photo`/`file` → file
3. **DB column type** — `boolean` → boolean, `integer`/`bigint` → number, `decimal`/`float` → number, `text`/`longtext` → textarea, `date` → date, `datetime` → datetime-local
4. **Default** — `text`

### Name Pattern Rules
- Patterns are evaluated from most specific to least specific
- Adding a new pattern is safe (additive change)
- Changing an existing pattern behavior is breaking — models depending on that pattern will change behavior
- Patterns match against the column name, not the field label

### Relationship Detection
- Uses reflection to scan public methods on the model
- `BelongsTo` → select field with relationship configuration
- `BelongsToMany` → multiselect field, `hide_in_index: true`
- `HasMany` / `HasOne` → select field, `hide_in_index: true`
- Related model's display attribute is configurable via `option_label`

### Nullable Detection
- Database column's `nullable` → field is not required
- `NOT NULL` without a default → field is required
- Consumer can override with explicit `rules` in field config

## Field Configuration

### Explicit Fields (`$resourceFields`)
Defines the complete field list. Replaces auto-detection entirely. Every field must be defined.

### Field Overrides (`$resourceFieldOverrides`)
Merges with auto-detected fields. Only specified fields are changed. Unspecified fields keep auto-detected values.

### Field Config Shape
```php
'field_name' => [
    'type' => 'text',           // input type
    'label' => 'Field Label',   // display label
    'rules' => 'required|...',  // validation rules
    'options' => [...],         // for select/radio
    'relationship' => '...',    // relationship method name
    'option_label' => '...',    // related model display column
    'multiple' => true,         // for multi-select
    'help_text' => '...',       // help text below field
    'placeholder' => '...',     // input placeholder
    'hide_in_index' => true,    // hide from table
    'hide_in_form' => true,     // hide from create/edit
    'hide_in_create' => true,   // hide from create only
    'hide_in_edit' => true,     // hide from edit only
    'hide_in_single_view' => true, // hide from detail view
    'searchable' => true,       // include in search
    'sortable' => true,         // allow sorting
    'readonly' => true,         // read-only input
    'attributes' => [...],      // HTML attributes
]
```

### Supported Field Types
`text`, `textarea`, `select`, `multiselect`, `checkbox`, `radio`, `file`, `boolean`, `richtext`, `markdown`, `hidden`, `email`, `password`, `url`, `number`, `date`, `datetime-local`, `time`

## Form Rendering

### Create/Edit Views
- Single dynamic loop over `$config['fields']`
- Each field type has a conditional rendering block
- `hide_in_form`, `hide_in_create`, `hide_in_edit` flags suppress fields
- `help_text` renders below the field
- File fields show preview of existing file in edit mode
- Password fields show "Leave blank to keep current" in edit mode
- Boolean fields render as single checkbox (unchecked = false)

### Rich Text & Markdown
- `richtext` → Quill.js editor with hidden textarea
- `markdown` → EasyMDE editor
- JS initialization loops run after page load for all richtext/markdown fields

## Table Rendering

### Index View
- Search bar queries all `searchable` fields with `LIKE %term%`
- Column visibility filter persisted in localStorage per resource
- Cells render differently by type:
  - `file` → "View" link
  - `boolean` → Yes/No badge
  - `richtext`/`markdown` → stripped 50-char preview
  - Default → 50-char limit
- Row click navigates to show view
- Sort via `sort_by`/`sort_dir` query params on `sortable` fields
- Pagination via standard Laravel pagination

### Show View
- Responsive grid of label/value pairs
- `richtext` → Purifier-sanitized HTML
- `markdown` → client-side rendered with marked.js + DOMPurify
- `file` → clickable link
- `hide_in_single_view` flag suppresses fields

## Data Handling

### Store/Update
- Validation rules are auto-generated from field `rules`, DB column types, and nullable constraints
- Boolean checkboxes: missing from request = `false`
- Many-to-many fields are separated from model attributes before `$model->save()`
- After save, `$model->relationship()->sync($m2mFields)` runs
- File uploads use `StoreResourceMedia` trait if available, or manual storage

### Destroy
- Checks `auto_delete_on_resource_delete` config
- If enabled, deletes uploaded files from storage

### DB Error Parsing
- MySQL: `SQLSTATE[23000]` patterns
- SQLite: `NOT NULL constraint failed` patterns
- PostgreSQL: `violates not-null constraint` patterns
- Unrecognized errors → generic message (never raw SQL)

## Field Caching
- Cache key: `tyro_dashboard_fields_{md5(modelClass)}_{fillableHash}`
- Hash tracking key: `tyro_dashboard_hash_{md5(modelClass)}` — stores current fillable hash
- TTL: 6 hours (21600 seconds)
- Invalidation: `tyro-dashboard:clear-cache` command, `HasCrud::clearFieldCache()`, or automatic on fillable change
- The cache stores the complete generated field config array

## Resource Access Control
- `$resourceRoles` — role slugs with full CRUD access
- `$resourceReadonly` — role slugs with view-only access
- Empty both = admin-only (secure by default)
- Access is checked in `ResourceController::hasAccess()` and `isReadonly()`
- Sidebar resources are filtered by `TyroDashboardServiceProvider::filterResourcesByUserRole()`
