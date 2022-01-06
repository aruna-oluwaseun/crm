<div class="row"><!-- BEGIN ROW -->
    {{ view('admin.templates.alert') }}

    <p class="mb-3">Be careful editing these details</p>

    <form method="post" wire:submit.prevent="save">

        <div class="form-group">
            <label class="form-label">Default Holiday Days</label>
            <input wire:model.defer="settings.default_holiday_days" type="number" value="{{ $settings['default_holiday_days'] }}" class="form-control" required>
        </div>

        <div class="form-group" wire:ignore>
            <label>Holidays are inclusive of bank holiday?</label><br>
            <div class="custom-control custom-control-lg custom-radio {!! is_checked(1, $settings['inclusive_of_bank_holidays']) !!}">
                <input wire:model="settings.inclusive_of_bank_holidays" type="radio"  id="customRadio1" value="1" class="custom-control-input">
                <label class="custom-control-label" for="customRadio1">Yes</label>
            </div>
            <div class="custom-control custom-control-lg custom-radio  {!! is_checked(0, $settings['inclusive_of_bank_holidays']) !!}">
                <input wire:model="settings.inclusive_of_bank_holidays" type="radio"  id="customRadio2" value="0" class="custom-control-input">
                <label class="custom-control-label" for="customRadio2">No</label>
            </div>
        </div>

        <div class="alert alert-info">Toggle switch to payment live means the Payment gateway will be live to charge customers, toggle off payment gateway will be in test, to find test card numbers please search your payment provider with test card number appended (i.e Stripe test card numbers)</div>
        <div class="form-group" wire:ignore>
            <label>Accept payments in which mode?</label><br>
            <div class="custom-control custom-control-lg custom-radio {!! is_checked(1, $settings['testing_payments']) !!}">
                <input wire:model="settings.testing_payments" type="radio"  id="customRadio3" value="1" class="custom-control-input">
                <label class="custom-control-label" for="customRadio3">Test mode</label>
            </div>
            <div class="custom-control custom-control-lg custom-radio  {!! is_checked(0, $settings['testing_payments']) !!}">
                <input wire:model="settings.testing_payments" type="radio"  id="customRadio4" value="0" class="custom-control-input">
                <label class="custom-control-label" for="customRadio4">Live mode</label>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
        </div>
    </form>
</div>
