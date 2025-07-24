<label class="card shadow-sm rounded-4 border-0 overflow-hidden position-relative mb-4 payment-radio-card"
       style="max-width: 320px; margin-left: 30px; border-left: 4px solid #198754; cursor: pointer;">
    <!-- Hidden Radio -->
    <input type="radio" name="payment_method" value="bank" class="d-none"
           {{ isset($payment->payment_method) && $payment->payment_method === 'bank' ? 'checked' : '' }}>

    <div class="card-body p-0">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center p-3"
             style="background: linear-gradient(90deg, #e9f7ec 0%, #d9f2e4 100%);">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('img/bank.png') }}" alt="Bank"
                     style="height: 24px;">
            </div>
            <span class="badge rounded-pill bg-success" style="font-size: 0.65rem;">ACTIVE</span>
        </div>

        <!-- Body -->
        <div class="p-3 position-relative" style="background-color: #f5fef8;">
            <!-- Background Icon -->
            <div style="position: absolute; right: 10px; top: 10px; opacity: 0.03;">
                <svg width="60" height="60" viewBox="0 0 50 50" fill="#198754">
                    <path d="M25 5C14 5 5 14 5 25s9 20 20 20 20-9 20-20S36 5 25 5zm0 36c-8.8 0-16-7.2-16-16S16.2 9 25 9s16 7.2 16 16-7.2 16-16 16z"/>
                </svg>
            </div>

            <!-- Holder Name -->
            <div class="d-flex align-items-center mb-3">
                <div class="me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="#198754">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-muted small mb-0">Account Holder</p>
                    <h6 class="mb-0 text-dark" style="letter-spacing: 0.5px;">
                        @if($payment->account_holder_name)
                            <span class="text-muted" style="font-family: 'Courier New', monospace;">
                                ••••••{{ substr($payment->account_holder_name, -3) }}
                            </span>
                        @else
                            <span class="text-muted">Not provided</span>
                        @endif
                    </h6>
                </div>
            </div>

            <!-- Status Indicator -->
            <div class="d-flex align-items-center gap-2 mt-3 pt-2" style="border-top: 1px dashed #cdebd6;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <span class="small text-success">Bank Verified</span>
            </div>
        </div>
    </div>
</label>
