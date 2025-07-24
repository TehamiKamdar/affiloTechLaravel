    <div class="form-cc">

        <div class="form-group">
            <label for="paypal_holder_name" class="font-weight-bold text-black font-size14">Payoneer Account Name</label>
            <input type="text" class="form-control" id="payoneer_holder_name" name="payoneer_holder_name" placeholder="Enter Payoneer Account Holder Name" value="{{ $payment->payoneer_holder_name ?? null }}" >
        </div>
        <div class="form-group">
            <label for="pyoneer_email" class="font-weight-bold text-black font-size14">Payoneer Email Address</label>
            <input type="text" class="form-control" id="payoneer_email" name="payoneer_email" placeholder="Enter Payoneer Email Address" value="{{ $payment->payoneer_email ?? null }}" >
        </div>
        <div class="form-group">
            <p> <i class="la la-exclamation-circle text-warning"></i> 2% processing fee will be charged, capped to USD 20.00.</p>
        </div>
    </div>
