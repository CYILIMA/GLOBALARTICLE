<div x-data="mediaLibraryModal()" 
     x-show="isOpen" 
     x-on:open-media-modal.window="openModal($event.detail)"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <!-- Backdrop -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="isOpen = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Panel -->
        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
            
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4 border-b dark:border-gray-700 pb-2">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">Select Media</h3>
                    <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Media Browser UI -->
                <div class="flex h-[500px] gap-4">
                     <!-- Sidebar (Folders) -->
                    <div class="w-1/4 border-r dark:border-gray-700 pr-2 overflow-y-auto">
                        <button @click="loadFolder(null)" :class="{'bg-blue-50 dark:bg-blue-900/20 text-blue-600': currentFolder === null}" class="w-full text-left px-3 py-2 rounded text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                            All Media
                        </button>
                        <template x-for="folder in folders" :key="folder.id">
                             <button @click="loadFolder(folder.id)" :class="{'bg-blue-50 dark:bg-blue-900/20 text-blue-600': currentFolder === folder.id}" class="w-full text-left px-3 py-2 rounded text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                <span x-text="folder.name"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Main Grid -->
                    <div class="w-3/4 flex flex-col">
                        <!-- Search -->
                        <div class="mb-4">
                            <input type="text" x-model="search" @keyup.debounce.300ms="loadMedia()" placeholder="Search..." class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm text-sm dark:text-white">
                        </div>

                        <!-- Grid -->
                        <div class="flex-1 overflow-y-auto p-1">
                             <div x-show="loading" class="flex justify-center py-10">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            </div>

                            <div x-show="!loading && media.length === 0" class="text-center text-gray-500 mt-10">
                                No media found.
                            </div>

                            <div x-show="!loading && media.length > 0" class="grid grid-cols-4 gap-4">
                                <template x-for="item in media" :key="item.id">
                                    <div @click="selectMedia(item)" class="group relative aspect-square bg-gray-50 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 overflow-hidden cursor-pointer hover:border-blue-500 transition">
                                        <template x-if="item.mime_type.startsWith('image/')">
                                            <img :src="item.url" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!item.mime_type.startsWith('image/')">
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                        </template>
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="isOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        function mediaLibraryModal() {
            return {
                isOpen: false,
                media: [],
                folders: [],
                currentFolder: null,
                search: '',
                loading: false,
                targetEditor: null,

                init() {
                    this.$watch('isOpen', value => {
                        if (value) {
                            this.loadMedia();
                            this.loadFolders();
                        }
                    });
                },

                openModal(detail) {
                    this.isOpen = true;
                    // detail could contain 'editorId' if we had multiple
                },

                async loadMedia() {
                    this.loading = true;
                    let url = "{{ route('admin.media.index') }}?format=json&folder_id=" + (this.currentFolder ?? 'null');
                    if (this.search) url += '&search=' + this.search;

                    try {
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' }});
                        const data = await res.json();
                        this.media = data.media;
                        this.folders = data.folders; // Update folders too in case
                    } catch (e) {
                        console.error('Failed to load media', e);
                    } finally {
                        this.loading = false;
                    }
                },

                async loadFolders() {
                    // This might be redundant if loadMedia returns folders, but good for initial check
                },

                loadFolder(id) {
                    this.currentFolder = id;
                    this.loadMedia();
                },

                selectMedia(item) {
                    if (item.mime_type.startsWith('image/')) {
                         this.insertImage(item.url, item.name);
                    } else {
                        // Handle other files (link?)
                        this.insertLink(item.url, item.name);
                    }
                    this.isOpen = false;
                },

                insertImage(url, alt) {
                    // We need to access the global editor instance. 
                    // Since we have window.editor or we can dispatch an event.
                    // Dispatching an event is cleaner.
                    window.dispatchEvent(new CustomEvent('media-selected', { 
                        detail: { url: url, type: 'image', alt: alt } 
                    }));
                },

                insertLink(url, name) {
                    window.dispatchEvent(new CustomEvent('media-selected', { 
                        detail: { url: url, type: 'file', name: name } 
                    }));
                }
            }
        }
    </script>
</div>
