<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Email <span>invoice</span></h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <em class="icon ni ni-cross"></em>
        </a>
    </div>
    <div class="modal-body">
        <form method="post" action="{{ url('invoices/email') }}" id="mail-form" class=" form-validate">
            @csrf
            <input type="hidden" name="invoice_id" value="{{ $invoice_id }}">

            <div class="form-group" >
                <label class="form-label">Email</label>
                <div class="form-control-wrap">
                    <input class="form-control" type="email" name="email" value="{{ $email }}">
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
            </div>
        </form>
    </div>
    {{--<div class="modal-footer bg-light">
        <span class="sub-text">Modal Footer Text</span>
    </div>--}}
</div>
