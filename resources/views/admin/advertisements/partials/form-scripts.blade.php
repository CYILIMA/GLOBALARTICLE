<script>
    function previewImage(input) {
        const preview     = document.getElementById('image-preview');
        const placeholder = document.getElementById('upload-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.querySelectorAll('.status-card input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.status-card').forEach(c => {
                c.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                c.classList.add('border-gray-200', 'dark:border-gray-600');
            });
            radio.closest('.status-card').classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            radio.closest('.status-card').classList.remove('border-gray-200', 'dark:border-gray-600');
        });
    });

    document.querySelectorAll('.tier-card input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const isPremium = radio.value === 'premium';
            document.querySelectorAll('.tier-card').forEach(c => {
                c.classList.remove(
                    'border-yellow-500', 'bg-yellow-50', 'dark:bg-yellow-900/20',
                    'border-blue-500',   'bg-blue-50',   'dark:bg-blue-900/20'
                );
                c.classList.add('border-gray-200', 'dark:border-gray-600');
            });
            const card = radio.closest('.tier-card');
            card.classList.remove('border-gray-200', 'dark:border-gray-600');
            card.classList.add(...(isPremium
                ? ['border-yellow-500', 'bg-yellow-50',  'dark:bg-yellow-900/20']
                : ['border-blue-500',   'bg-blue-50',    'dark:bg-blue-900/20']
            ));
        });
    });

    // Dimension hints per position
    const dimensionHints = {
        header_leaderboard: 'Landscape required — recommended: 1200×250 or 970×250 (min width > height)',
        sidebar_rectangle:  'Portrait or square OK — recommended: 300×250 or 300×600',
        in_article:         'Landscape required — recommended: 728×90 or 970×250 (min width > height)',
        footer_banner:      'Landscape required — recommended: 970×90 or 970×250 (min width > height)',
    };

    function updateDimensionHint() {
        const posSelect = document.querySelector('select[name="position"]');
        const hintEl    = document.getElementById('dimension-hint');
        if (!posSelect || !hintEl) return;
        const hint = dimensionHints[posSelect.value] || '';
        hintEl.textContent = hint ? '📐 ' + hint : '';
        hintEl.style.display = hint ? '' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const posSelect = document.querySelector('select[name="position"]');
        if (posSelect) {
            posSelect.addEventListener('change', updateDimensionHint);
            updateDimensionHint();
        }
    });
</script>
