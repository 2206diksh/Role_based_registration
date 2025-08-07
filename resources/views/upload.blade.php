<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AJAX File Upload with Preview</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .file-preview {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Upload Multiple Files</h2>

    <input type="file" id="fileInput" multiple />
    <div id="previewContainer"></div>

    <script>
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    let fileList = [];

    fileInput.addEventListener('change', () => {
        const files = Array.from(fileInput.files);
        files.forEach(file => {
            const fileId = `${file.name}-${Date.now()}`;
            const fileData = {
                id: fileId,
                file,
                uploaded: false,
                serverPath: null,
            };
            fileList.push(fileData);
            showPreview(fileData);
            uploadFile(fileData);
        });
        fileInput.value = ''; // Reset input to allow same file re-selection
    });

    function showPreview(fileData) {
        const wrapper = document.createElement('div');
        wrapper.id = fileData.id;
        wrapper.className = 'file-preview';

        const fileName = document.createElement('span');
        fileName.textContent = fileData.file.name;

        const status = document.createElement('span');
        status.textContent = ' (Uploading...)';
        status.className = 'status';

        const delBtn = document.createElement('button');
        delBtn.textContent = 'Delete';
        delBtn.style.marginLeft = '10px';
        delBtn.onclick = () => deleteFile(fileData);

        wrapper.appendChild(fileName);
        wrapper.appendChild(status);
        wrapper.appendChild(delBtn);

        previewContainer.appendChild(wrapper);
    }

    function uploadFile(fileData) {
        const formData = new FormData();
        formData.append('file', fileData.file);

        fetch('/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        }).then(res => res.json())
          .then(data => {
            fileData.uploaded = true;
            fileData.serverPath = data.path;
            document.querySelector(`#${fileData.id} .status`).textContent = ' (Uploaded)';
          })
          .catch(() => {
            document.querySelector(`#${fileData.id} .status`).textContent = ' (Upload failed)';
          });
    }

    function deleteFile(fileData) {
        // Remove from preview
        const el = document.getElementById(fileData.id);
        if (el) el.remove();

        // Remove from list
        fileList = fileList.filter(f => f.id !== fileData.id);

        // If already uploaded, send delete request to server
        if (fileData.uploaded && fileData.serverPath) {
            fetch('/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ path: fileData.serverPath })
            }).then(() => {
                console.log(`Deleted ${fileData.serverPath}`);
            });
        }
    }
    </script>
</body>
</html>
