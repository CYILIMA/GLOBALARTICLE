<x-admin-layout>
    @section('header', 'Media Library')

    <div class="flex h-[calc(100vh-140px)] gap-6" id="media-app">
        <!-- Sidebar: Folders -->
        <div class="w-64 bg-white dark:bg-gray-800 rounded-lg shadow-sm flex flex-col">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 dark:text-gray-300">Folders</h3>
                <button onclick="createFolderPrompt()" class="text-blue-600 hover:text-blue-800 text-sm font-bold p-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-2 space-y-1" id="folder-list">
                <!-- Folders injected here -->
                <button onclick="loadFolder(null)" class="w-full text-left px-3 py-2 rounded text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2 active-folder">
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                    All Media
                </button>
            </div>
        </div>

        <!-- Main Area -->
        <div class="flex-1 bg-white dark:bg-gray-800 rounded-lg shadow-sm flex flex-col relative">
            <!-- Toolbar -->
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <div class="relative">
                    <input type="text" id="median-search" placeholder="Search files..." onkeyup="debounceSearch()"
                           class="pl-9 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm dark:bg-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 w-64">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <div class="flex gap-3">
                     <label for="file-upload" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload Files
                     </label>
                     <input type="file" id="file-upload" class="hidden" multiple onchange="handleFiles(this.files)">
                </div>
            </div>

            <!-- Grid -->
            <div class="flex-1 p-4 overflow-y-auto" id="drop-zone">
                <div id="media-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    <!-- Media Items -->
                </div>
                
                <!-- Empty State -->
                <div id="empty-state" class="hidden h-full flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p>No media found</p>
                    <p class="text-sm">Drag and drop files here to upload</p>
                </div>
            </div>

            <!-- Loading Overlay -->
            <div id="loading" class="hidden absolute inset-0 bg-white/80 dark:bg-gray-800/80 flex items-center justify-center z-10 backdrop-blur-sm">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            </div>

             <!-- Drag Overlay -->
            <div id="drag-overlay" class="hidden absolute inset-0 bg-blue-500/10 border-2 border-blue-500 border-dashed m-2 rounded-lg flex items-center justify-center z-20 pointer-events-none">
                <div class="text-blue-600 font-bold text-xl bg-white dark:bg-gray-800 px-6 py-3 rounded-lg shadow-lg">Drop files to upload</div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentFolder = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Drag & Drop Logic
        const dropZone = document.getElementById('drop-zone');
        const dragOverlay = document.getElementById('drag-overlay');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        dropZone.addEventListener('dragenter', () => dragOverlay.classList.remove('hidden'));
        dropZone.addEventListener('dragleave', (e) => {
            if (e.relatedTarget && !dropZone.contains(e.relatedTarget)) {
                dragOverlay.classList.add('hidden');
            }
        });
        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            dragOverlay.classList.add('hidden');
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // Upload Logic
        function handleFiles(files) {
            if (!files.length) return;
            
            const MAX_SIZE = 50 * 1024 * 1024; // 50MB
            const validFiles = Array.from(files).filter(file => {
                if (file.size > MAX_SIZE) {
                    alert(`File "${file.name}" is too large (Max 50MB).`);
                    return false;
                }
                return true;
            });

            if (validFiles.length === 0) return;

            showLoading(true);
            const uploads = validFiles.map(file => uploadFile(file));

            Promise.all(uploads).then(() => {
                loadFolder(currentFolder); // Refresh
                showLoading(false);
            });
        }

        async function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            if (currentFolder) formData.append('folder_id', currentFolder);

            try {
                const response = await fetch("{{ route('admin.media.store') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                
                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Upload failed');
                }
            } catch (error) {
                console.error(error);
                alert(`Failed to upload ${file.name}: ${error.message}`);
            }
        }

        // Folder & Media Logic
        async function loadFolder(folderId) {
            currentFolder = folderId;
            showLoading(true);
            
            // Highlight sidebar
            document.querySelectorAll('#folder-list button').forEach(btn => btn.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-blue-600'));
            // (Simple highlighting logic omitted for brevity, would need ID on buttons)

            let url = "{{ route('admin.media.index') }}?format=json&folder_id=" + (folderId ?? 'null');
            const search = document.getElementById('median-search').value;
            if(search) url += '&search=' + search;

            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
                const data = await res.json();
                renderMedia(data.media);
                renderFolders(data.folders); // Optional: reload folders if dynamic
            } catch (e) {
                console.error(e);
            } finally {
                showLoading(false);
            }
        }

        function renderMedia(media) {
            const grid = document.getElementById('media-grid');
            const empty = document.getElementById('empty-state');
            grid.innerHTML = '';

            if (media.length === 0) {
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');

            media.forEach(item => {
                const isImage = item.mime_type.startsWith('image/');
                const div = document.createElement('div');
                div.className = 'group relative aspect-square bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition cursor-pointer';
                div.onclick = () => copyUrl(item.url);
                
                div.innerHTML = `
                    ${isImage 
                        ? `<img src="${item.url}" class="w-full h-full object-cover">` 
                        : `<div class="w-full h-full flex items-center justify-center text-gray-400"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>`}
                    
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-2">
                        <span class="text-white text-xs px-2 text-center truncate w-full">${item.name}</span>
                        <div class="flex gap-2">
                            <button onclick="event.stopPropagation(); deleteMedia(${item.id})" class="text-white bg-red-600 p-1.5 rounded hover:bg-red-700" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            <button onclick="event.stopPropagation(); copyUrl('${item.url}')" class="text-white bg-blue-600 p-1.5 rounded hover:bg-blue-700" title="Copy URL">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(div);
            });
        }

        function renderFolders(folders) {
            // Simplified: just clearing list and adding back 'All' then folders
            // A production app would manage state better
            const list = document.getElementById('folder-list');
            list.innerHTML = `
                <button onclick="loadFolder(null)" class="w-full text-left px-3 py-2 rounded text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2 ${currentFolder === null ? 'bg-gray-100 dark:bg-gray-700' : ''}">
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                    All Media
                </button>
            `;

            folders.forEach(folder => {
                 list.innerHTML += `
                    <button onclick="loadFolder(${folder.id})" class="w-full text-left px-3 py-2 rounded text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2 ${currentFolder === folder.id ? 'bg-gray-100 dark:bg-gray-700' : ''}">
                        <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                        ${folder.name}
                    </button>
                `;
            });
        }

        async function createFolderPrompt() {
            const name = prompt("Enter folder name:");
            if (name) {
                try {
                    await fetch("{{ route('admin.media.folder.create') }}", {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken 
                        },
                        body: JSON.stringify({ name: name, parent_id: currentFolder })
                    });
                    loadFolder(currentFolder);
                } catch(e) { alert('Error creating folder'); }
            }
        }

        async function deleteMedia(id) {
            if(!confirm('Delete this file?')) return;
            try {
                await fetch(`{{ url('/media') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                });
                loadFolder(currentFolder);
            } catch(e) { alert('Error deleting file'); }
        }

        function copyUrl(url) {
            navigator.clipboard.writeText(url);
            alert('URL Copied!');
        }

        function showLoading(show) {
            document.getElementById('loading').classList.toggle('hidden', !show);
        }

        let debounceTimer;
        function debounceSearch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => loadFolder(currentFolder), 300);
        }

        // Initial Load
        loadFolder(null);

    </script>
    @endpush
</x-admin-layout>
