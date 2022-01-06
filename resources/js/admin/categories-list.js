$(document).ready(function() {

    // Keywords
    $('[name=keywords]').tagify();

    // slug
    $(document).on('keyup','#modalCreateCategory [name="title"]', function(event) {
        var title = $(this).val();
        setTimeout(function() {
            var slug = title.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
            $('#modalCreateCategory [name="slug"]').val(slug);
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

    /**
     * Manage product stats
     */
    $(document).on('click', '.btn-action', function(event) {
        event.preventDefault();
        var button = $(this);
        var data_box = $(this).parents('.data-container');
        var action = $(this).data('action');
        var url = $(this).attr('href');
        var id = data_box.data('id');

        var active = '<a href="/category-status" class="btn btn-trigger btn-action btn-icon" data-action="active" data-toggle="tooltip" data-placement="top" title="Enable category"><em class="icon ni ni-check-thick"></em></a>';
        var disable = '<a href="/category-status" class="btn btn-trigger btn-action btn-icon" data-action="disabled" data-toggle="tooltip" data-placement="top" title="Disable category"><em class="icon ni ni-cross"></em></a>';

        $.ajax({
            method : 'GET',
            url : url,
            data : 'status=' + action + '&id='+id+'&_token='+ $('meta[name="csrf-token"]').attr('content'),
            async : false,
            success : function(data, textStatus, XHR) {
                if(data.success)
                {
                    if( action == 'active' ) {
                        $('[data-id="'+id+'"]').find('.tb-status').removeClass('text-warning').addClass('text-success').text('Active');
                        data_box.find('.status-result').html(disable);

                    } else {
                        $('[data-id="'+id+'"]').find('.tb-status').removeClass('text-success').addClass('text-warning').text('Disabled');
                        data_box.find('.status-result').html(active);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Category status updated.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });
                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message ? data.message : 'Something went wrong! please re-fresh your page and try again.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });

                }
            },
            error : function(XHR, textStatus, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Your session may have expired, please re-fresh your page and try again.',
                    //footer: '<a href>Why do I have this issue?</a>'
                });
            }
        });
    });


});
