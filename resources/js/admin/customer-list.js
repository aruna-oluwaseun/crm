$(document).ready(function() {
    $('#modalSuspend').on('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var data_box = $(button).parents('.data-container');
        var id = data_box.data('id');
        var name = data_box.find('.customer-name').val();
        var notes = data_box.find('.customer-notes').val();

        $('#modalSuspend h5.modal-title span').text(name).addClass('text-primary');
        $('#modalSuspend #id').val(id);
        $('#modalSuspend #notes').val(notes);
        setTimeout(function () {
            $('#modalSuspend #notes').focus();
        }, 800)
    })

    /**/
    $(document).on('keyup', '#add-email', function (event) {
        $(this).removeClass('error');
        if( $('#add-email-error').length) {
            $('#add-email-error').remove();
        }
        if( $('#add-email-success').length) {
            $('#add-email-success').remove();
        }
        $('#create-form .submit-btn').removeAttr('disabled');
        $('#password-email').text($(this).val());
    });

    /**
     * Check customer email doesnt exist
     */
    $(document).on('focusout', '#add-email', function(event) {
        // Check the email
        if($(this).val() != '')
        {
            $.ajax({
                method: 'GET',
                url : '/check-customer-email',
                data : 'email=' + $('#add-email').val(),
                success : function(records)
                {
                    if(records > 0)
                    {
                        $('#create-form .submit-btn').attr('disabled', 'disabled');
                        $('#add-email').addClass('error');
                        $('#add-email').parent().append('<span id="add-email-error" class="invalid">This email already exists please change it.</span>');
                    }
                    else
                    {
                        $('#add-email').addClass('success');
                        $('#add-email').parent().append('<small id="add-email-success"  class="text-success">No customer uses this email so you are good to go!</span>');

                    }
                },
                error : function(XHR, textStatus, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Your session may have expired, checks cannot be carried out to determine if the already exists.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });
                    button.removeAttr('disabled').text('Save');
                }
            })
        }

    });

    /**
     * Suspend user
     */
    $(document).on('submit', '#suspend-form', function(event) {
        event.preventDefault();
        var url = $(this).attr('action');
        var button = $(this).find('.submit-btn');
        var id = $('#modalSuspend #id').val();

        var suspend = '<a href="#" class="btn btn-icon" data-toggle="modal" data-target="#modalSuspend" data-toggle="tooltip" data-placement="top" title="Suspend Customer"><em class="icon ni ni-user-cross-fill"></em></a>'
        var activate = '<a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Re-activate Customer"><em class="icon ni ni-user-check-fill"></em></a>';

        button.attr('disabled','disabled').text('...Please wait');

        $.ajax({
            method : 'POST',
            url : url,
            data : $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            async : false,
            success : function(data, textStatus, XHR) {
                if(data.success)
                {
                    $('[data-id="'+id+'"]').find('.status-result').html(activate);
                    $('[data-id="'+id+'"]').find('.tb-status').removeClass('text-success').addClass('text-danger').text('Suspended');
                    $('#modalSuspend').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User has been suspended.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });
                    button.removeAttr('disabled').text('Save');
                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message ? data.message : 'Something went wrong! please re-fresh your page and try again.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });
                    button.removeAttr('disabled').text('Save');
                }
            },
            error : function(XHR, textStatus, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Your session may have expired, please re-fresh your page and try again.',
                    //footer: '<a href>Why do I have this issue?</a>'
                });
                button.removeAttr('disabled').text('Save');
            }
        });
    });

    /**
     * Re-activate user
     */
    $(document).on('click', '.btn-trigger', function(event) {
        event.preventDefault();
        var data_box = $(this).parents('.data-container');
        var url = $(this).attr('href');
        var id = data_box.data('id');

        var activate = '<a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Re-activate Customer"><em class="icon ni ni-user-check-fill"></em></a>';

        $.ajax({
            method : 'POST',
            url : url,
            data : '_method=PUT&status=active&id='+id+'&_token='+ $('meta[name="csrf-token"]').attr('content'),
            async : false,
            success : function(data, textStatus, XHR) {
                if(data.success)
                {
                    $('[data-id="'+id+'"]').find('.status-result').html(activate);
                    $('[data-id="'+id+'"]').find('.tb-status').removeClass('text-danger').addClass('text-success').text('Active');
                    $('#modalSuspend').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User has been re-activated.',
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
