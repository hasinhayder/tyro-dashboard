<?php

namespace HasinHayder\TyroDashboard\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Editor extends Component {
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $value = null,
        public ?string $label = null,
        public ?string $placeholder = null,
        public ?string $help = null,
        public bool|string $showError = true,
        public string $height = '300px',
        public string|array $toolbar = 'bold,italic,heading,|,quote,unordered-list,ordered-list,|,link,image,|,preview',
        public bool $spellChecker = false,
        public array|bool $status = false,
        public string $output = 'select',
        public bool|string $fullUrl = true,
        public int|string|null $columns = null,
        public array $options = [],
    ) {}

    public function render(): View {
        return view('tyro-dashboard::components.editor');
    }
}
