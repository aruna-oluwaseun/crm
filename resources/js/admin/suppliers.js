$(document).ready(function() {

    $('.copy-address-to-shipping').on('click', function (e) {
        e.preventDefault();
        var parent = $(this).parents('.address'); //#shipping-address-details
        //#billing-address-details

        $('[name="head_office_address_data[line1]"]').val(parent.find('[name="billing_address_data[line1]"]').val());
        $('[name="head_office_address_data[line2]"]').val(parent.find('[name="billing_address_data[line2]"]').val());
        $('[name="head_office_address_data[line3]"]').val(parent.find('[name="billing_address_data[line3]"]').val());
        $('[name="head_office_address_data[city]"]').val(parent.find('[name="billing_address_data[city]"]').val());
        $('[name="head_office_address_data[postcode]"]').val(parent.find('[name="billing_address_data[postcode]"]').val());
        $('[name="head_office_address_data[county]"]').val(parent.find('[name="billing_address_data[county]"]').val());
        $('[name="head_office_address_data[country]"]').val(parent.find('[name="billing_address_data[country]"]').val());
        $('[name="head_office_address_data[lat]"]').val(parent.find('[name="billing_address_data[lat]"]').val());
        $('[name="head_office_address_data[lng]"]').val(parent.find('[name="billing_address_data[lng]"]').val());
    });
    $('.copy-address-to-billing').on('click', function (e) {
        e.preventDefault();
        var parent = $(this).parents('.address'); //#shipping-address-details
        //#billing-address-details

        $('[name="billing_address_data[line1]"]').val(parent.find('[name="head_office_address_data[line1]"]').val());
        $('[name="billing_address_data[line2]"]').val(parent.find('[name="head_office_address_data[line2]"]').val());
        $('[name="billing_address_data[line3]"]').val(parent.find('[name="head_office_address_data[line3]"]').val());
        $('[name="billing_address_data[city]"]').val(parent.find('[name="head_office_address_data[city]"]').val());
        $('[name="billing_address_data[postcode]"]').val(parent.find('[name="head_office_address_data[postcode]"]').val());
        $('[name="billing_address_data[county]"]').val(parent.find('[name="head_office_address_data[county]"]').val());
        $('[name="billing_address_data[country]"]').val(parent.find('[name="head_office_address_data[country]"]').val());
        $('[name="billing_address_data[lat]"]').val(parent.find('[name="head_office_address_data[lat]"]').val());
        $('[name="billing_address_data[lng]"]').val(parent.find('[name="head_office_address_data[lng]"]').val());
    }); // change billing address

    $('#change-billing-address').click(function (e) {
        e.preventDefault();
        $('.new-billing-address').toggle();
    }); // change billing address

    $('#change-delivery-address').click(function (e) {
        e.preventDefault();
        $('.new-delivery-address').toggle();
    }); // show items on invo

});
