<?php

use HasinHayder\TyroDashboard\Tests\TestCase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class EditorComponentTest extends TestCase {
    protected function renderEditor(string $attrs = 'name="bio" id="bio" :value="$value"', array $data = []): string {
        Route::get('editor-test', function () use ($attrs, $data) {
            return Blade::render('<x-tyro-dashboard-editor '.$attrs.' />', array_merge(['value' => '**Hello**'], $data));
        })->middleware('web');

        return $this->get('editor-test')->getContent();
    }

    public function test_renders_textarea_with_editor_data_attributes(): void {
        $html = $this->renderEditor();

        $this->assertStringContainsString('data-tyro-editor', $html);
        $this->assertStringContainsString('data-output="select"', $html);
        $this->assertStringContainsString('data-full-url="true"', $html);
        $this->assertStringContainsString('name="bio"', $html);
        $this->assertStringContainsString('id="bio"', $html);
        $this->assertStringContainsString('**Hello**', $html);
    }

    public function test_default_toolbar_contains_image_button(): void {
        $html = html_entity_decode($this->renderEditor());

        $this->assertStringContainsString('"bold"', $html);
        $this->assertStringContainsString('"image"', $html);
        $this->assertStringContainsString('"preview"', $html);
    }

    public function test_invalid_output_mode_falls_back_to_select(): void {
        $html = $this->renderEditor('name="bio" output="bogus"');

        $this->assertStringContainsString('data-output="select"', $html);
    }

    public function test_custom_array_toolbar_is_json_encoded(): void {
        $html = html_entity_decode($this->renderEditor('name="bio" :toolbar="$toolbar"', ['toolbar' => ['bold', 'italic']]));

        $this->assertStringContainsString('["bold","italic"]', $html);
        $this->assertStringNotContainsString('"preview"', $html);
    }

    public function test_label_and_help_render_when_provided(): void {
        $html = $this->renderEditor('name="bio" label="Body" help="Markdown supported"');

        $this->assertStringContainsString('>Body</label>', $html);
        $this->assertStringContainsString('Markdown supported', $html);
    }

    public function test_error_rendering_can_be_suppressed(): void {
        Route::get('editor-error-default', function () {
            return Blade::render('<x-tyro-dashboard-editor name="bio" />');
        })->middleware('web');

        Route::get('editor-error-off', function () {
            return Blade::render('<x-tyro-dashboard-editor name="bio" :show-error="false" />');
        })->middleware('web');

        $session = ['errors' => new ViewErrorBag];
        $bag = new MessageBag(['bio' => ['The bio field is required.']]);
        $session['errors']->put('default', $bag);

        $withError = $this->withSession($session)->get('editor-error-default')->getContent();
        $withoutError = $this->withSession($session)->get('editor-error-off')->getContent();

        $this->assertStringContainsString('The bio field is required.', $withError);
        $this->assertStringNotContainsString('The bio field is required.', $withoutError);
    }

    public function test_media_assets_are_deduplicated_when_coexisting_with_media_picker(): void {
        Route::get('editor-coexist', function () {
            return Blade::render(
                '<x-media-picker name="cover" />'
                .'<x-tyro-dashboard-editor name="bio" />'
                .'{!! $__env->yieldPushContent("scripts") !!}'
            );
        })->middleware('web');

        $html = $this->get('editor-coexist')->getContent();

        $this->assertSame(1, substr_count($html, 'id="tyroDashboardMediaPickerModal"'));
        $this->assertSame(1, substr_count($html, 'window.TyroDashboardMediaPicker = {'));

        // The picker script must be emitted before the editor bootstrap so the
        // image toolbar action can detect window.TyroDashboardMediaPicker.
        $this->assertLessThan(
            strpos($html, 'window.TyroDashboardEditor = {'),
            strpos($html, 'window.TyroDashboardMediaPicker = {')
        );
    }
}
