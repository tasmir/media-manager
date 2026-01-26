<div class="group relative bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl hover:border-indigo-200 transition-all duration-300">
    <!-- Image Preview -->
    <div class="aspect-square bg-slate-100 overflow-hidden relative">
        @if(in_array(strtolower($media->extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
            <img src="{{ route('file.show', [$media->slug]) }}?w=200"
                 alt="{{ $media->alt }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                 loading="lazy">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center p-4">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="mt-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-0.5 rounded">{{ $media->extension }}</span>
            </div>
        @endif

        <!-- Actions Overlay (Always visible on mobile, hover on desktop) -->
        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
            @can('files_edit')
                <a href="{{ route($page_date['loop']->edit, $media) }}"
                   class="p-2 bg-white rounded-lg text-slate-700 hover:bg-indigo-600 hover:text-white transition-colors duration-200 shadow-lg"
                   title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
            @endcan

            <a href="{{ route('file.show', [$media->slug]) }}"
               target="_blank"
               class="p-2 bg-white rounded-lg text-slate-700 hover:bg-emerald-600 hover:text-white transition-colors duration-200 shadow-lg"
               title="View Detail">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
            @can('files_delete')
                <form action="{{ route($page_date['loop']->delete, $media) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this media?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-2 bg-white rounded-lg text-slate-700 hover:bg-rose-600 hover:text-white transition-colors duration-200 shadow-lg"
                            title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <!-- Media Info -->
    <div class="p-2">
        <p class="text-xs font-medium text-slate-900 truncate" title="{{ $media->name }}">{{ $media->name }}</p>
        <div class="mt-1 flex items-center justify-between">
            <span class="text-[10px] text-slate-400 font-mono">{{ $media->size }}</span>
            <span class="text-[10px] text-slate-400 font-mono">{{ $media->dimensions }}</span>
        </div>
    </div>
</div>
