$(document).ready(function() {

    // Keywords
    $('[name=keywords]').tagify();

    // slug
    $(document).on('keyup','[name="title"]', function(event) {
        var title = $(this).val();
        setTimeout(function() {
            var slug = title.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
            $('[name="slug"]').val(slug);
        }, 400);

    });

    // summernote editor
    if($('.summernote-minimal').length) {
        $('.summernote-minimal').summernote({
            placeholder: 'Description',
            tabsize: 2,
            height: 120,
            toolbar: [['style', ['style']], ['font', ['bold', 'underline', 'clear']], ['para', ['ul', 'ol', 'paragraph']], ['table', ['table']], ['view', ['fullscreen']]]
        });
    }

});
