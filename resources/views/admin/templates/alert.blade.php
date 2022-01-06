@if(session('success'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-success alert-icon">
            <em class="icon ni ni-check-circle"></em> {!! session('success') !!}
        </div>
    </div>
@endif
@if(session('error'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger alert-icon">
            <em class="icon ni ni-alert-circle"></em> {!! session('error') !!}
        </div>
    </div>
@endif
@if(session('warning'))
    <div class="col-md-12 mb-3" >
        <div class="alert alert-warning alert-icon">
            <em class="icon ni ni-alert-circle"></em> {!! session('warning') !!}
        </div>
    </div>
@endif
@if(session('info'))
    <div class="col-md-12 mb-3">
        <div class="alert alert-info alert-icon">
            <em class="icon ni ni-alert-circle"></em> {!! session('info') !!}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
