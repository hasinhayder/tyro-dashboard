<?php

namespace HasinHayder\TyroDashboard\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Media extends Component {
    public function __construct(
        public $id = null,
        public $media = null,
        public string $variant = 'webp',
        public ?string $width = null,
        public ?string $height = null,
        public string $rounded = 'none',
        public bool|string $circle = false,
        public ?string $alt = null,
        public string $loading = 'lazy',
        public bool|string $showTitle = false,
    ) {}

    public function render(): View {
        return view('tyro-dashboard::components.media');
    }
}
