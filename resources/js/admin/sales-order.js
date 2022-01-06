$(document).ready(function() {

    if(show_email_invoice_modal) {
        $('#modalInvoiceAction').modal('show');
    }

    // copy address to shipping
    $('.copy-address-to-shipping').on('click', function (e) {
        e.preventDefault();
        var parent = $(this).parents('.address');
        //#shipping-address-details
        //#billing-address-details
        $('[name="delivery_address_data[title]"]').val(parent.find('[name="billing_address_data[title]"]').val());
        $('[name="delivery_address_data[line1]"]').val(parent.find('[name="billing_address_data[line1]"]').val());
        $('[name="delivery_address_data[line2]"]').val(parent.find('[name="billing_address_data[line2]"]').val());
        $('[name="delivery_address_data[line3]"]').val(parent.find('[name="billing_address_data[line3]"]').val());
        $('[name="delivery_address_data[city]"]').val(parent.find('[name="billing_address_data[city]"]').val());
        $('[name="delivery_address_data[postcode]"]').val(parent.find('[name="billing_address_data[postcode]"]').val());
        $('[name="delivery_address_data[county]"]').val(parent.find('[name="billing_address_data[county]"]').val());
        $('[name="delivery_address_data[country]"]').val(parent.find('[name="billing_address_data[country]"]').val());
        $('[name="delivery_address_data[lat]"]').val(parent.find('[name="billing_address_data[lat]"]').val());
        $('[name="delivery_address_data[lng]"]').val(parent.find('[name="billing_address_data[lng]"]').val());
    });

    $('.copy-address-to-billing').on('click', function (e) {
        e.preventDefault();
        var parent = $(this).parents('.address');
        //#shipping-address-details
        //#billing-address-details
        $('[name="billing_address_data[title]"]').val(parent.find('[name="delivery_address_data[title]"]').val());
        $('[name="billing_address_data[line1]"]').val(parent.find('[name="delivery_address_data[line1]"]').val());
        $('[name="billing_address_data[line2]"]').val(parent.find('[name="delivery_address_data[line2]"]').val());
        $('[name="billing_address_data[line3]"]').val(parent.find('[name="delivery_address_data[line3]"]').val());
        $('[name="billing_address_data[city]"]').val(parent.find('[name="delivery_address_data[city]"]').val());
        $('[name="billing_address_data[postcode]"]').val(parent.find('[name="delivery_address_data[postcode]"]').val());
        $('[name="billing_address_data[county]"]').val(parent.find('[name="delivery_address_data[county]"]').val());
        $('[name="billing_address_data[country]"]').val(parent.find('[name="delivery_address_data[country]"]').val());
        $('[name="billing_address_data[lat]"]').val(parent.find('[name="delivery_address_data[lat]"]').val());
        $('[name="billing_address_data[lng]"]').val(parent.find('[name="delivery_address_data[lng]"]').val());
    });

    // change billing address
    $('#change-billing-address').click(function(e) {
        e.preventDefault();
        $('.new-billing-address').toggle();
    });

    // change billing address
    $('#change-delivery-address').click(function(e) {
        e.preventDefault();
        $('.new-delivery-address').toggle();
    });

    // show items on invoice
    $('#modalInvoiceItems').on('shown.bs.modal', function (e) {
        var button = e.relatedTarget;
        var invoice_id = $(button).data('invoice-id');

        $('#modalInvoiceItems .modal-header span').val(invoice_id);
        $('#modalInvoiceItems .items-on-invoice').hide();
        $('#modalInvoiceItems #items-on-invoice-'+invoice_id).show();
    });

    // Email an invoice
    $('#modalEmailInvoice').on('shown.bs.modal', function (e) {
        var button = e.relatedTarget;
        var invoice_id = $(button).data('invoice-id');

        $('#modalEmailInvoice [name="invoice_id"]').val(invoice_id);
    });

    // when invoicing allow immediate send of email
    $(document).on('click', '#email-invoice', function() {
        if($(this).is(':checked')) {
            $('#email-for-invoice').show();
            $('#email-for-invoice input').removeAttr('disabled');
        } else {
            $('#email-for-invoice').hide();
            $('#email-for-invoice input').attr('disabled','disabled');
        }
    });

    $(document).on('click','.checkbox-item-all, .checkbox-item', function() {
        if($(this).hasClass('checkbox-item-all'))
        {
            if($(this).is(':checked')) {
                // uncheck all
                $('#dispatch-items-form tbody input.checkbox-item').prop('checked', true);
            }
            else {
                // check all
                $('#dispatch-items-form tbody input.checkbox-item').prop('checked', false);
            }
        }

        var checked = $('#dispatch-items-form tbody input.checkbox-item:checked').length;

        if(checked) {
            $('#dispatch-btn').removeClass('disabled').removeAttr('disabled');
        } else {
            $('#dispatch-btn').addClass('disabled').attr('disabled','disabled');
        }
    });

    $(document).on('click', '#additional-shipping', function() {
        if($(this).is(':checked')) {
            $('#additional-shipping-details').show();
            $('#add-additional-shipping-cost').removeAttr('disabled');
        } else {
            $('#additional-shipping-details').hide();
            $('#add-additional-shipping-cost').attr('disabled','disabled');
        }
    });

    $(document).on('click', '#dispatch-is-collection', function() {
        if($(this).is(':checked')) {
            $('.collection-details').show();
            $('.shipping-details').hide();
            $('#dispatch-courier-title').attr('disabled','disabled');
        } else {
            $('.collection-details').hide();
            $('.shipping-details').show();
            $('#dispatch-courier-title').removeAttr('disabled');
        }
    });

    $(document).on('change', '#dispatch-courier-id', function() {
        var selected = $(this).find(':selected').val();

        if(selected == 'new') {
            $('#dispatch-courier-title').removeAttr('disabled');
            $('#dispatch-new-courier').show();
        } else {
            $('#dispatch-courier-title').attr('disabled','disabled');
            $('#dispatch-new-courier').hide();
        }
    });

    // add pallet or box row
    $(document).on('click', '.btn-add-dispatch-row', function(e) {
        e.preventDefault();
        var parent = $(this).parents('.card');
        var container = parent.find('.dispatch-block');
        var row = container.find('.row:first');
        var select_name = container.find('select').attr('name');
        var qty_name = container.find('input[type=number]').attr('name');
        var id = new Date().getTime();

        var select = '<option val="">No results found</option>';

        if($(row).hasClass('box-row')) {
            if(typeof boxes != "undefined") {
                select = '';
                $(boxes).each(function(key,value){
                    select+= '<option value="'+value.id+'">'+value.title+'</option>'
                });
            }
        } else {
            if(typeof pallets != "undefined") {
                select = '';
                $(pallets).each(function(key,value){
                    select+= '<option value="'+value.id+'">'+value.title+'</option>'
                });
            }
        }

        var html = '<div class="row mb-2"><div class="col-md-8">';
            html += '<div class="form-control-wrap">';
            html += '<select class="form-select" name="'+ select_name +'" id="'+ id +'-add-pallet" data-search="on" required>' + select + '</select></div></div>';
            html += '<div class="col-md-4"><div class="form-control-wrap"><input type="number" name="'+ qty_name +'" class="form-control" value="0" min="0"></div>';
            html += '</div></div>';

        container.append(html);
        container.find('select:not(.select2)').select2();
    });

    /*
     * Get what we want to dispatch
     */
    $('#modalDispatch').on('shown.bs.modal', function () {

        $('.loading').show();
        $('#load-dispatch-items').hide();

        $.ajax({
            url : $('#dispatch-items-form').attr('action'),
            method : 'POST',
            data : $('#dispatch-items-form').serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success : function(response) {
                if(response.success)
                {
                    var html = '';
                    $.each(response.data, function(key, value) {
                        if( value.qty === 0 ) {
                            // All items have shipped
                            html += '<tr><td colspan="3">' + value.product_title + ' cannot be dispatched / collected, all items have been dispatched / collected</td></tr>';
                        } else {
                            html += '<tr><td>' + value.product_title + '</td><td>' + value.sent + '</td><td><input name="dispatch_items[' + value.sales_order_item_id + ']" type="number" min="1" max="' + value.qty + '" class="form-control" value="' + value.qty + '"></td></tr>';
                        }
                    });
                    $('#load-dispatch-items tbody').html(html);
                    $('.loading').hide();
                    $('#load-dispatch-items').show();
                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message ? response.message : 'We could not retrieve the items you wish to dispatch.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });
                }
            },
            error : function(XHR,textStatus,error)
            {
                if(XHR.status === 422) {
                    var response = XHR.responseJSON;
                    var errors = response.errors;
                    var message = 'You have errors in your form.';
                    $.each( errors, function( key, value ) {
                        $('#edit-form [name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
                        message += value;
                    });
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message ? message : 'Your session may have expired, please re-fresh your page and try again.',
                    //footer: '<a href>Why do I have this issue?</a>'
                });
            }
        });
    })

    $(document).on('change','#sales-order-status-id', function(event) {
        var selected = $(this).find(':selected').val();

        if(selected == 1 || selected == 2) {
            $('#status-warning').hide();
            $('#cancellation-reason').hide();
            $('#cancelled-reason').removeAttr('required');
        }
        else if(selected == 8) {
            $('#cancellation-reason').show();
            $('#cancelled-reason').attr('required','required');
        }
        else {
            $('#status-warning').show();
            $('#cancellation-reason').hide();
            $('#cancelled-reason').removeAttr('required');
        }
    });

    $(document).on('change','#add-none-stock-item', function() {
        if($(this).is(':checked'))
        {
            $('#add-product-id').attr('disabled','disabled');
            $('#select-product').hide();
            $('#add-product-title').removeAttr('disabled');
            $('#typein-product').show();
            $('#add-item-weight').show();
            $('#add-paying-deposit-box').hide();
            $('#add-qty').removeAttr('readonly');
            $('#add-training-box').hide();
        }
        else
        {
            $('#add-product-id').removeAttr('disabled');
            $('#select-product').show();
            $('#add-product-title').attr('disabled','disabled');
            $('#typein-product').hide();
            $('#add-item-weight').hide();

            if($('#add-product-id').find(':selected').data('is-training'))
            {
                $('#add-qty').val(1).attr('readonly','readonly');
                $('#add-paying-deposit-box').show();
                $('#add-training-box').show();
            }

        }
    });

    // Shipping Cost
    $(document).on('change focusout', '#add-additional-shipping-cost, #add-additional-shipping-vat', function(event) {
        var vat_value = $('#add-additional-shipping-vat').find(':selected').data('value');
        // Get the inputed item cost
        var item_cost = parseFloat(($('#add-additional-shipping-cost').val() ? $('#add-additional-shipping-cost').val() : 0));

        var vat = (item_cost * vat_value) / 100;
        //$('#add-additional-shipping-vat-cost').val(vat.toFixed(2));

        var gross = item_cost + vat;
        $('#add-additional-shipping-gross-cost').val(gross.toFixed(2));

    });

    // Costings
    $(document).on('change focusout', '#add-item-cost, #add-vat-type-id, #add-qty', function(event) {
        $('#add-discount-cost').val(0);
        $('#sale-saving').hide();

        var vat_value = $('#add-vat-type-id').find(':selected').data('value');
        var qty = $('#add-qty').val();

        // Per item costings
        var original_net_cost = parseFloat($('#add-original-net-cost').val()) * qty;
        var original_sale_cost = parseFloat($('#add-original-sale-cost').val()) * qty;

        // Get the inputed item cost
        var item_cost = parseFloat(($('#add-item-cost').val() ? $('#add-item-cost').val() : 0));

        var net_cost = item_cost * qty;
        $('#add-net-cost').val(net_cost.toFixed(2));

        var vat = (net_cost * vat_value) / 100;
        $('#add-vat-cost').val(vat.toFixed(2));

        var gross = net_cost + vat;
        $('#add-gross-cost').val(gross.toFixed(2));

        console.log('The net original cost = ' + original_net_cost + ' and the original sale cost = ' + original_sale_cost + ' the net cost is = ' + net_cost);

        if(original_sale_cost != 0)
        {
            calculate_saving(original_sale_cost);
        }

        if(original_net_cost > net_cost)
        {
            calculate_saving(original_net_cost);
        }

        function calculate_saving(sale_price)
        {
            console.log('saving triggered')

            $('#add-discount-cost').val((sale_price-net_cost).toFixed(2));

            var saving = (net_cost / sale_price) * 100;
            saving = 100-saving;
            $('#sale-saving mark').text(saving.toFixed(2) + '%').removeClass('text-danger');
            $('#sale-saving').show();
            if(saving < 0) {
                $('#sale-saving mark').text(saving.toFixed(2) + '% this is not a saving').addClass('text-danger');
            }
        }

    });

    $(document).on('click','#add-paying-deposit', function() {
        var net = $(this).data('deposit-net');
        var original = $(this).data('original-net');

        if($(this).is(':checked')) {
            $('#add-item-cost').val(net).change();
        } else {
            $('#add-item-cost').val(original).change();
        }

    });

    // Add item
    $(document).on('change','#add-product-id', function(event) {
        $('#fetch-product-status').fadeOut(400);
        var selected = $(this).find(':selected').val();
        var qty = $('#add-qty');

        // Training box
        var is_training =  $(this).find(':selected').data('is-training');
        var training_container = $('#add-training-box');
        $(training_container).hide();

        if(is_training) {
            $(qty).val(1);
            $(qty).attr('readonly','readonly');
        } else {
            $(qty).removeAttr('readonly');
        }

        // fetch product
        $.ajax({
            method: 'GET',
            url : '/get-product',
            data : 'id=' + selected,
            success : function(response) {
                if(response.success) {

                    $('#add-item-cost').val(0);
                    $('#add-original-net-cost').val(0);
                    $('#add-original-sale-cost').val(0);
                    $('#add-qty').trigger('change');

                    if(response.data.net_cost) {
                        $('#add-item-cost').val(response.data.net_cost);
                        $('#add-original-net-cost').val(response.data.net_cost);

                        $('#original-cost mark').text('£'+response.data.net_cost).addClass('text-danger')
                        $('#original-cost').show();
                    }

                    if(response.data.sale_cost) {
                        $('#add-item-cost').val(response.data.sale_cost);
                        $('#add-original-sale-cost').val(response.data.sale_cost);
                    }

                    if(response.data.vat_type_id) {
                        $('#add-vat-type-id').val(response.data.vat_type_id).trigger('change');
                    }

                    if(response.data.deposit_allowed)
                    {
                        var deposit_checkbox = '<div class="custom-control custom-checkbox">'
                            + '<input data-original-net="'+ response.data.net_cost +'" data-deposit-net="'+ response.data.deposit_net_cost +'" data-deposit-gross="'+ response.data.deposit_gross_cost +'" data-deposit-vat="' + response.data.deposit_vat_cost + '" name="paying_deposit" type="checkbox" class="custom-control-input" id="add-paying-deposit" value="1">'
                            +'<label class="custom-control-label" for="add-paying-deposit"> Paying deposit?</label></div>';

                        $('#add-paying-deposit-box').html(deposit_checkbox).show();
                    }
                    else
                    {
                        $('#add-paying-deposit-box').html('').hide();
                    }

                    if(is_training)
                    {

                        training_container.html(loader);
                        training_container.show();

                        $.ajax({
                            method: 'GET',
                            url : '/get-training-dates',
                            data : 'id=' + selected,
                            success : function(response) {

                                //debug(response)
                                if(response.success) {

                                    var html = '<div class="form-control-wrap">';
                                    html += '<select name="training_date_id" class="training-dates form-control" data-search="on" required>';

                                    $(response.data).each(function(key, item) {
                                        html += '<option value="' + item.id + '">' + (item.formated_date ? item.formated_date : item.date)  + '</option>';
                                    });

                                    html += '</select></div>';
                                    html += '<div class="form-control-wrap"><input class="form-control attendee mt-3" type="text" name="attendee" placeholder="Attendee"></div>'

                                    training_container.html(html);
                                    $('.training-dates').select2();
                                }
                                else
                                {
                                    training_container.html('<div class="alert alert-warning">There are no available dates for this user.</div>');
                                }
                            },
                            error : function ()
                            {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Not found',
                                    text: 'Failed to find training data.',
                                    //footer: '<a href>Why do I have this issue?</a>'
                                });
                                $(training_container).hide();
                            }
                        });
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

    // Update the product
    /*$(document).on('submit','#edit-form', function(event) {
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
                        text: response.message ? response.message : 'Sales order updated.',
                        //footer: '<a href>Why do I have this issue?</a>'
                    });

                    if($('#is-urgent').is(':checked')) {
                        $('#sales-order-urgent').show();
                    } else {
                        $('#sales-order-urgent').hide();
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
                        $('#edit-form [name="'+ key +'"]').parent().append('<span id="'+ key +'-error" class="invalid">This field is required.</span>');
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
    });*/

});
