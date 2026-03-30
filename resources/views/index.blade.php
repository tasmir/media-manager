@extends('backend.layouts.app')
@section('title', $page_date['page_title'])

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $page_date['page_title'] }}</h1>
            <p class="text-slate-500 mt-1">Manage and organize your media files efficiently.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ $page_date['rootURL'] }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all duration-200 shadow-sm">
                @if(request()->exists('trashed'))
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    Back to Active
                @else
                    <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    View Trash
                @endif
            </a>

            <button type="button"
               onclick="document.getElementById('file-input').click()"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-md shadow-indigo-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload Media
            </button>
        </div>
    </div>

    <!-- Stats/Quick Filters (Optional - can be expanded) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-4">
            <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Media</p>
                <p class="text-xl font-bold text-slate-900">{{ $page_date['model_data']->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Drag and Drop Zone -->
    @if(!request()->exists('trashed'))
    <div id="drop-zone" class="relative group">
        <div class="flex flex-col items-center justify-center py-10 bg-white rounded-3xl border-2 border-dashed border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/30 transition-all duration-300 cursor-pointer" onclick="document.getElementById('file-input').click()">
            <div class="bg-indigo-50 p-4 rounded-2xl text-indigo-600 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </div>
            <h3 class="mt-4 text-sm font-semibold text-slate-900">Drag and drop files here</h3>
            <p class="mt-1 text-xs text-slate-500">or click to browse from your computer</p>
            <input type="file" id="file-input" class="hidden" multiple accept="image/*">
        </div>

        <!-- Upload Progress Overlay (Hidden by default) -->
        <div id="upload-progress" class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm rounded-3xl z-10 flex flex-col items-center justify-center p-6">
            <div class="w-full max-w-xs bg-slate-100 rounded-full h-2 mb-4">
                <div id="progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p id="progress-text" class="text-sm font-medium text-slate-700">Uploading...</p>
        </div>
    </div>
    @endif

    <!-- Media Grid -->
    <div id="media-grid-container">
        @if($page_date['model_data']->count() > 0)
            <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-9 gap-4">
                @foreach($page_date['model_data'] as $media)
                    @include('media-manager::partials.media-item', ['media' => $media])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $page_date['model_data']->links() }}
            </div>
        @else
            <div id="empty-state" class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-dashed border-slate-300">
                <div class="bg-slate-50 p-6 rounded-full text-slate-300">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mt-6 text-lg font-semibold text-slate-900">{{ $page_date['empty_message'] }}</h3>
                <p class="mt-2 text-slate-500 text-center max-w-sm">It looks like there's no media here yet. Start by uploading some files to your library.</p>
            </div>
        @endif
    </div>
</div>
@inject('traitService', 'Tasmir\MediaManager\Services\TraitWrapper')


@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const mediaGrid = document.getElementById('media-grid');
    const emptyState = document.getElementById('empty-state');
    const gridContainer = document.getElementById('media-grid-container');

    if (!dropZone) return;

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    // Handle selected files
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropZone.classList.add('border-indigo-400', 'bg-indigo-50/50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-indigo-400', 'bg-indigo-50/50');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    let activeUploads = 0;

    function handleFiles(files) {
        const allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        // const maxSize = 10 * 1024 * 1024; // 10MB
        const maxSize = {{$traitService->minimumUpSize()}} // 10MB
        const validFiles = [];
        const rejectedFiles = [];

        ([...files]).forEach(file => {
            if (!allowedTypes.includes(file.type)) {
                rejectedFiles.push(`${file.name} (Invalid type)`);
            } else if (file.size > maxSize) {
                rejectedFiles.push(`${file.name} (Too large, max {{$traitService->bytesToHuman($traitService->minimumUpSize())}})`);
            } else {
                validFiles.push(file);
            }
        });

        if (rejectedFiles.length > 0) {
            alert('Some files were rejected:\n' + rejectedFiles.join('\n'));
        }

        if (validFiles.length > 0) {
            activeUploads += validFiles.length;
            uploadProgress.classList.remove('hidden');
            validFiles.forEach(uploadFile);
        }
    }

    function uploadFile(file) {
        const url = "{{ route('admin.files.store') }}";
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', "{{ csrf_token() }}");

        progressText.innerText = `Uploading ${activeUploads} file(s)...`;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.addEventListener("progress", function(e) {
            if (activeUploads === 1) {
                const pc = parseInt((e.loaded / e.total * 100));
                progressBar.style.width = pc + "%";
            } else {
                progressBar.style.width = "100%";
                progressBar.classList.add('animate-pulse');
            }
        });

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                activeUploads--;
                if (activeUploads <= 0) {
                    activeUploads = 0;
                    uploadProgress.classList.add('hidden');
                    progressBar.style.width = "0%";
                    progressBar.classList.remove('animate-pulse');
                } else {
                    progressText.innerText = `Uploading ${activeUploads} file(s)...`;
                }

                if (xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        appendMediaToGrid(response);
                    }
                } else {
                    let errorMessage = 'Failed to upload file: ' + file.name;
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.errors && response.errors.file) {
                            errorMessage = response.errors.file[0];
                        } else if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {}

                    console.error('Upload failed', xhr.responseText);
                    alert(errorMessage);
                }
            }
        };

        xhr.send(formData);
    }

    function appendMediaToGrid(data) {
        if (emptyState) {
            emptyState.remove();
        }

        if (!mediaGrid) {
            // Create the grid if it doesn't exist (when list was empty)
            const newGrid = document.createElement('div');
            newGrid.id = 'media-grid';
            newGrid.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-9 gap-4';
            gridContainer.prepend(newGrid);
            // Re-assign mediaGrid
            location.reload(); // Simplest way to get the grid structure right if it was empty
            return;
        }

        const media = data.media;
        const html = `
            <div class="group relative bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl hover:border-indigo-200 transition-all duration-300 animate-fade-in-up">
                <div class="aspect-square bg-slate-100 overflow-hidden relative">
                    <img src="${data.media_url}?w=200"
                         alt="${media.alt || ''}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                         loading="lazy">

                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                        <a href="${data.edit_url}"
                           class="p-2 bg-white rounded-lg text-slate-700 hover:bg-indigo-600 hover:text-white transition-colors duration-200 shadow-lg"
                           title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>

                        <a href="${data.media_url}"
                           target="_blank"
                           class="p-2 bg-white rounded-lg text-slate-700 hover:bg-emerald-600 hover:text-white transition-colors duration-200 shadow-lg"
                           title="View Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>

                        <form action="${data.delete_url}" method="POST" onsubmit="return confirm('Are you sure you want to delete this media?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit"
                                    class="p-2 bg-white rounded-lg text-slate-700 hover:bg-rose-600 hover:text-white transition-colors duration-200 shadow-lg"
                                    title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-2">
                    <p class="text-xs font-medium text-slate-900 truncate" title="${media.name}">${media.name}</p>
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-mono">${media.size || ''}</span>
                        <span class="text-[10px] text-slate-400 font-mono">${media.dimensions || ''}</span>
                    </div>
                </div>
            </div>
        `;

        mediaGrid.insertAdjacentHTML('afterbegin', html);
    }
});
</script>
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.4s ease-out forwards;
}
</style>
@endpush
@endsection


