document.addEventListener("DOMContentLoaded", function () {
    const contentArea = document.querySelector('.prose'); // Target the article content area using Tailwind typography class
    if (!contentArea) return;

    const links = contentArea.querySelectorAll('a');

    links.forEach(link => {
        const url = link.href;
        const extension = url.split('.').pop().toLowerCase();
        const videoExts = ['mp4', 'webm', 'ogg', 'mov'];
        const audioExts = ['mp3', 'wav', 'm4a'];

        if (videoExts.includes(extension)) {
            // Create Video Player
            const video = document.createElement('video');
            video.src = url;
            video.controls = true;
            video.style.maxWidth = '100%';
            video.style.borderRadius = '8px';
            video.style.marginTop = '20px';
            video.style.marginBottom = '20px';
            video.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';

            link.parentNode.replaceChild(video, link);
        } else if (audioExts.includes(extension)) {
            // Create Audio Player
            const audio = document.createElement('audio');
            audio.src = url;
            audio.controls = true;
            audio.style.width = '100%';
            audio.style.marginTop = '10px';
            audio.style.marginBottom = '10px';

            link.parentNode.replaceChild(audio, link);
        }
    });
});
