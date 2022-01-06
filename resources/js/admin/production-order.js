$(document).ready(function() {

    $(document).on('change','[name="status"]', function(event) {
        if($(this).val() == 'completed') {
            $('#status-warning').show();
        } else {
            $('#status-warning').hide();
        }
    });

    // Populate form data
    $(document).on('change','#add-product-id', function(event) {
        $('#fetch-product-status').fadeOut(400);
        var selected = $(this).find(':selected').val();
        // fetch product
        $.ajax({
            method: 'GET',
            url : '/get-product',
            data : 'id=' + selected,
            success : function(response) {
                if(response.success) {
                    if(response.data.weight && response.data.unit_of_measure_id) {
                        $('#add-qty').val(response.data.weight);
                    }
                    if(response.data.unit_of_measure_id) {
                        $('#add-unit-of-measure-id').val(response.data.unit_of_measure_id).trigger('change');
                    } else {
                        $('#add-unit-of-measure-id').val('unit').trigger('change');
                    }
                }
                else {
                    $('#fetch-product-status').html('Error fetching the selected product to auto populate the form but the request failed, you can manually fill the form in.').show();
                }
            },
            error : function () {
                $('#fetch-product-status').html('Error fetching the selected product to auto populate the form but the request failed, your session may have expired, please re-fresh and try again.').show();
            }
        })
    });

    // Add build product
    $(document).on('submit', '#create-form', function (event) {
        event.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        var button = $(this).find('.submit-btn');

        button.attr('disabled','disabled').text('...Please wait');

        $.ajax({
            method : method,
            url : url,
            data : $(this).serialize(),
            async : false,
            success : function(response, textStatus, XHR) {
                if(response.success)
                {
                    // add to table
                    if($('#build-products').length) {

                        var clone = $('#build-product-prototype tbody').html();
                        /** @TODO Sort qty display out */
                        clone = clone.replace(/{ID}/g,response.data.id);
                        clone = clone.replace(/{PRODUCT_TITLE}/g,response.data.product_title);
                        clone = clone.replace(/{QTY}/g,response.data.qty);
                        clone = clone.replace(/{PRODUCT_ID}/g,response.data.product_id);
                        $('#build-products tbody').prepend(clone);

                        $('#create-form').trigger('reset');
                        $('#modalCreate').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message ? response.message : 'Build product added.',
                            //footer: '<a href>Why do I have this issue?</a>'
                        });
                    } else {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message ? response.message : 'Build product added.',
                            //footer: '<a href>Why do I have this issue?</a>'
                        });

                        setTimeout(function() {
                            location.reload();
                        },900)
                    }

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
                    var message = 'You have errors in your form.';
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
