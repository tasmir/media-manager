/**
 * Tasmir Media Manager JS Library
 */
const MediaManager = {
    modal: null,
    overlay: null,
    options: {
        pickerUrl: '',
        storeUrl: '',
        csrfToken: '',
        baseUrl: window.location.origin
    },
    currentConfig: {
        type: 'single', // single, multiple
        returnType: 'int', // int, string, array
        onSelect: null,
        targetInput: null,
        targetPreview: null
    },
    selectedItems: [],
    searchTimeout: null,

    init(options = {}) {
        this.options = { ...this.options, ...options };
        
        // Create modal container if not exists
        if (!document.getElementById('media-manager-modal')) {
            const modalHtml = `
                <div id="media-manager-modal-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9998] hidden transition-opacity duration-300 opacity-0"></div>
                <div id="media-manager-modal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[95%] max-w-6xl h-[85vh] bg-white rounded-3xl shadow-2xl z-[9999] hidden transition-all duration-300 scale-95 opacity-0 overflow-hidden flex flex-col">
                    <div id="media-manager-content" class="h-full flex flex-col">
                        <div class="flex items-center justify-center h-full">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            this.modal = document.getElementById('media-manager-modal');
            this.overlay = document.getElementById('media-manager-modal-overlay');

            this.overlay.addEventListener('click', () => this.close());
        }
    },

    open(config = {}) {
        if (!this.modal) this.init();
        
        this.currentConfig = {
            type: config.type || 'single',
            returnType: config.returnType || 'int',
            onSelect: config.onSelect || null,
            targetInput: config.targetInput || null,
            targetPreview: config.targetPreview || null
        };
        this.selectedItems = [];

        // Reset display
        this.showModal();
        this.loadPicker();
    },

    showModal() {
        this.modal.classList.remove('hidden');
        this.overlay.classList.remove('hidden');
        setTimeout(() => {
            this.modal.classList.add('scale-100', 'opacity-100');
            this.modal.classList.remove('scale-95', 'opacity-0');
            this.overlay.classList.add('opacity-100');
            this.overlay.classList.remove('opacity-0');
        }, 10);
    },

    close() {
        this.modal.classList.remove('scale-100', 'opacity-100');
        this.modal.classList.add('scale-95', 'opacity-0');
        this.overlay.classList.remove('opacity-100');
        this.overlay.classList.add('opacity-0');
        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.overlay.classList.add('hidden');
        }, 300);
    },

    loadPicker(url = null) {
        const pickerUrl = url || this.options.pickerUrl;
        const content = document.getElementById('media-manager-content');
        
        fetch(pickerUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
            this.setupInitialState();
            this.setupUploadHandlers();
            this.setupPagination();
        })
        .catch(err => console.error('Failed to load media picker:', err));
    },

    setupInitialState() {
        // Update selected items if targetInput has values
        if (this.currentConfig.targetInput) {
            const input = document.getElementById(this.currentConfig.targetInput);
            if (input && input.value) {
                const val = input.value;
                let ids = [];
                try {
                    const parsed = JSON.parse(val);
                    ids = Array.isArray(parsed) ? parsed.map(String) : [val.toString()];
                } catch (e) {
                    ids = val.split(',').map(v => v.trim()).filter(v => v !== '');
                }
                
                this.selectedItems = ids.map(id => {
                    const gridItem = document.querySelector(`.media-picker-item[data-id="${id}"]`);
                    return {
                        id: id.toString(),
                        slug: gridItem ? gridItem.dataset.slug : '',
                        thumb: gridItem ? gridItem.dataset.thumb : ''
                    };
                });
            }
        }
        this.updateUI();
    },

    setTab(tab) {
        document.getElementById('panel-library').classList.toggle('hidden', tab !== 'library');
        document.getElementById('panel-upload').classList.toggle('hidden', tab !== 'upload');
        
        document.getElementById('tab-library').className = tab === 'library' 
            ? 'px-4 py-1.5 text-xs font-semibold rounded-md transition-all duration-200 bg-white text-indigo-600 shadow-sm' 
            : 'px-4 py-1.5 text-xs font-semibold rounded-md transition-all duration-200 text-slate-600 hover:text-slate-900';
            
        document.getElementById('tab-upload').className = tab === 'upload' 
            ? 'px-4 py-1.5 text-xs font-semibold rounded-md transition-all duration-200 bg-white text-indigo-600 shadow-sm' 
            : 'px-4 py-1.5 text-xs font-semibold rounded-md transition-all duration-200 text-slate-600 hover:text-slate-900';
    },

    toggleSelection(el) {
        const id = el.dataset.id;
        const item = {
            id: id,
            slug: el.dataset.slug,
            thumb: el.dataset.thumb
        };
        
        const index = this.selectedItems.findIndex(i => i.id === id);

        if (this.currentConfig.type === 'single') {
            this.selectedItems = [item];
            // Clear other selections in UI
            document.querySelectorAll('.media-picker-item').forEach(item => item.classList.remove('is-selected'));
            el.classList.add('is-selected');
        } else {
            if (index > -1) {
                this.selectedItems.splice(index, 1);
                el.classList.remove('is-selected');
            } else {
                this.selectedItems.push(item);
                el.classList.add('is-selected');
            }
        }
        this.updateUI();
    },

    updateUI() {
        const ids = this.selectedItems.map(i => i.id);
        const countEl = document.getElementById('selected-count');
        const idsDisplayEl = document.getElementById('selected-ids-display');
        
        if (countEl) countEl.innerText = this.selectedItems.length;
        if (idsDisplayEl) idsDisplayEl.innerText = ids.length > 0 ? ids.join(', ') : 'None';
        
        // Update grid visual states
        document.querySelectorAll('.media-picker-item').forEach(item => {
            if (ids.includes(item.dataset.id)) {
                item.classList.add('is-selected');
            } else {
                item.classList.remove('is-selected');
            }
        });

        const confirmBtn = document.getElementById('confirm-selection');
        if (confirmBtn) {
            confirmBtn.disabled = this.selectedItems.length === 0;
        }
    },

    search(query) {
        if (this.searchTimeout) clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            const url = new URL(this.options.pickerUrl, this.options.baseUrl);
            url.searchParams.append('search', query);
            url.searchParams.append('list_only', '1');
            this.fetchList(url.toString());
        }, 500);
    },

    setupPagination() {
        const pagination = document.getElementById('picker-pagination');
        if (pagination) {
            pagination.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.fetchList(link.href);
                });
            });
        }
    },

    fetchList(url) {
        const fetchUrl = new URL(url, this.options.baseUrl);
        fetchUrl.searchParams.append('list_only', '1');

        const container = document.getElementById('picker-list-container');
        if (container) {
            container.classList.add('opacity-50', 'pointer-events-none');
            
            fetch(fetchUrl.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                container.classList.remove('opacity-50', 'pointer-events-none');
                this.updateUI();
                this.setupPagination();
            })
            .catch(err => {
                console.error('Failed to fetch media list:', err);
                container.classList.remove('opacity-50', 'pointer-events-none');
            });
        }
    },

    confirm() {
        const ids = this.selectedItems.map(i => i.id);
        let returnValue = null;
        if (this.currentConfig.type === 'single') {
            returnValue = ids[0];
        } else {
            if (this.currentConfig.returnType === 'string') {
                returnValue = ids.join(',');
            } else {
                returnValue = ids; // array
            }
        }

        // Update target input
        if (this.currentConfig.targetInput) {
            const input = document.getElementById(this.currentConfig.targetInput);
            if (input) {
                input.value = Array.isArray(returnValue) ? JSON.stringify(returnValue) : returnValue;
                // Trigger change event
                input.dispatchEvent(new Event('change'));
            }
        }

        // Handle Preview
        if (this.currentConfig.targetPreview) {
            this.updatePreview(this.selectedItems);
        }

        // Call callback
        if (this.currentConfig.onSelect) {
            this.currentConfig.onSelect(returnValue, this.selectedItems);
        }

        this.close();
    },

    updatePreview(items) {
        const previewContainer = document.getElementById(this.currentConfig.targetPreview);
        if (!previewContainer) return;

        previewContainer.innerHTML = '';
        items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'relative group w-20 h-20';
            div.innerHTML = `
                <img src="${item.thumb}" class="w-full h-full object-cover rounded-xl border border-slate-200">
                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                    <button type="button" onclick="this.parentElement.parentElement.remove(); MediaManager.removeItemFromInput('${item.id}', '${this.currentConfig.targetInput}')" class="text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            `;
            previewContainer.appendChild(div);
        });
    },

    removeItemFromInput(id, inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        let val = input.value;
        if (this.currentConfig.type === 'single') {
            input.value = '';
        } else {
            let ids = [];
            try {
                const parsed = JSON.parse(val);
                ids = Array.isArray(parsed) ? parsed.map(String) : val.split(',').map(v => v.trim());
            } catch (e) {
                ids = val.split(',').map(v => v.trim());
            }
            
            ids = ids.filter(v => v !== id.toString() && v !== '');
            input.value = ids.join(',');
        }
        input.dispatchEvent(new Event('change'));
    },

    setupUploadHandlers() {
        const dropZone = document.getElementById('picker-drop-zone');
        const fileInput = document.getElementById('picker-file-input');
        if (!dropZone) return;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(name => {
            dropZone.addEventListener(name, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        dropZone.addEventListener('drop', (e) => {
            this.handleUpload(e.dataTransfer.files);
        });

        fileInput.addEventListener('change', (e) => {
            this.handleUpload(e.target.files);
        });
    },

    handleUpload(files) {
        if (!files.length) return;

        const progress = document.getElementById('picker-upload-progress');
        const bar = document.getElementById('picker-progress-bar');
        const percentText = document.getElementById('picker-progress-percent');
        
        progress.classList.remove('hidden');

        const formData = new FormData();
        formData.append('file', files[0]); // Handle one for now or loop
        formData.append('_token', this.options.csrfToken);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', this.options.storeUrl, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.addEventListener("progress", (e) => {
            if (e.lengthComputable) {
                const pc = Math.round((e.loaded / e.total) * 100);
                if (bar) bar.style.width = pc + "%";
                if (percentText) percentText.innerText = pc + "%";
            }
        });

        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const res = JSON.parse(xhr.responseText);
                    this.setTab('library');
                    this.loadPicker(); // Refresh list
                    setTimeout(() => {
                        progress.classList.add('hidden');
                        if (bar) bar.style.width = "0%";
                    }, 1000);
                } else {
                    alert('Upload failed');
                    progress.classList.add('hidden');
                }
            }
        };

        xhr.send(formData);
    }
};

window.MediaManager = MediaManager;
