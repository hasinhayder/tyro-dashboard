<?php

use HasinHayder\TyroDashboard\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

class FormTestModel extends Model {
    protected $fillable = ['name', 'email', 'bio', 'avatar', 'is_active'];
    protected $hidden = ['secret_token'];
    protected $guarded = [];
}

class FormTestModelWithSecret extends FormTestModel {
    protected $fillable = ['name', 'email', 'secret_token'];
}

class ModelFormComponentTest extends TestCase {
    protected function defineEnvironment($app) {
        parent::defineEnvironment($app);

        $app['config']->set('database.default', 'testing');
    }

    protected function renderForm(array $props): string {
        $propsHtml = '';
        foreach ($props as $key => $value) {
            if (is_bool($value)) {
                $propsHtml .= ' '.$key.'="'.($value ? 'true' : 'false').'"';
            } elseif (is_array($value)) {
                $propsHtml .= ' :'.$key.'="'.str_replace('"', '&quot;', var_export($value, true)).'"';
            } else {
                $propsHtml .= ' '.$key.'="'.e($value).'"';
            }
        }

        Route::get('form-test', function () use ($propsHtml) {
            return Blade::render('<x-tyro-dashboard-model-form'.$propsHtml.' />');
        })->middleware('web');

        return $this->get('form-test')->getContent();
    }

    public function test_renders_fillable_fields_and_excludes_hidden(): void {
        $html = $this->renderForm(['model' => FormTestModelWithSecret::class]);

        $this->assertStringContainsString('name="name"', $html);
        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('type="email"', $html);
        $this->assertStringNotContainsString('name="secret_token"', $html);
    }

    public function test_type_overrides_render_media_picker_and_textarea(): void {
        $html = $this->renderForm([
            'model' => FormTestModel::class,
            'types' => ['name' => 'textarea'],
        ]);

        $this->assertStringContainsString('<textarea', $html);
        $this->assertStringContainsString('data-tyro-media-picker-input', $html);
        $this->assertStringContainsString('name="avatar"', $html);
    }

    public function test_header_and_submit_behavior(): void {
        $html = $this->renderForm(['model' => FormTestModel::class]);

        $this->assertStringNotContainsString('form-header', $html);
        $this->assertStringContainsString('type="submit"', $html);
        $this->assertStringContainsString('Save', $html);

        $html = $this->renderForm([
            'model' => FormTestModel::class,
            'header' => 'Edit Product',
            'submit' => false,
        ]);

        $this->assertStringContainsString('Edit Product', $html);
        $this->assertStringNotContainsString('type="submit"', $html);
    }

    public function test_submit_label_is_customizable(): void {
        $html = $this->renderForm([
            'model' => FormTestModel::class,
            'submitLabel' => 'Create Product',
        ]);

        $this->assertStringContainsString('Create Product', $html);
    }

    public function test_two_columns_with_explicit_assignment(): void {
        $html = $this->renderForm([
            'model' => FormTestModel::class,
            'columns' => 2,
            'columnOne' => ['name', 'email'],
            'columnTwo' => ['bio'],
        ]);

        $this->assertStringContainsString('model-form-two-columns', $html);
        $this->assertEquals(2, substr_count($html, 'model-form-column'));
    }

    public function test_single_column_by_default(): void {
        $html = $this->renderForm(['model' => FormTestModel::class]);

        $this->assertStringNotContainsString('model-form-two-columns', $html);
        $this->assertEquals(1, substr_count($html, 'model-form-column'));
    }

    public function test_action_defaults_to_current_url(): void {
        $html = $this->renderForm(['model' => FormTestModel::class]);

        $this->assertStringContainsString('action="http://localhost/form-test"', $html);
    }

    public function test_custom_action_and_method_passthrough(): void {
        $html = $this->renderForm([
            'model' => FormTestModel::class,
            'action' => 'https://example.com/submit',
            'method' => 'PUT',
        ]);

        $this->assertStringContainsString('action="https://example.com/submit"', $html);
        $this->assertStringContainsString('method="POST"', $html);
        $this->assertStringNotContainsString('method="PUT"', $html);
        $this->assertStringContainsString('name="_method"', $html);
        $this->assertStringContainsString('value="PUT"', $html);
    }

    public function test_get_method_does_not_spoof(): void {
        $html = $this->renderForm([
            'model' => FormTestModel::class,
            'method' => 'GET',
        ]);

        $this->assertStringContainsString('method="GET"', $html);
        $this->assertStringNotContainsString('name="_method"', $html);
    }

    public function test_field_attributes_are_escaped(): void {
        Route::get('form-attributes', function () {
            return Blade::render(
                '<x-tyro-dashboard-model-form :model="$model" :fields="$fields" />',
                [
                    'model' => FormTestModel::class,
                    'fields' => ['name' => ['attributes' => ['data-x' => 'a"b']]],
                ]
            );
        })->middleware('web');

        $html = $this->get('form-attributes')->getContent();

        $this->assertStringContainsString('data-x="a&quot;b"', $html);
        $this->assertStringNotContainsString('data-x="a"b"', $html);
    }

    public function test_select_options_render(): void {
        $html = $this->renderForm([
            'model' => FormTestModel::class,
            'fields' => [
                'name' => [
                    'type' => 'select',
                    'options' => ['draft' => 'Draft', 'live' => 'Live'],
                ],
            ],
        ]);

        $this->assertStringContainsString('<select name="name"', $html);
        $this->assertStringContainsString('value="draft"', $html);
        $this->assertStringContainsString('Draft', $html);
    }

    public function test_multiple_select_accepts_collection_values(): void {
        $model = new FormTestModel(['name' => collect(['draft', 'live'])]);

        Route::get('form-multi', function () use ($model) {
            return Blade::render('<x-tyro-dashboard-model-form :model="$model" :fields="$fields" />', [
                'model' => $model,
                'fields' => ['name' => ['type' => 'select', 'multiple' => true, 'options' => ['draft' => 'Draft', 'live' => 'Live']]],
            ]);
        })->middleware('web');

        $html = $this->get('form-multi')->getContent();

        $this->assertStringContainsString('name="name[]"', $html);
        $this->assertStringContainsString('value="draft" selected', $html);
        $this->assertStringContainsString('value="live" selected', $html);
    }

    public function test_resource_field_types_render(): void {
        $html = $this->renderForm([
            'model' => FormTestModel::class,
            'fields' => [
                'bio' => ['type' => 'markdown'],
                'name' => ['type' => 'radio', 'options' => ['a' => 'A']],
            ],
        ]);

        $this->assertStringContainsString('<textarea name="bio"', $html);
        $this->assertStringContainsString('type="radio" name="name"', $html);
    }

    public function test_edit_form_prefills_model_values(): void {
        $model = new FormTestModel(['name' => 'Existing Name']);

        Route::get('form-edit', function () use ($model) {
            return Blade::render('<x-tyro-dashboard-model-form :model="$model" />', ['model' => $model]);
        })->middleware('web');

        $html = $this->get('form-edit')->getContent();

        $this->assertStringContainsString('value="Existing Name"', $html);
    }
}
