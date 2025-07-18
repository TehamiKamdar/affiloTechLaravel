<?php

namespace App\Http\Requests\Doc;

/**
 * Query parameters
 */
class TransactionRequest extends GenericRequest
{
    public function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
           'start_date' => 'date',
           'end_date' => 'date',
           'transaction_id' => 'string|exists:transactions,transaction_id',
           'payment_id' => 'string',
        ]);
    }

    public function queryParameters()
    {
        $messages = parent::queryParameters();
        $startDateFormat = now()->subMonth()->format('Y-m-01');
        $endDateFormat = now()->subMonth()->format('Y-m-t');
        return array_merge($messages, [
            'start_date' => [
                'description' => "The start_date is optional, with a default value of {$startDateFormat}.",
                'example' => $startDateFormat,
            ],
            'end_date' => [
                'description' => "The end_date is optional, with a default value of {$endDateFormat}.",
                'example' => $endDateFormat,
            ],
            'transaction_id' => [
                'description' => 'The transaction_id is optional, with a value of 10959121312.',
                'example' => '10959121312',
            ],
            'payment_id' => [
                'description' => 'The payment_id is optional, with a value of 455832323.',
                'example' => '455832323',
            ],
        ]);
    }
}

