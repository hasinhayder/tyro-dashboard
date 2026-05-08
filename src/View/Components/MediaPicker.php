<?php

namespace HasinHayder\TyroDashboard\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MediaPicker extends Component {
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $value = null,
        public string $output = 'webp',
        public string $buttonText = 'Select media',
        public string $placeholder = 'Select or paste a media URL',
        public ?string $label = null,
        public string $width = '550px',
    ) {}

    public function render(): View {
        return view('tyro-dashboard::components.media-picker');
    }
}
