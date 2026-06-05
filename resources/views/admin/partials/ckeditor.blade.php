{{-- Lightweight rich-text editor via CKEditor 5 CDN. Falls back to a plain textarea if offline. --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof ClassicEditor === 'undefined') return;
        document.querySelectorAll('textarea.rich-text').forEach(function (el) {
            ClassicEditor.create(el, {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo']
            }).catch(function (e) { console.warn('CKEditor failed:', e); });
        });
    });
</script>
