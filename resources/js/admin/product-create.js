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

    // Training
    $(document).on('change', '#is-training', function(event) {
        if($(this).is(':checked')) {
            $('#assessment-product').show();
            $('#free-shipping').prop('checked','checked');
        } else {
            $('#assessment-product').hide();
            $('#free-shipping').prop('checked',false);
        }
    });

    // Pallet or Box
    $(document).on('change', '#is-packaging', function(event) {
        if($(this).is(':checked')) {
            $('#packaging-options').show();
            $('#product-options').hide();
        } else {
            $('#packaging-options').hide();
            $('#product-options').show();
        }
    });

    $(document).on('change', '#is-shipping-box', function(event) {
        if($(this).is(':checked')) {
            $('#is-shipping-pallet').prop('checked',false);
        }
    });

    $(document).on('change', '#is-shipping-pallet', function(event) {
        if($(this).is(':checked')) {
            $('#is-shipping-box').prop('checked',false);
        }
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

    // Sale costings
    if($('#sale-net-cost').length) {

        setTimeout(function() {
            $('#sale-net-cost').trigger('change');
        },400);


        $(document).on('change keyup', '#sale-net-cost, #vat-type-id', function(event) {
            var net_cost = parseFloat($('#sale-net-cost').val());
            var vat_value = $('#vat-type-id').find(':selected').data('value');

            // Update costings
            if($('#sale-net-cost').val() != '') {
                if($('#vat-type-id').length && $('#sale-vat-cost').length && $('#sale-gross-cost').length) {
                    var vat = net_cost * vat_value;
                    var vat_cost = vat / 100;
                    var gross_cost = net_cost + vat_cost;

                    vat_cost = vat_cost.toFixed(2);
                    gross_cost = gross_cost.toFixed(2);

                    $('#sale-vat-cost').val(vat_cost);
                    $('#sale-gross-cost').val(gross_cost);

                    // calculate saving
                    var cost_net = parseFloat($('#net-cost').val());
                    var sale_net = parseFloat($('#sale-net-cost').val());
                    var saving = (sale_net / cost_net) * 100;
                    saving = 100-saving;
                    $('#sale-saving mark').text(saving.toFixed(2) + '%').removeClass('text-danger');
                    $('#sale-saving').show();
                    if(saving < 0) {
                        $('#sale-saving mark').text(saving.toFixed(2) + '% this is not a saving').addClass('text-danger');
                    }
                }
            } else {
                $('#sale-saving').hide();
                $('#sale-vat-cost').val('');
                $('#sale-gross-cost').val('');
            }

        });
    }

    // Deposit allowed
    $(document).on('change', '#deposit-allowed', function() {
        if($(this).is(':checked')) {
            $('#deposit-costings').show();
        } else {
            $('#deposit-costings').hide();
        }
    });

    // Update costings
    if($('#deposit-net-cost').length) {
        $(document).on('change', '#deposit-net-cost, #vat-type-id', function(event) {
            var net_cost = parseFloat($('#deposit-net-cost').val());
            var vat_value = $('#vat-type-id').find(':selected').data('value');

            // Update costings
            if($('#deposit-net-cost').val() != '') {
                if($('#vat-type-id').length && $('#deposit-vat-cost').length && $('#deposit-gross-cost').length) {
                    var vat = net_cost * vat_value;
                    var vat_cost = vat / 100;
                    var gross_cost = net_cost + vat_cost;

                    vat_cost = vat_cost.toFixed(2);
                    gross_cost = gross_cost.toFixed(2);

                    $('#deposit-vat-cost').val(vat_cost);
                    $('#deposit-gross-cost').val(gross_cost);
                }
            } else {
                $('#sale-saving').hide();
                $('#sale-vat-cost').val('');
                $('#sale-gross-cost').val('');
            }

        });
    }

});
