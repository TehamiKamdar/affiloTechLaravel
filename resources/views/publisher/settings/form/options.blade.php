<style>
    .payment-option-card {
        border: 2px solid #dee2e6;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .payment-option-card.selected {
        border-color: #0d6efd;
        background-color: #f0f8ff;
    }

    .payment-option-card img {
        max-height: 32px;
    }

    .payment-radio {
        display: none;
    }
</style>

<div class="mb-4" id="paymentContent">
    <div class="row g-3">

        <!-- Payment Option -->
        @php
            $methods = [
                'bank' => 'Bank Transfer',
                'paypal' => 'PayPal',
                'payoneer' => 'Payoneer'
            ];
        @endphp

        @foreach($methods as $method => $label)
            <div class="col-md-4">
                <label class="border border-2 rounded-3 p-4 text-center d-flex flex-column align-items-center justify-content-center h-100 transition-all position-relative payment-option w-100"
                    style="cursor: pointer;"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse-{{ $method }}"
                    aria-expanded="{{ isset($payment->payment_method) && $payment->payment_method == $method ? 'true' : 'false' }}">

                    <!-- Hidden radio input -->
                    <input type="radio" name="payment_method" class="d-none payment-radio"
                        value="{{ $method }}"
                        {{ isset($payment->payment_method) && $payment->payment_method == $method ? 'checked' : '' }} required>

                    <!-- Option Icon -->
                    <img src="{{ \App\Helper\Static\Methods::staticAsset("img/{$method}.png") }}"
                        alt="{{ $label }}"
                        class="mb-3" style="height: 36px;">

                    <!-- Option Label -->
                    <h6 class="mb-0 fw-semibold text-capitalize">{{ $label }}</h6>
                </label>
            </div>

        @endforeach

    </div>

    <div class="accordion mt-4" id="paymentAccordion">

        <!-- Bank -->
        <div class="accordion-item border-0">
            <div class="collapse {{ isset($payment->payment_method) && $payment->payment_method == 'bank' ? 'show' : '' }}"
                id="collapse-bank" data-bs-parent="#paymentAccordion"  data-method-form>
                <div class="card card-body">
                    @include("publisher.settings.form.bank", compact('payment', 'countries'))
                </div>
            </div>
        </div>

        <!-- PayPal -->
        <div class="accordion-item border-0">
            <div class="collapse {{ isset($payment->payment_method) && $payment->payment_method == 'paypal' ? 'show' : '' }}"
                id="collapse-paypal" data-bs-parent="#paymentAccordion"  data-method-form>
                <div class="card card-body">
                    @include("publisher.settings.form.paypal", compact('payment'))
                </div>
            </div>
        </div>

        <!-- Payoneer -->
        <div class="accordion-item border-0">
            <div class="collapse {{ isset($payment->payment_method) && $payment->payment_method == 'payoneer' ? 'show' : '' }}"
                id="collapse-payoneer" data-bs-parent="#paymentAccordion"  data-method-form>
                <div class="card card-body">
                    @include("publisher.settings.form.payoneer", compact('payment'))
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Submit Button -->
<div class="mt-4 d-flex justify-content-end">
    <button type="submit" class="btn btn-primary">
        {{ isset($payment->id) ? 'Update' : 'Save' }}
    </button>
</div>

<!-- Script to handle border highlighting -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        const radios = document.querySelectorAll('.payment-radio');

        // Highlight selected card and show the relevant collapse
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function () {
                radios.forEach(radio => radio.removeAttribute('required'));
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                const radio = option.querySelector('.payment-radio');
                radio.checked = true;
                radio.setAttribute('required', true);
            });
        });

       
    });
</script>

