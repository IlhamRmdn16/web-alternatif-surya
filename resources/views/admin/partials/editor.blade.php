{{-- Editor seperti MS Word (TinyMCE). Pakai: <textarea name="..." class="rich"> lalu @include('admin.partials.editor') SETELAH textarea. --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    (function () {
        if (typeof tinymce === 'undefined') {
            document.querySelectorAll('textarea.rich').forEach(function (t) { t.style.minHeight = '320px'; });
            alert('Editor gagal dimuat (butuh koneksi internet). Isi tetap bisa diketik sebagai teks/HTML biasa.');
            return;
        }
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        tinymce.init({
            selector: 'textarea.rich',
            license_key: 'gpl',
            height: 560,
            menubar: 'edit insert format table',
            plugins: 'advlist autolink lists link image charmap searchreplace code fullscreen table wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | ' +
                     'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | ' +
                     'link image table | blockquote hr charmap | removeformat | searchreplace code fullscreen',
            block_formats: 'Paragraf=p; Judul 2=h2; Judul 3=h3; Judul 4=h4',
            toolbar_sticky: true,
            branding: false,
            promotion: false,
            image_caption: true,
            automatic_uploads: true,
            paste_data_images: true,
            relative_urls: false,
            remove_script_host: true,
            convert_urls: true,
            content_style: 'body { font-family: "Plus Jakarta Sans", Arial, sans-serif; font-size: 15px; line-height: 1.7; } img { max-width: 100%; height: auto; }',
            images_upload_handler: function (blobInfo) {
                return new Promise(function (resolve, reject) {
                    var fd = new FormData();
                    fd.append('file', blobInfo.blob(), blobInfo.filename());
                    fetch(@json(route('admin.uploads.image')), {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: fd
                    }).then(function (r) {
                        return r.json().then(function (j) { return { ok: r.ok, j: j }; });
                    }).then(function (res) {
                        if (res.ok && res.j.location) resolve(res.j.location);
                        else reject({ message: (res.j.errors && res.j.errors.file ? res.j.errors.file[0] : (res.j.message || 'Gagal mengunggah gambar.')), remove: true });
                    }).catch(function () { reject({ message: 'Gagal mengunggah gambar.', remove: true }); });
                });
            }
        });
    })();
</script>
