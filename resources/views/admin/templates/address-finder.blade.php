<div class="address-container">
    <div class="form-group">
        <label class="form-label" for="notes">Postcode *</label>
        <div class="form-control-wrap">
            <input id="find-postcode" class="find-postcode form-control" value="" placeholder="Enter postcode">
        </div>
    </div>
    <div class="form-group">
        <button id="postcode-btn" type="button" class="submit-btn postcode-btn btn btn-lg btn-outline-primary">Find addresses</button>
    </div>

    <div id="show-addresses" class="form-group show-addresses" style="display: none;">
        <label class="form-label" for="notes">Addresses found</label>
        <div class="form-control-wrap">
            <select class="form-select">

            </select>
        </div>
    </div>

    <hr>

    <div class="form-group">
        <label class="form-label">Organisation / Address name</label>
        <div class="form-control-wrap">
            <input class="form-control title" type="text" name="title" id="title">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Line 1 *</label>
                <div class="form-control-wrap">
                    <input class="form-control line1" type="text" name="line1" id="line1" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Line 2</label>
                <div class="form-control-wrap">
                    <input class="form-control line2" type="text" name="line2" id="line2">
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Line 3</label>
                <div class="form-control-wrap">
                    <input class="form-control line3" type="text" name="line3" id="line3">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">City *</label>
                <div class="form-control-wrap">
                    <input class="form-control city" type="text" name="city" id="city" required>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Postcode *</label>
                <div class="form-control-wrap">
                    <input class="form-control postcode" type="text" name="postcode" id="postcode" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">County</label>
                <div class="form-control-wrap">
                    <input class="form-control county" type="text" name="county" id="county">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php
        if( !$ip_country = session('ip_location.country_name') ) {
            $ip_country = '';
        }
        ?>
        <label class="form-label" for="notes">Country *</label>
        <div class="form-control-wrap">
            <select class="form-select country" name="country" id="add-country" data-search="on" required>
                <?php if($countries = countries()) : ?>
                <?php foreach($countries as $country) : ?>
                <option value="{{ $country->title }}" data-country-code="{{$country->code}}" {{ is_selected($country->title, $ip_country) }}>{{$country->title}}</option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <input class="lat" type="hidden" id="lat" name="lat">
    <input class="lng" type="hidden" id="lng" name="lng">
</div>
