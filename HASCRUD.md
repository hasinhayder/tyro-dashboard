# HasCrud Trait Documentation

The `HasCrud` trait provides a simple way to add CRUD functionality to your Eloquent models without manually configuring resources in the `tyro-dashboard.php` config file.

## Features

- Automatically discover models with CRUD capabilities
- Define resource fields directly in your model
- Configure role-based access control per model
- Works alongside traditional config-based resources for backward compatibility

## Basic Usage

### 1. Add the Trait to Your Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasinHayder\TyroDashboard\Concerns\HasCrud;

class Book extends Model
{
    use HasCrud;
    
    // Define your resource fields
    protected $resourceFields = [
        'title' => [
            'type' => 'text', 
            'label' => 'Book Title', 
            'rules' => 'required|max:255|unique:books,title',
            'help_text' => 'Enter a unique title for the book',
            'searchable' => true, 
            'sortable' => true
        ],
        'isbn' => [
            'type' => 'text', 
            'label' => 'ISBN', 
            'rules' => 'required|max:20|unique:books,isbn',
            'help_text' => 'International Standard Book Number',
            'searchable' => true
        ],
        'description' => [
            'type' => 'textarea', 
            'label' => 'Description', 
            'hide_in_index' => true
        ],
        'price' => [
            'type' => 'number', 
            'label' => 'Price'
        ],
        'published_date' => [
            'type' => 'date', 
            'label' => 'Published Date'
        ],
        'pages' => [
            'type' => 'number', 
            'label' => 'Number of Pages'
        ],
        'publisher' => [
            'type' => 'text', 
            'label' => 'Publisher', 
            'rules' => 'nullable|max:255'
        ],
        'is_active' => [
            'type' => 'boolean', 
            'label' => 'Active'
        ],
        // BelongsToMany relationship
        'authors' => [
            'type' => 'select', 
            'label' => 'Authors', 
            'relationship' => 'authors', 
            'option_label' => 'name', 
            'multiple' => true, 
            'hide_in_index' => true
        ],
    ];
    
    // Define the relationship
    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
}
```

### 2. Customize Resource Titles (Optional)

By default, the trait will generate resource titles from the model class name. You can customize them:

```php
class Book extends Model
{
    use HasCrud;
    
    protected $resourceTitle = 'Books';
    protected $resourceTitleSingular = 'Book';
    protected $resourceFields = [...];
}
```

### 3. Customize Resource Key (Optional)

By default, the resource key is the plural snake_case of the model name. You can override it:

```php
class Book extends Model
{
    use HasCrud;
    
    protected $resourceKey = 'my_books'; // Default would be 'books'
    protected $resourceFields = [...];
}
```

## Role-Based Access Control

### Full Access Roles

Define which roles have full CRUD access:

```php
class Book extends Model
{
    use HasCrud;
    
    protected $resourceRoles = ['admin', 'editor'];
    protected $resourceFields = [...];
}
```

### Readonly Access Roles

Define which roles have readonly (view only) access:

```php
class Book extends Model
{
    use HasCrud;
    
    protected $resourceRoles = ['admin', 'editor']; // Full access
    protected $resourceReadonly = ['viewer']; // Readonly access
    protected $resourceFields = [...];
}
```

### Public Access

If no roles are defined, the resource is accessible to all authenticated users:

```php
class Book extends Model
{
    use HasCrud;
    
    // No roles defined = accessible to all authenticated users
    protected $resourceFields = [...];
}
```

## Auto-Generation from $fillable

If you don't define `$resourceFields`, the system will automatically generate field configurations based on:
1. Field names in your `$fillable` array
2. Database schema inspection (types, constraints, enum values)
3. Detected model relationships

```php
class Book extends Model
{
    use HasCrud;
    
    protected $fillable = [
        'title',
        'isbn',
        'description',
        'price',
        'published_date',
        'is_active',
    ];
    
    // No $resourceFields defined - will auto-generate!
    // The system will:
    // - Detect field types from database
    // - Add proper validation rules
    // - Find relationships (books, authors, etc.)
    // - Make searchable fields like 'title', 'name'
}
```

## Customizing Auto-Generated Fields

Want to customize just a few fields while keeping auto-generation for the rest? Use `$resourceFieldOverrides`:

```php
class Book extends Model
{
    use HasCrud;
    
    protected $fillable = [
        'title',
        'isbn', 
        'description',
        'price',
        'is_active',
    ];
    
    // Override specific fields while auto-generating the rest
    protected $resourceFieldOverrides = [
        'isbn' => [
            'label' => 'ISBN Code',
            'help_text' => 'International Standard Book Number (13 digits)',
        ],
        'description' => [
            'hide_in_index' => true, // Hide from list view
        ],
        'price' => [
            'label' => 'Price (USD)',
            'help_text' => 'Enter price in US dollars',
        ],
    ];
    
    // All other fields (title, is_active) are auto-generated
}

## Complete Example with Many-to-Many Relationship

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasinHayder\TyroDashboard\Concerns\HasCrud;

class Author extends Model
{
    use HasCrud;
    
    protected $resourceTitle = 'Authors';
    protected $resourceTitleSingular = 'Author';
    
    protected $resourceRoles = ['admin'];
    protected $resourceReadonly = ['editor'];
    
    protected $resourceFields = [
        'name' => [
            'type' => 'text', 
            'label' => 'Author Name', 
            'rules' => 'required|max:255|unique:authors,name', 
            'searchable' => true, 
            'sortable' => true
        ],
        'email' => [
            'type' => 'email', 
            'label' => 'Email', 
            'rules' => 'required|email|unique:authors,email', 
            'searchable' => true
        ],
        'bio' => [
            'type' => 'textarea', 
            'label' => 'Biography', 
            'hide_in_index' => true
        ],
        'birth_date' => [
            'type' => 'date', 
            'label' => 'Date of Birth'
        ],
        'country' => [
            'type' => 'text', 
            'label' => 'Country', 
            'rules' => 'nullable|max:100'
        ],
        'is_active' => [
            'type' => 'boolean', 
            'label' => 'Active'
        ],
        'books' => [
            'type' => 'select', 
            'label' => 'Books', 
            'relationship' => 'books', 
            'option_label' => 'title', 
            'multiple' => true, 
            'hide_in_index' => true
        ],
    ];
    
    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
```

## Supported Field Types

All field types from the config-based system are supported:

- `text` - Single line text input
- `email` - Email input
- `password` - Password input
- `textarea` - Multi-line text input
- `richtext` - Rich text editor
- `number` - Number input
- `date` - Date picker
- `datetime-local` - Date and time picker
- `time` - Time picker
- `boolean` - Checkbox
- `file` - File upload
- `select` - Dropdown (use `multiple => true` for many-to-many)
- `multiselect` - Multiple selection dropdown
- `radio` - Radio buttons
- `checkbox` - Checkbox group

## Field Options

- `label` - Display label for the field
- `type` - Field type (required)
- `rules` - Laravel validation rules
- `help_text` - Help text displayed below the field (optional)
- `searchable` - Make field searchable (default: false)
- `sortable` - Make field sortable (default: false)
- `hide_in_index` - Hide field in list view (default: false)
- `relationship` - Name of the Eloquent relationship method
- `option_label` - Attribute to display for relationship options (default: 'name')
- `multiple` - For select fields, enable multiple selection (many-to-many)
- `options` - Array of options for select/radio/checkbox fields

## Backward Compatibility

The trait-based system works alongside the traditional config-based system:

1. **Config-based resources** (defined in `config/tyro-dashboard.php`) take precedence
2. **Trait-based resources** are automatically discovered and added
3. Both types appear together in the dashboard sidebar

This means you can migrate gradually from config-based to trait-based resources without breaking existing functionality.

## How It Works

1. The `HasCrud` trait adds methods to your model to expose resource configuration
2. The service provider automatically scans the `app/Models` directory for models using the trait
3. Resources are registered and made available to the dashboard
4. The resource controller handles both config-based and trait-based resources seamlessly

## Benefits Over Config-Based Resources

1. **Co-location** - Resource configuration lives with the model
2. **Less boilerplate** - No need to duplicate model class names in config
3. **IDE support** - Better autocomplete and refactoring support
4. **Scalability** - Easier to manage many resources
5. **Organization** - Each model owns its CRUD configuration
