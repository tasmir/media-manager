<?php

namespace Tasmir\MediaManager\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class MediaPicker extends Component
{
    public function __construct(
        public string $name,
        public ?string $label = null,
        public mixed $value = null,
        public string $type = 'single',
        public string $returnType = 'int',
        public string $placeholder = 'Select Media',
        public ?string $buttonClass = null,
        public ?string $buttonText = null
    ) {
        $this->buttonClass = $buttonClass ?? config('media-manager.button_class');
        $this->buttonText = $buttonText ?? config('media-manager.button_text');
    }

    public function render(): View
    {
        return view('media-manager::components.media-picker');
    }
}
