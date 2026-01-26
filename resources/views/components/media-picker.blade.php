@props([
    'name',
    'label' => null,
    'value' => null,
    'type' => 'single', // single, multiple
    'returnType' => 'int', // int, string, array
    'placeholder' => 'Select Media',
    'buttonClass' => null,
    'buttonText' => null
])

<div class="media-picker-container" x-data="{ hasValue: '{{ $value }}' }">
    @if($label)
        <label class="block text-sm font-medium text-slate-700 mb-1">{{ $label }}</label>
    @endif

    <div class="flex flex-wrap gap-4 items-start">
        <!-- Preview Area -->
        <div id="preview-{{ $name }}" class="flex flex-wrap gap-3">
            @if($value)
                @php
                    $ids = [];
                    if (is_string($value) && str_starts_with($value, '[')) {
                        $ids = json_decode($value, true) ?: [];
                    } else {
                        $ids = explode(',', $value);
                    }
                    $ids = array_filter(array_map('trim', (array)$ids));
                    $medias = \Tasmir\MediaManager\Models\MediaFile::whereIn('id', $ids)->orderByRaw('FIELD(id, ' . implode(',', $ids) . ')')->get();
                @endphp
                @foreach($medias as $media)
                    <div class="relative group w-20 h-20">
                        <img src="{{ route('file.show', $media->slug) }}?w=100" class="w-full h-full object-cover rounded-xl border border-slate-200" loading="lazy">
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                            <button type="button" onclick="this.parentElement.parentElement.remove(); MediaManager.removeItemFromInput('{{ $media->id }}', '{{ $name }}')" class="text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Add Button -->
        <button type="button"
                onclick="MediaManager.open({
                    type: '{{ $type }}',
                    returnType: '{{ $returnType }}',
                    targetInput: '{{ $name }}',
                    targetPreview: 'preview-{{ $name }}'
                })"
                class="{{ $buttonClass ?? config('media-manager.button_class') }}">
            <svg class="w-6 h-6 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-[10px] font-semibold">{{ $buttonText ?? config('media-manager.button_text') }}</span>
        </button>
    </div>

    <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}">
</div>
