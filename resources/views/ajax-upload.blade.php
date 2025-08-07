@extends('layouts.admin')
<form method="POST" action="{{ route('upload.files') }}" enctype="multipart/form-data" onsubmit="return submitFiles(event)">
    @csrf
    <input type="file" id="fileInput" name="files[]" multiple style="display: none;" onchange="handleFileSelect(this.files)">
    
    <div id="dropZone" 
         ondrop="handleDrop(event)" 
         ondragover="handleDragOver(event)" 
         onclick="document.getElementById('fileInput').click()"
         style="border: 2px dashed #999; padding: 20px; text-align: center; cursor: pointer;">
        Drag & Drop Files Here or Click to Select
    </div>

    <div id="preview" style="margin-top: 10px;"></div>

    <button type="submit" style="margin-top: 10px;">Upload</button>
</form>

<script>
let selectedFiles = [];

function handleFileSelect(files) {
    selectedFiles = selectedFiles.concat(Array.from(files));
    updatePreview();
}

function handleDrop(event) {
    event.preventDefault();
    handleFileSelect(event.dataTransfer.files);
}

function handleDragOver(event) {
    event.preventDefault();
}

function updatePreview() {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.style.display = 'inline-block';
            div.style.margin = '5px';

            div.innerHTML = `
                <img src="${e.target.result}" width="100" style="display:block;" />
                <button type="button" onclick="removeFile(${index})">Delete</button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updatePreview();
}

// Handle form submission to send selectedFiles manually
function submitFiles(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData();

    selectedFiles.forEach(file => {
        formData.append('files[]', file);
    });

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        alert('Upload successful');
        selectedFiles = [];
        updatePreview();
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('Upload failed');
    });

    return false;
}
</script>
