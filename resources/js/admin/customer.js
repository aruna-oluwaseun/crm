$(document).ready(function() {
    // Training
    $(document).on('change', '[name="status"]', function(event) {
        var checked = $(this).val();

        if(checked == 'suspended') {
            $('#customer-notes').show();
        } else {
            $('#customer-notes').hide();
        }
    });

    $(document).on('keyup', 'input[name="email"]', function (event) {
        $('#password-email').text($(this).val());
    });

    $(document).on('change', '#change-password', function(event) {
        if($(this).is(':checked')) {
            $('#change-password-form').show();
        } else {
            $('#change-password-form').hide();
        }
    });


    // Update the resource
    $(document).on('submit','#detail-form', function(event) {
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

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message ? response.message : 'Customer updated.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });
                    button.removeAttr('disabled').text('Save');

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
                    var message = 'You have errors in your form. ';
                    $.each( errors, function( key, value ) {
                        $('#modalCreate [name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
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
