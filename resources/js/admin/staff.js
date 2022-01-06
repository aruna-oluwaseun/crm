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


    $(document).on('change', '#change-password', function(event) {
        if($(this).is(':checked')) {
            $('#change-password-form').show();
        } else {
            $('#change-password-form').hide();
        }
    });

});
