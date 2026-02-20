import re

with open('new-design/doc.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the nav section
nav_old = """                <div class="doc-nav-section">
                    <div class="doc-nav-title">Resource Configuration</div>
                    <ul class="doc-nav-list">
                        <li><a href="#search-config" class="doc-nav-link">Search Settings</a></li>
                        <li><a href="#field-types" class="doc-nav-link">Field Types</a></li>
                        <li><a href="#rich-text" class="doc-nav-link">Rich Text Editor</a></li>
                        <li><a href="#markdown-editor" class="doc-nav-link">Markdown Editor</a></li>
                        <li><a href="#file-upload-preview" class="doc-nav-link">File Upload & Preview</a></li>
                        <li><a href="#field-visibility" class="doc-nav-link">Field Visibility & Control</a></li>
                        <li><a href="#field-options-reference" class="doc-nav-link">Field Options Reference</a></li>
                        <li><a href="#readonly-resources" class="doc-nav-link">Readonly Resources</a></li>
                        <li><a href="#error-visibility" class="doc-nav-link">Error Visibility</a></li>
                    </ul>
                </div>"""

nav_new = """                <div class="doc-nav-section">
                    <div class="doc-nav-title">Resource Configuration</div>
                    <ul class="doc-nav-list">
                        <li><a href="#the-power-of-hascrud" class="doc-nav-link">The Power of HasCrud</a></li>
                        <li><a href="#model-overrides" class="doc-nav-link">Model Overrides</a></li>
                        <li><a href="#field-types-modifiers" class="doc-nav-link">Field Types & Modifiers</a></li>
                        <li><a href="#mastering-relationships" class="doc-nav-link">Mastering Relationships</a></li>
                        <li><a href="#file-image-modifiers" class="doc-nav-link">File & Image Modifiers</a></li>
                    </ul>
                </div>"""

if nav_old in content:
    content = content.replace(nav_old, nav_new)
else:
    print("WARNING: nav_old not found!")

# Find the start and end of the resource-configuration section
start_marker = '            <!-- Resource Configuration -->'
end_marker = '            </section>'
start_idx = content.find(start_marker)

if start_idx != -1:
    end_idx = content.find(end_marker, start_idx) + len(end_marker)

    new_section = """            <!-- Resource Configuration -->
            <section id="resource-configuration" class="doc-section">
                <h2 class="doc-section-title">Resource CRUD Configuration</h2>
                <p class="doc-text">
                    Tyro Dashboard comes with a powerful and dynamic CRUD generator that allows you to quickly build administrative interfaces for your Eloquent models. It supports generating full create, read, update, and delete interfaces simply by using the <code>HasCrud</code> trait on your models.
                </p>

                <h3 id="the-power-of-hascrud" class="doc-feature-title" style="margin-top: 2rem;">The Power of HasCrud</h3>
                <p class="doc-text">
                    The <code>HasCrud</code> trait uses auto-discovery and introspection to automatically learn about your database schema, model properties (<code>$fillable</code>), and defined Eloquent relationships. <strong>With just one line of code, you get a full-fledged working CRUD dashboard.</strong>
                </p>

                <div class="doc-code-block">
                    <pre><code class="language-php">namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;
use HasinHayder\\TyroDashboard\\Concerns\\HasCrud;

class Post extends Model
{
    use HasCrud;

    protected $fillable = [
        'title',
        'content',
        'is_published',
        'category_id',
    ];

    // Defining this relationship tells HasCrud to automatically create a select field for category_id!
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}</code></pre>
                </div>
                <p class="doc-text">
                    The <code>HasCrud</code> trait introspects the table schema, analyzes your <code>$fillable</code> array, and intelligently guesses field types. For example:
                </p>
                <ul class="doc-list">
                    <li>It matches a <code>_id</code> suffix to a <code>select</code> type.</li>
                    <li>It detects <code>is_</code>, <code>has_</code>, <code>can_</code> prefixed fields as <code>boolean</code> inputs.</li>
                    <li>It sees fields like <code>description</code>, <code>bio</code>, or <code>content</code> and turns them into <code>textarea</code>.</li>
                    <li>It intelligently discovers all publicly exported Eloquent relationships like <code>BelongsToMany</code> to create syncable multi-select fields out of the box.</li>
                </ul>

                <h3 id="model-overrides" class="doc-feature-title" style="margin-top: 2rem;">Model Overrides & Deep Customization</h3>
                <p class="doc-text">
                    While auto-detection takes care of most of the heavy lifting, <code>HasCrud</code> exposes several highly customizable protected properties allowing you to bend the CRUD to your exact needs without needing external configuration files.
                </p>

                <h4 class="doc-feature-title" style="margin-top: 1.5rem; font-size: 1.1rem;">General Resource Overrides</h4>
                <ul class="doc-list">
                    <li><strong><code>$resourceKey</code></strong>: Override the URL slug/identifier used in routes (default is plural <code>snake_case</code> of class name).</li>
                    <li><strong><code>$resourceTitle</code></strong>: The plural display name of the resource (default is plural Title Case of class name).</li>
                    <li><strong><code>$resourceTitleSingular</code></strong>: The singular display name.</li>
                    <li><strong><code>$resourceRoles</code></strong>: An array of role slugs that can access this resource globally <code>['admin', 'manager']</code>.</li>
                    <li><strong><code>$resourceReadonly</code></strong>: An array of role slugs that can only view (read-only) <code>['editor', 'guest']</code>.</li>
                    <li><strong><code>$resourceUploadDisk</code></strong>: The Laravel storage disk for file uploads (<code>public</code> by default).</li>
                    <li><strong><code>$resourceUploadDirectory</code></strong>: The disk directory for uploads (<code>uploads</code> by default).</li>
                </ul>

                <h4 class="doc-feature-title" style="margin-top: 1.5rem; font-size: 1.1rem;">Tailoring Fields with $resourceFieldOverrides</h4>
                <p class="doc-text">
                    The real magic arrives with <strong><code>$resourceFieldOverrides</code></strong>. Instead of redefining all your fields or moving to a configuration file, use this property to inject UI specifics on a per-field basis.
                </p>
                <div class="doc-code-block">
                    <pre><code class="language-php">class User extends Model
{
    use HasCrud;

    protected $fillable = ['name', 'email', 'bio', 'password', 'role_id'];

    // Provide overriding UI states just for the fields that need tweaking
    protected $resourceFieldOverrides = [
        'email' => [
            'type' => 'email', // Ensures client validation for email
            'searchable' => true,
        ],
        'bio' => [
            'type' => 'markdown', // Change from textarea to rich markdown editor
            'hide_in_index' => true,
        ],
        'password' => [
            'type' => 'password',
            'hide_in_index' => true,      
            'hide_in_edit' => true, // Don't let users edit password here
        ],
    ];
}</code></pre>
                </div>
                <p class="doc-text" style="font-size: 0.9em; font-style: italic;">
                    Note: You can completely disable auto-generation and define the entire UI natively from scratch by providing a strict <code>$resourceFields</code> array inside your model instead. However, using <code>$resourceFieldOverrides</code> is the generally recommended path.
                </p>

                <h3 id="field-types-modifiers" class="doc-feature-title" style="margin-top: 2rem;">Field Types and Modifiers</h3>
                <p class="doc-text">Tyro Dashboard natively supports a huge list of input configurations.</p>
                
                <h4 class="doc-feature-title" style="margin-top: 1.5rem; font-size: 1.1rem;">Field Types</h4>
                <div class="table-container">
                    <table class="doc-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><code>text</code></td><td>Standard text input.</td></tr>
                            <tr><td><code>number</code></td><td>Numeric input (used for integers, decimals, prices, etc.).</td></tr>
                            <tr><td><code>textarea</code></td><td>Multi-line text (used for descriptions, bodies).</td></tr>
                            <tr><td><code>markdown</code></td><td>Built-in Markdown editor utilizing EasyMDE. Automatically hidden from index tables.</td></tr>
                            <tr><td><code>richtext</code></td><td>Rendered as a rich QuillJS HTML editor in views (safely sanitized against XSS).</td></tr>
                            <tr><td><code>boolean</code></td><td>Checkbox input for Yes/No states.</td></tr>
                            <tr><td><code>select</code></td><td>Standard dropdown.</td></tr>
                            <tr><td><code>multiselect</code></td><td>Multi-select dropdown. Supports syncing many-to-many relationships automatically.</td></tr>
                            <tr><td><code>radio</code></td><td>Radio button lists.</td></tr>
                            <tr><td><code>checkbox</code></td><td>Checkbox groups.</td></tr>
                            <tr><td><code>date</code> / <code>time</code> / <code>datetime-local</code></td><td>Specialized pickers natively handled.</td></tr>
                            <tr><td><code>password</code></td><td>Handled securely, stripped out of list views. On update actions, sending an empty password intelligently skips mutating the stored database password hash.</td></tr>
                            <tr><td><code>email</code></td><td>HTML5 Email input.</td></tr>
                            <tr><td><code>url</code></td><td>HTML5 URL input.</td></tr>
                            <tr><td><code>file</code></td><td>File upload integration.</td></tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="doc-feature-title" style="margin-top: 1.5rem; font-size: 1.1rem;">Field Options</h4>
                <p class="doc-text">Every field defined under <code>$resourceFieldOverrides</code> accepts various parameters that dictate behavior on forms:</p>
                <div class="doc-code-block">
                    <pre><code class="language-php">'description' => [
    'type' => 'text',
    'label' => 'Display Label',
    'rules' => 'required|min:5|max:200', // Laravel validation rules
    'default' => 'Initial Text',         // Default value shown on create screens
    'placeholder' => 'Enter text here',  // Standard placeholder
    'help_text' => 'Optional string beneath the input describing usage',
    'hide_in_index' => true,             // Hides field specifically on the table view
    'hide_in_create' => true,            // Hides on the "create" mode
    'hide_in_edit' => true,              // Hides on the "update/edit" mode
    'hide_in_form' => true,              // Hides completely across ALL forms (create + edit)
    'hide_in_single_view' => true,       // Hide from detail/show view
    'readonly' => true,                  // Forces HTML `readonly` state
],
// Sort/Search mechanics
'title' => [
    'searchable' => true, // Can users search this globally on index boards?
    'sortable' => true,   // Clickable column header asc/desc tracking?
]</code></pre>
                </div>

                <h4 class="doc-feature-title" style="margin-top: 1.5rem; font-size: 1.1rem;">Passing HTML Attributes</h4>
                <p class="doc-text">You can pass raw underlying HTML attributes directly to the generated inputs via the <code>attributes</code> dictionary. This is incredibly useful for setting HTML5 specific steps, disabled states, or data tags.</p>
                <div class="doc-code-block">
                    <pre><code class="language-php">'purchase_price' => [
    'type' => 'number',
    'attributes' => [
        'min' => '0',
        'step' => '0.01',
        'data-trigger' => 'price-update',
        'disabled' => 'disabled' // Or using native string 'disabled'
    ]
]</code></pre>
                </div>

                <h3 id="mastering-relationships" class="doc-feature-title" style="margin-top: 2rem;">Mastering Relationships</h3>
                <p class="doc-text">
                    <code>HasCrud</code> goes out of its way to link Eloquent relationships securely. When <code>HasCrud</code> analyzes your model, it identifies relationships and immediately builds the correct foreign selections out of the box. You only really need to customize them using <code>$resourceFieldOverrides</code> for fine-grained labeling.
                </p>
                <p class="doc-text">You do this via three main relationship keys:</p>
                <ul class="doc-list">
                    <li><strong><code>relationship</code></strong>: The name of the method housing your Eloquent relationship.</li>
                    <li><strong><code>option_label</code></strong>: The target DB column representing the display name for the relation choices.</li>
                    <li><strong><code>multiple</code></strong>: Needed for Multi-select <code>BelongsToMany</code> syncs where type is a standard <code>select</code>.</li>
                </ul>

                <h4 class="doc-feature-title" style="margin-top: 1.5rem; font-size: 1.1rem;">1D Relationships (BelongsTo)</h4>
                <div class="doc-code-block">
                    <pre><code class="language-php">// Creating standard one-to-many selectors
'category_id' => [
    'type' => 'select', // Or radio natively
    'label' => 'Parent Category',
    'relationship' => 'category', // Name of `public function category()`
    'option_label' => 'name', // What property to grab from the Category model
],</code></pre>
                </div>

                <h4 class="doc-feature-title" style="margin-top: 1.5rem; font-size: 1.1rem;">Many-To-Many Synced Relationships (BelongsToMany)</h4>
                <p class="doc-text">Multi-relational syncing handles logic perfectly underneath the hood. Choose the optimal display logic (a large multi-select, or an expanding array of checkboxes).</p>
                <div class="doc-code-block">
                    <pre><code class="language-php">// In `$resourceFieldOverrides` for Tags Syncing
'tags' => [
    'type' => 'multiselect', // Will render an auto-syncing multiselect dropdown
    // OR 'type' => 'checkbox' // Will render an auto-syncing UI checklist!
    'label' => 'Post Tags',
    'relationship' => 'tags',
    'option_label' => 'name', 
],</code></pre>
                </div>
                <p class="doc-text">
                    When <code>store()</code> or <code>update()</code> is tripped, these relationship selections are cleverly parsed out of standard Eloquent payload flows and strictly diverted safely through Eloquent's <code>User->tags()->sync([id_list])</code> function.
                </p>

                <h3 id="file-image-modifiers" class="doc-feature-title" style="margin-top: 2rem;">File and Image Modifiers</h3>
                <p class="doc-text">
                    File fields (<code>type => file</code>) automatically push uploads directly to standard Laravel Disks utilizing configured settings.
                </p>
                <p class="doc-text">
                    You can explicitly ask Tyro Dashboard to render image previews natively inline on Edit Views by overriding the config properties:
                </p>
                <div class="doc-code-block">
                    <pre><code class="language-php">'avatar' => [
    'type' => 'file',
    'display_image' => true, // Auto-renders an &lt;img&gt; preview if the stored asset is jpg/webp/gif
    'display_image_position' => 'top', // Show the preview 'top' (before input) or 'bottom' (after input) 
]</code></pre>
                </div>
                <p class="doc-text" style="font-size: 0.9em; font-style: italic;">
                    During deletion/replacement, Tyro Dashboard is equipped to safely manage auto-delete garbage collection. When you update a user's avatar, it drops the old picture first directly via Storage facades natively to preserve disk capacities.
                </p>
            </section>"""

    content = content[:start_idx] + new_section + content[end_idx:]
else:
    print("WARNING: start_marker not found!")

with open('new-design/doc.html', 'w', encoding='utf-8') as f:
    f.write(content)

print("Replacement complete.")
