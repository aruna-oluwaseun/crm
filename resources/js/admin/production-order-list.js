$(document).ready(function() {

    var table = false;
    if($('.datatable-custom tbody tr').length) {
        table = $('.datatable-custom').DataTable(dataTable_options);
    }

    /**
     * New production order
     */
    $(document).on('submit', '#create-form', function(event) {
        event.preventDefault();
        var url = $(this).attr('action');
        var button = $(this).find('.submit-btn');

        button.attr('disabled','disabled').text('...Please wait');

        $.ajax({
            method : 'POST',
            url : url,
            data : $(this).serialize(),
            async : false,
            success : function(response, textStatus, XHR) {
                if(response.success)
                {
                    var id = response.id;

                    if(table) {

                        var row = table.row.add([
                            '<td><a class="id" href="/productionorders/'+ id +'">'+ id +'</a></td>',
                            '<td><div class="user-info"><span class="tb-lead title">' + $('[name="product_id"]').find(':selected').text() + '</span></div></td>',
                            '<td><span class="tb-lead">' + $('[name="qty"]').val() +'</span></td>',
                            '<td><span>' + $('[name="due_date"]').val() + '</span></td>',
                            '<td><span class="tb-status status text-info">Pending</span></td>',
                            '<td></td>'
                        ]).draw().node();

                        // style rows
                        $( row ).find('tr').addClass('nk-tb-item');
                        $( row ).find('td').eq(0).addClass('nk-tb-col');
                        $( row ).find('td').eq(1).addClass('nk-tb-col');
                        $( row ).find('td').eq(2).addClass('nk-tb-col tb-col-mb');
                        $( row ).find('td').eq(3).addClass('nk-tb-col tb-col-lg');
                        $( row ).find('td').eq(4).addClass('nk-tb-col tb-col-md');
                        $( row ).find('td').eq(5).addClass('nk-tb-col nk-tb-col-tools');

                        // re-order table
                        table.order([0, 'desc']).draw();

                        $('#create-form').trigger('reset');
                        $('#modalCreate').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message//'Production order created.',
                            //footer: '<a href>Why do I have this issue?</a>'
                        });
                        button.removeAttr('disabled').text('Save');
                    }
                    else {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message//'Production order created.',
                            //footer: '<a href>Why do I have this issue?</a>'
                        });

                        setTimeout(function() {
                            location.reload();
                        },900)
                    }

                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message ? response.message : 'Something went wrong! please re-fresh your page and try again.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });
                    button.removeAttr('disabled').text('Save');
                }
            },
            error : function(XHR, textStatus, error) {
                if(XHR.status === 422) {
                    var response = XHR.responseJSON;
                    var errors = response.errors;
                    var message = 'You have errors in your form.';
                    $.each( errors, function( key, value ) {
                        $('[name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
                        message += value;
                    });
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message ? message : 'Your session may have expired, please re-fresh your page and try again.',
                    //footer: '<a href>Why do I have this issue?</a>'
                });

                button.removeAttr('disabled').text('Save');
            }
        });
    });

});
