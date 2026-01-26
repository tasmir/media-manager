<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4" id="picker-media-grid">
    @foreach($medias as $media)
        <div class="media-picker-item group relative bg-white border border-slate-200 rounded-xl overflow-hidden cursor-pointer hover:border-indigo-400 transition-all duration-200"
             data-id="{{ $media->id }}"
             data-slug="{{ $media->slug }}"
             data-thumb="{{ route('file.show', $media->slug) }}?w=100"
             onclick="MediaManager.toggleSelection(this)">
            <div class="aspect-square bg-slate-50 relative">
                <img src="{{ route('file.show', $media->slug) }}?w=200"
                     alt="{{ $media->alt }}"
                     class="w-full h-full object-cover" loading="lazy">

                <!-- Selection Overlay -->
                <div class="selection-overlay absolute inset-0 bg-indigo-600/20 opacity-0 group-[.is-selected]:opacity-100 transition-opacity flex items-center justify-center">
                    <div class="bg-indigo-600 text-white rounded-full p-1 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <p class="text-[10px] font-medium text-slate-900 truncate">{{ $media->name }}</p>
                <p class="text-[8px] text-slate-400 font-mono">{{ $media->dimensions }}</p>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-6" id="picker-pagination">
    {{ $medias->links() }}
</div>
