import './bootstrap';

import Alpine from 'alpinejs';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.Alpine = Alpine;
window.ClassicEditor = ClassicEditor;

Alpine.data('richEditor', (content, config = {}) => ({
    editor: null,
    content: content,
    init() {
        if (!window.ClassicEditor) {
            console.error('ClassicEditor not found');
            return;
        }

        ClassicEditor
            .create(this.$refs.editor, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'insertTable', 'mediaEmbed', 'undo', 'redo'
                ],
                ...config
            })
            .then(editor => {
                this.editor = editor;

                // Sync data back to Alpine/Textarea
                editor.model.document.on('change:data', () => {
                    this.content = editor.getData();
                    this.$refs.input.value = this.content;
                });

                // Listen for media insert events from our custom modal
                window.addEventListener('media-selected', event => {
                    const { url, type, name } = event.detail;
                    if (type === 'image') {
                        editor.execute('insertImage', { source: url });
                    } else {
                        editor.model.change(writer => {
                            const link = writer.createText(name, { linkHref: url });
                            editor.model.insertContent(link, editor.model.document.selection);
                        });
                    }
                });

                console.log('Rich Editor Initialized');
            })
            .catch(error => {
                console.error(error);
            });
    }
}));

Alpine.start();
