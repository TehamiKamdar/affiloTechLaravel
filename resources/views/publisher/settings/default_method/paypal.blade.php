<label class="card shadow-sm rounded-4 border-0 overflow-hidden position-relative mb-4 payment-radio-card" style="max-width: 320px; margin-left: 30px; border-left: 4px solid #009cde; cursor: pointer;">
    <!-- Hidden radio input -->
    <input type="radio" name="payment_method" value="paypal" class="d-none"
           {{ isset($payment->payment_method) && $payment->payment_method === 'paypal' ? 'checked' : '' }}>

    <div class="card-body p-0">
        <!-- Header with gradient background -->
        <div class="d-flex justify-content-between align-items-center p-3" style="background: linear-gradient(90deg, #f5f7fa 0%, #e8f0f8 100%);">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ \App\Helper\Static\Methods::staticAsset('img/paypal.png') }}" alt="PayPal" style="height: 24px;">
                <span class="text-muted small">Default Payment</span>
            </div>
            <span class="badge rounded-pill" style="background-color: #009cde; color: white; font-size: 0.65rem;">ACTIVE</span>
        </div>

        <!-- Payment details with background -->
        <div class="p-3 position-relative" style="background-color: #f9fbfd;">
            <div style="position: absolute; right: 10px; top: 10px; opacity: 0.05;">
                <svg width="60" height="60" viewBox="0 0 50 50" fill="#009cde">
                    <path d="M25 10c-8.3 0-15 6.7-15 15s6.7 15 15 15 15-6.7 15-15-6.7-15-15-15zm0 27c-6.6 0-12-5.4-12-12s5.4-12 12-12 12 5.4 12 12-5.4 12-12 12z"/>
                </svg>
            </div>

            <div class="d-flex align-items-center mb-3">
                <div class="me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#009cde">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-muted small mb-0">Account Holder</p>
                    <h6 class="mb-0 text-dark" style="letter-spacing: 0.5px;">
                        @if(isset($payment->paypal_holder_name))
                            <span class="text-muted" style="font-family: 'Courier New', monospace;">
                                ••••••{{ substr($payment->paypal_holder_name, -3) }}
                            </span>
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </h6>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 mt-3 pt-2" style="border-top: 1px dashed #d1e0f0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <span class="small text-success">PayPal Verified</span>
            </div>
        </div>
    </div>
</label>
