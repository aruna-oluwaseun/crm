$(document).ready(function() {

    if(show_email_invoice_modal) {
        $('#modalInvoiceAction').modal('show');
    }

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

    // change tab
    $(document).on('click', '.tab-links li a', function(event) {
        event.preventDefault();
        $('.tab-links li a').removeClass('active');
        $('.tab').hide();
        $(this).addClass('active');
        $($(this).attr('href')).show();
        var href = window.location.href.replace(/#.*$/,'') + $(this).attr('href');
        window.history.pushState({href: href}, '', href);
    });

    // Show tab on page load
    if($(location).attr('hash').length)  {
        $('.tab-links li a[href="'+$(location).attr('hash')+'"]').trigger('click');
    }

});
