
    <!-- Alert -->
    <div class="alert alert-warning d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div>We transfer funds in Australian Dollars (AUD) at the current date conversion rates.</div>
    </div>

    <!-- PayPal Country -->
    <div class="mb-3">
        <label for="paypal_country" class="form-label fw-semibold">PayPal Country</label>
        <select class="form-control js-example-basic-single" id="paypal_country" name="paypal_country" >
            <option value="">Select PayPal Account Location</option>
            @foreach($countries as $country)
                <option value="{{ $country['id'] }}" {{ isset($payment->paypal_country) && $payment->paypal_country == $country['id'] ? 'selected' : '' }}>
                    {{ ucwords($country['name']) }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- PayPal Account Holder Name -->
    <div class="mb-3">
        <label for="paypal_holder_name" class="form-label fw-semibold">PayPal Account Name</label>
        <input type="text" class="form-control" id="paypal_holder_name" name="paypal_holder_name"
               placeholder="Enter PayPal Account Holder Name" value="{{ $payment->paypal_holder_name ?? '' }}" >
    </div>

    <!-- PayPal Email -->
    <div class="mb-3">
        <label for="paypal_email" class="form-label fw-semibold">PayPal Email Address</label>
        <input type="email" class="form-control" id="paypal_email" name="paypal_email"
               placeholder="Enter PayPal Email Address" value="{{ $payment->paypal_email ?? '' }}" >
    </div>

    <!-- Note -->
    <div class="mt-3 text-muted small">
        <i class="bi bi-exclamation-circle-fill text-warning me-1"></i>
        2% processing fee will be charged, capped to AUD 30.00.
    </div>

