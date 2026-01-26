<div class="flex flex-col h-full bg-slate-50">
    <!-- Modal Header -->
    <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-slate-200">
        <div class="flex items-center space-x-4">
            <h3 class="text-lg font-bold text-slate-800">Media Library</h3>
            <div class="flex p-1 bg-slate-100 rounded-lg">
                <button type="button" onclick="MediaManager.setTab('library')" id="tab-library" class="px-4 py-1.5 text-xs font-semibold rounded-md transition-all duration-200 bg-white text-indigo-600 shadow-sm">
                    Browse
                </button>
                <button type="button" onclick="MediaManager.setTab('upload')" id="tab-upload" class="px-4 py-1.5 text-xs font-semibold rounded-md transition-all duration-200 text-slate-600 hover:text-slate-900">
                    Upload New
                </button>
            </div>
        </div>
        <button type="button" onclick="MediaManager.close()" class="p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-hidden flex flex-col">
        <!-- Tab: Library -->
        <div id="panel-library" class="flex-1 flex flex-col p-6 overflow-hidden">
            <!-- Toolbar -->
            <div class="flex items-center justify-between mb-6 gap-4">
                <div class="relative flex-1 max-w-sm">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="media-search" placeholder="Search files..."
                           class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                           onkeyup="MediaManager.search(this.value)">
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span id="selected-count">0</span> items selected
                </div>
            </div>

            <!-- Scrollable Grid Area -->
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar" id="picker-list-container">
                @include('media-manager::partials.picker-list', ['medias' => $medias])
            </div>
        </div>

        <!-- Tab: Upload -->
        <div id="panel-upload" class="hidden flex-1 p-10 overflow-y-auto">
            <div id="picker-drop-zone" class="h-full flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-3xl bg-white hover:border-indigo-400 hover:bg-indigo-50/20 transition-all duration-300">
                <div class="bg-indigo-50 p-6 rounded-2xl text-indigo-600 mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-slate-900">Drop files to upload</h4>
                <p class="text-slate-500 mt-2 mb-6">or click to browse from your computer</p>
                <button type="button" onclick="document.getElementById('picker-file-input').click()" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all">
                    Select Files
                </button>
                <input type="file" id="picker-file-input" class="hidden" multiple accept="image/*">

                <!-- Progress -->
                <div id="picker-upload-progress" class="hidden w-full max-w-md mt-10">
                    <div class="flex items-center justify-between mb-2">
                        <span id="picker-progress-text" class="text-sm font-medium text-slate-700">Uploading...</span>
                        <span id="picker-progress-percent" class="text-sm font-bold text-indigo-600">0%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div id="picker-progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="px-6 py-4 bg-white border-t border-slate-200 flex items-center justify-between">
        <div class="text-xs text-slate-400">
            Selected IDs: <span id="selected-ids-display" class="font-mono">None</span>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="MediaManager.close()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                Cancel
            </button>
            <button type="button" onclick="MediaManager.confirm()" class="px-8 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all disabled:opacity-50 disabled:cursor-not-allowed" id="confirm-selection">
                Insert Media
            </button>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>
