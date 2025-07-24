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
    <div class="row justify-content-around g-3">

        <!-- Payment Option -->
        @php
            $methods = [
                'bank' => 'Bank Transfer',
                'paypal' => 'PayPal',
                'payoneer' => 'Payoneer'
            ];
        @endphp

        @foreach($methods as $method => $label)
            @php
                $id = 'payment_' . $method;
            @endphp

            <div class="payment-option" data-toggle="collapse" data-target="#collapse-{{ $method }}"
                aria-expanded="{{ isset($payment->payment_method) && $payment->payment_method == $method ? 'true' : 'false' }}">

                <input type="radio" id="{{ $id }}" name="payment_method" class="payment-radio"
                    value="{{ $method }}"
                    {{ isset($payment->payment_method) && $payment->payment_method == $method ? 'checked' : '' }} hidden>

                <label for="{{ $id }}" class="payment-label d-flex align-items-center">
                    <div>
                        <strong>{{ $label }}</strong>
                        <div class="text-muted">Transfer via your bank account</div>
                    </div>
                </label>
            </div>
        @endforeach


    </div>

    <div class="accordion mt-4" id="paymentAccordion">

        <!-- Bank -->
        <div class="accordion-item border-0">
            <div class="collapse {{ isset($payment->payment_method) && $payment->payment_method == 'bank' ? 'show' : '' }}"
                id="collapse-bank" data-parent="#paymentAccordion" datamethod-form>
                    @include("publisher.settings.form.bank", compact('payment', 'countries'))
            </div>
        </div>

        <!-- PayPal -->
        <div class="accordion-item border-0">
            <div class="collapse {{ isset($payment->payment_method) && $payment->payment_method == 'paypal' ? 'show' : '' }}"
                id="collapse-paypal" data-parent="#paymentAccordion" datamethod-form>
                    @include("publisher.settings.form.paypal", compact('payment'))
            </div>
        </div>

        <!-- Payoneer -->
        <div class="accordion-item border-0">
            <div class="collapse {{ isset($payment->payment_method) && $payment->payment_method == 'payoneer' ? 'show' : '' }}"
                id="collapse-payoneer" data-parent="#paymentAccordion" datamethod-form>
                    @include("publisher.settings.form.payoneer", compact('payment'))
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

    // Initially highlight the pre-selected payment option
    radios.forEach(radio => {
        if (radio.checked) {
            const option = radio.closest('.payment-option');
            option.classList.add('selected');
        }
    });

    // Add click behavior for payment option selection
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function () {
            // Remove 'required' from all radios and remove previous selection
            radios.forEach(radio => radio.removeAttribute('required'));
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));

            // Apply new selection
            const radio = option.querySelector('.payment-radio');
            radio.checked = true;
            radio.setAttribute('required', true);
            option.classList.add('selected');
        });
    });
});
</script>
