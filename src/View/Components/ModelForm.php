<?php

namespace HasinHayder\TyroDashboard\View\Components;

use HasinHayder\TyroDashboard\Support\ModelFormFieldResolver;
use Illuminate\View\Component;
use Illuminate\View\View;

class ModelForm extends Component {
    public array $fields;

    public array $columns;

    public function __construct(
        public $model = null,
        array $types = [],
        array $labels = [],
        array $fields = [],
        array $exclude = [],
        int|string $columns = 1,
        public ?array $columnOne = null,
        public ?array $columnTwo = null,
        public ?string $action = null,
        public string $method = 'POST',
        public ?string $header = null,
        public bool|string $submit = true,
        public ?string $submitLabel = null,
    ) {
        $this->fields = $model
            ? ModelFormFieldResolver::resolve($model, $types, $labels, $fields, $exclude)
            : [];

        $this->columns = $this->distributeColumns((int) $columns, $columnOne, $columnTwo);
        $this->submit = filter_var($submit, FILTER_VALIDATE_BOOL);
        $this->submitLabel = $submitLabel ?? 'Save';
    }

    protected function distributeColumns(int $count, ?array $one, ?array $two): array {
        $names = array_keys($this->fields);
        $count = max(1, $count);

        if ($count === 1) {
            return [$names];
        }

        $columns = array_fill(0, $count, []);
        $assigned = [];

        foreach ([0 => $one, 1 => $two] as $index => $assignedList) {
            if (! is_array($assignedList)) {
                continue;
            }
            foreach ($assignedList as $name) {
                if (isset($this->fields[$name]) && ! isset($assigned[$name])) {
                    $columns[$index][] = $name;
                    $assigned[$name] = true;
                }
            }
        }

        foreach ($names as $name) {
            if (isset($assigned[$name])) {
                continue;
            }
            $target = 0;
            for ($i = 1; $i < $count; $i++) {
                if (count($columns[$i]) < count($columns[$target])) {
                    $target = $i;
                }
            }
            $columns[$target][] = $name;
        }

        return $columns;
    }

    public function render(): View {
        return view('tyro-dashboard::forms.model-form');
    }
}
