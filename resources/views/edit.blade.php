@extends('backend.layouts.app')
@section('title', $page_date['page_title'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $page_date['page_title'] }}</h1>
            <p class="text-slate-500 mt-1">Update media metadata and attributes.</p>
        </div>
        <a href="{{ $page_date['back_button'] }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Library
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Preview Section -->
        <div class="md:col-span-1 space-y-4">
            <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-900 mb-4">Media Preview</h3>
                <div class="aspect-square bg-slate-50 rounded-2xl overflow-hidden border border-slate-100 mb-4">
                    @if(in_array(strtolower($page_date['model_data']->extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
                        <img src="{{ route('file.show', [$page_date['model_data']->slug]) }}" 
                             alt="{{ $page_date['model_data']->alt }}" 
                             class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center p-8 text-slate-300">
                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="mt-4 font-bold uppercase tracking-widest text-slate-400">{{ $page_date['model_data']->extension }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">File Name</span>
                        <span class="text-slate-700 font-medium truncate ml-4">{{ $page_date['model_data']->name }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">File Size</span>
                        <span class="text-slate-700 font-medium">{{ $page_date['model_data']->size }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Dimensions</span>
                        <span class="text-slate-700 font-medium">{{ $page_date['model_data']->dimensions ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Uploaded At</span>
                        <span class="text-slate-700 font-medium">{{ $page_date['model_data']->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                <div class="flex gap-3">
                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-indigo-900">Pro Tip</h4>
                        <p class="text-xs text-indigo-700 mt-1">SEO matters! Adding descriptive Alt text and Captions helps your files rank better in search results.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden text-slate-900">
                <form action="{{ $page_date['form']->action }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf
                    @method($page_date['form']->type)

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Slug Field -->
                        <div class="space-y-2">
                            <label for="slug" class="text-sm font-semibold text-slate-700 flex items-center">
                                File URL Slug
                                <span class="ml-1 text-rose-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </div>
                                <input type="text" name="slug" id="slug" 
                                       value="{{ old('slug', $page_date['model_data']->slug) }}"
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-slate-700 font-mono text-sm"
                                       placeholder="file-name-slug">
                            </div>
                            @error('slug') <p class="mt-1 text-xs text-rose-500 italic">{{ $message }}</p> @enderror
                        </div>

                        <!-- Alt Text Field -->
                        <div class="space-y-2">
                            <label for="alt" class="text-sm font-semibold text-slate-700">Alternative Text (Alt)</label>
                            <input type="text" name="alt" id="alt" 
                                   value="{{ old('alt', $page_date['model_data']->alt) }}"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-slate-700"
                                   placeholder="Describe the image content...">
                            <p class="text-[10px] text-slate-400">Used for accessibility and SEO. Describe the image for users who can't see it.</p>
                            @error('alt') <p class="mt-1 text-xs text-rose-500 italic">{{ $message }}</p> @enderror
                        </div>

                        <!-- Caption Field -->
                        <div class="space-y-2">
                            <label for="caption" class="text-sm font-semibold text-slate-700">Caption</label>
                            <textarea name="caption" id="caption" rows="3"
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none text-slate-700 resize-none"
                                      placeholder="Add a brief description or credit...">{{ old('caption', $page_date['model_data']->caption) }}</textarea>
                            @error('caption') <p class="mt-1 text-xs text-rose-500 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <a href="{{ $page_date['back_button'] }}" 
                           class="px-6 py-3 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/20 transition-all shadow-lg shadow-indigo-100">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Information Box -->
            <div class="mt-6 bg-slate-900 rounded-3xl p-6 text-white shadow-xl overflow-hidden relative group">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/30 transition-colors duration-500"></div>
                <div class="relative flex items-start gap-4">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md">
                        <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold">Integration URL</h4>
                        <p class="text-indigo-100/60 text-sm mt-1">Use this URL to embed this media in your content or API.</p>
                        <div class="mt-4 flex items-center gap-2">
                            <code id="mediaUrl" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-indigo-300 font-mono text-xs truncate" style="max-width: 435px">
                                {{ route('file.show', [$page_date['model_data']->slug]) }}
                            </code>
                            <button onclick="copyToClipboard('{{ route('file.show', [$page_date['model_data']->slug]) }}', this)"
                                    class="p-3 bg-indigo-500 hover:bg-indigo-400 rounded-xl transition-all shadow-lg active:scale-95 group/copy relative"
                                    title="Copy URL">
                                <span class="copy-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                </span>
                                <span class="check-icon hidden">
                                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-6 bg-slate-900 rounded-3xl p-6 text-white shadow-xl overflow-hidden relative group">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/30 transition-colors duration-500"></div>
                <div class="relative flex items-start gap-4">
                    <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-md">
                        <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold">Accual URL</h4>
                        <p class="text-indigo-100/60 text-sm mt-1">Use this URL to open this media in your browser derictly.</p>
                        <div class="mt-4 flex items-center gap-2">
                            <code id="mediaActualUrl" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-indigo-300 font-mono text-xs truncate" style="max-width: 435px">
                                {{ url(asset($page_date['model_data']->path)) }}
                            </code>
                            <button onclick="copyToClipboard('{{ url(asset($page_date['model_data']->path)) }}', this)"
                                    class="p-3 bg-indigo-500 hover:bg-indigo-400 rounded-xl transition-all shadow-lg active:scale-95 group/copy relative"
                                    title="Copy URL">
                                <span class="copy-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                </span>
                                <span class="check-icon hidden">
                                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    function copyToClipboard(text, btn) {
        const copyIcon = btn.querySelector('.copy-icon');
        const checkIcon = btn.querySelector('.check-icon');

        const fallbackCopy = (val) => {
            const textArea = document.createElement("textarea");
            textArea.value = val;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                showSuccess();
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }
            document.body.removeChild(textArea);
        };

        const showSuccess = () => {
            copyIcon.classList.add('hidden');
            checkIcon.classList.remove('hidden');
            setTimeout(() => {
                copyIcon.classList.remove('hidden');
                checkIcon.classList.add('hidden');
            }, 2000);
        };

        if (!navigator.clipboard) {
            fallbackCopy(text);
            return;
        }

        navigator.clipboard.writeText(text).then(() => {
            showSuccess();
        }, (err) => {
            console.error('Async: Could not copy text: ', err);
            fallbackCopy(text);
        });
    }
</script>
@endpush
@endsection
