$(document).ready(function() {
    $(document).ready(function() {

        // Load the date picker initially
        Livewire.emit('init:date-picker');

        $(document).on('keyup','#obligation-vrn', function() {
            $('#vat-return-vrn').val($(this).val());
        });

        $(document).on('change','[name="from_date"]',function(e) {
            var date = $(this).val();
            Livewire.emit('set:from-date', date);
        });

        $(document).on('change','[name="to_date"]',function(e) {
            var date = $(this).val();
            Livewire.emit('set:to-date', date);
        });

        window.addEventListener('add-date-picker', event => {
            setTimeout(function () {
                $('[name="from_date"], [name="to_date"]').datepicker();
            }, 600);
        });

        $(document).on('click', '.period-link', function(e) {
            e.preventDefault();
            var due_date = $(this).data('due-date');
            var period_key = $(this).data('period-key');

            Livewire.emit('set:period_key',period_key);

            $('#tabSubmitVat h5.header').html('VAT due on <span class="text-success">'+ due_date +'</span>');
            $('#tabSubmitVat [name="period_key"]').val(period_key);

            $('a[href="#tabSubmitVat"]').click();
        });

    });
});
