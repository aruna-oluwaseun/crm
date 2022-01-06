<div class="row"><!-- BEGIN ROW -->
    {{ view('admin.templates.alert') }}

    <!-- Form -->
    <div class="col-md-6 mb-3"><!-- start col 6-->

        <h5 class="header mb-1">Get your obligations from the obligations tab</h5>
        <p class="mb-3">Please make sure your VAT is returned by this date.</p>

        <form method="post" wire:submit.prevent="save">
            @csrf

            <div class="form-group">
                <label class="form-label">VAT Number *</label>
                <input wire:model.defer="vrn" id="vat-return-vrn" type="number" value="{{ $vrn }}" class="form-control" name="vrn" required>{{----}}
            </div>

            <div class="form-group">
                <label class="form-label">Period Key *</label>
                <input wire:model.defer="period_key" type="text" value="0" class="form-control" name="period_key" required>
            </div>

            <div class="form-group">
                <label class="form-label">VAT Due Sales *</label>
                <input wire:model.defer="vat_due_sales" type="number" value="0" step="00.01" class="form-control" required>
            </div>

            <input wire:model.defer="vat_due_acquisitions" type="hidden" value="0" required>

            <div class="form-group">
                <label class="form-label">Total VAT Due *</label>
                <input wire:model.defer="total_vat_due" type="number" value="0" step="00.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Total Reclaimed VAT Current Period *</label>
                <input wire:model.defer="vat_reclaimed_curr_period" type="number" value="0" step="00.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Net VAT Due *</label>
                <input wire:model.defer="net_vat_due" type="number" value="0" step="00.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Total Value of Sales ExVAT *</label>
                <input wire:model.defer="total_value_sales_ex_vat" type="number" value="0" step="00.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Total Value of Purchases ExVAT *</label>
                <input wire:model.defer="total_value_purchases_ex_vat" type="number" value="0" step="00.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Total Value of Goods Supplied ExVAT (from Northern Ireland to EU) *</label>
                <input wire:model.defer="total_value_good_supplied_ex_vat" type="number" value="0" step="00.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Total Value of Acquisitions ExVAT ( made in Northern Ireland from EU ) *</label>
                <input wire:model.defer="total_acquisitions_ex_vat" type="number" value="0" step="00.01" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Finalised *</label>
                <select wire:model.defer="finalised" class="form-control" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn btn btn-lg btn-primary">Submit Return</button>
            </div>

        </form>
    </div><!-- end col 6 -->

    <div class="col-md-6 mb-3"><!-- start col 6-->

        <?php if(isset($response) && !empty($response)) : ?>

        <div class="card card-bordered">
            <div class="card-header bg-success text-white">VAT return filed</div>
            <div class="card-body text-center">
                <em class="icon ni ni-check-circle-cut" style="font-size: 30px;"></em><br>
                <p>We have saved the following details but if you require them for your reference please see below :</p>
                <ul style="text-align: left;">
                    <li>Date processed by HMRC : <b>{{ $response['processingDate'] }}</b></li>
                    <li>Form Bundle Ref : <b>{{ $response['formBundleNumber'] }}</b></li>
                    <?php if(isset($response['paymentIndicator']) && !is_null($response['paymentIndicator'])) : ?>
                        <li>Payment Indicator : <b>{{ $response['paymentIndicator'] }}</b></li>
                    <?php endif; ?>
                    <?php if(isset($response['chargeRefNumber']) && !is_null($response['chargeRefNumber'])) : ?>
                    <li>Charge Ref : <b>{{ $response['chargeRefNumber'] }}</b></li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <?php else : ?>

            <div class="alert alert-warning">Submit a vat return using the form</div>


        <?php endif; ?>

    </div><!-- end col 6 -->

</div><!-- END ROW -->

