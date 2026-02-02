/**
 * Professional Block-Based Article Editor
 * Using Editor.js for structured news content
 */

class ArticleEditor {
    constructor(holderId, data = null) {
        this.holderId = holderId;
        this.editor = null;
        this.data = data;
        this.init();
    }

    init() {
        // Editor.js will be loaded from CDN
        this.editor = new EditorJS({
            holder: this.holderId,

            // Initial data (for editing existing articles)
            data: this.data,

            // Available tools (blocks)
            tools: {
                paragraph: {
                    class: Paragraph,
                    inlineToolbar: ['bold', 'italic', 'link'],
                    config: {
                        placeholder: 'Start writing your article...'
                    }
                },

                header: {
                    class: Header,
                    config: {
                        placeholder: 'Enter a heading',
                        levels: [2, 3],
                        defaultLevel: 2
                    }
                },

                image: {
                    class: ImageTool,
                    config: {
                        endpoints: {
                            byFile: '/admin/upload-article-image',
                        },
                        field: 'image',
                        types: 'image/*',
                        captionPlaceholder: 'Enter image caption (required)',
                        buttonContent: 'Select an image',
                        additionalRequestHeaders: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }
                },

                quote: {
                    class: Quote,
                    inlineToolbar: true,
                    config: {
                        quotePlaceholder: 'Enter a quote',
                        captionPlaceholder: 'Quote author (optional)'
                    }
                }
            },

            // Editor configuration
            placeholder: 'Click here to start writing your article...',
            minHeight: 300,

            // Callbacks
            onChange: (api, event) => {
                console.log('Article content changed', event);
            },

            onReady: () => {
                console.log('Editor.js is ready to work!');
                this.markFirstParagraphAsIntro();
            }
        });
    }

    /**
     * Automatically mark the first paragraph as intro
     */
    async markFirstParagraphAsIntro() {
        const data = await this.editor.save();
        if (data.blocks.length > 0 && data.blocks[0].type === 'paragraph') {
            data.blocks[0].data.isIntro = true;
        }
    }

    /**
     * Save article content
     */
    async save() {
        try {
            const outputData = await this.editor.save();

            // Validate that all images have captions
            const imageBlocks = outputData.blocks.filter(block => block.type === 'image');
            for (let img of imageBlocks) {
                if (!img.data.caption || img.data.caption.trim() === '') {
                    throw new Error('All images must have captions');
                }
            }

            // Mark first paragraph as intro
            if (outputData.blocks.length > 0 && outputData.blocks[0].type === 'paragraph') {
                outputData.blocks[0].data.isIntro = true;
            }

            return outputData;
        } catch (error) {
            console.error('Saving failed: ', error);
            throw error;
        }
    }

    /**
     * Destroy editor instance
     */
    destroy() {
        if (this.editor) {
            this.editor.destroy();
        }
    }
}

// Export for use in other scripts
window.ArticleEditor = ArticleEditor;
