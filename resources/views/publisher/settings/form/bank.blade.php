
    <div class="card-body bg-white">

        <!-- Alert -->
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div>We transfer funds in Australian Dollars (AUD) at the current date conversion rates.</div>
        </div>

        <!-- Bank Country -->
        <div class="mb-3">
            <label for="bank_location" class="form-label fw-semibold">Bank Country</label>
            <select class="form-select js-example-basic-single" id="bank_location" name="bank_location" >
                <option value="">Select Bank Location</option>
                @foreach($countries as $country)
                    <option value="{{ $country['id'] }}" {{ isset($payment->bank_location) && $payment->bank_location == $country['id'] ? 'selected' : '' }}>
                        {{ ucwords($country['name']) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Account Holder Name -->
        <div class="mb-3">
            <label for="account_holder_name" class="form-label fw-semibold">Account Holder Name</label>
            <input type="text" class="form-control" id="account_holder_name" name="account_holder_name"
                   placeholder="Enter Bank Account Holder Name" value="{{ $payment->account_holder_name ?? '' }}" >
        </div>

        <!-- Bank Account Number -->
        <div class="mb-3">
            <label for="bank_account_number" class="form-label fw-semibold">Bank Account Number</label>
            <input type="text" class="form-control" id="bank_account_number" name="bank_account_number"
                   placeholder="Enter Complete Bank Account Number / IBAN / Routing"
                   value="{{ $payment->bank_account_number ?? '' }}" >
        </div>

        <!-- Conditional Fields -->
        @if(!isset($payment->bank_location) || $payment->bank_location != 166)
            <!-- BIC/SWIFT -->
            <div class="mb-3" id="bankCodeContent">
                <label for="bank_code" class="form-label fw-semibold">BIC / SWIFT Code / BSB</label>
                <input type="text" class="form-control" id="bank_code" name="bank_code"
                       placeholder="Enter BIC / SWIFT Code / BSB / ABA"
                       value="{{ $payment->bank_code ?? '' }}" >
            </div>

            <!-- Account Type -->
            <div class="mb-3" id="bankAccountTypeContent">
                <label for="account_type" class="form-label fw-semibold">Bank Account Type</label> 
                <select class="form-select js-example-basic-single" id="account_type" name="account_type" >
                    <option value="">Select Account Type</option>
                    <option value="checking" {{ isset($payment->account_type) && $payment->account_type == 'checking' ? 'selected' : '' }}>Checking</option>
                    <option value="saving" {{ isset($payment->account_type) && $payment->account_type == 'saving' ? 'selected' : '' }}>Saving</option>
                </select>
            </div>
        @endif

        <!-- Note -->
        <div class="mt-3 text-muted small">
            <i class="bi bi-exclamation-circle-fill text-warning me-1"></i>
            2% processing fee will be charged, capped to AUD 30.00.
        </div>

    </div>
