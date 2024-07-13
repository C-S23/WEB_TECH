document.addEventListener('DOMContentLoaded', () => {
    const showModal = (modal) => modal.style.display = 'block';
    const hideModal = (modal) => modal.style.display = 'none';

    document.getElementById('uploadButton').addEventListener('click', () => showModal(document.getElementById('uploadModal')));
    document.getElementById('downloadButton').addEventListener('click', () => showModal(document.getElementById('downloadModal')));

    document.querySelectorAll('.close').forEach(btn => btn.addEventListener('click', () => {
        hideModal(document.getElementById('uploadModal'));
        hideModal(document.getElementById('downloadModal'));
    }));

    window.addEventListener('click', (event) => {
        if (event.target === document.getElementById('uploadModal')) hideModal(document.getElementById('uploadModal'));
        if (event.target === document.getElementById('downloadModal')) hideModal(document.getElementById('downloadModal'));
    });

    document.querySelectorAll('.card').forEach(card => card.addEventListener('click', () => {
        console.log('Clicked on card with filename:', card.dataset.filename);
    }));

    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('upload.php', { method: 'POST', body: formData })
            .then(response => response.text())
            .then(data => {
                alert(data);
                hideModal(document.getElementById('uploadModal'));
            })
            .catch(error => {
                alert('Error uploading file.');
                console.error('Error:', error);
            });
    });

    document.getElementById('downloadForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const filename = document.getElementById('filename').value;
        fetch(`download.php?filename=${encodeURIComponent(filename)}`)
            .then(response => response.blob())
            .then(blob => {
                if (blob.size === 0) {
                    alert('File not found.');
                } else {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                }
            })
            .catch(error => {
                alert('Error downloading file.');
                console.error('Error:', error);
            });
    });
});


