<?php
namespace App\Models;

class TransactionConversation extends BaseModelWithoutUuids
{
    // The table associated with the model
    protected $table = "transaction_conversations";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "to",          // The recipient of the conversation
        "from",        // The sender of the conversation
        "rate",        // Rate associated with the transaction
        "date",        // Date of the transaction or conversation
        "actual_rate", // Actual rate involved in the transaction
    ];
}
