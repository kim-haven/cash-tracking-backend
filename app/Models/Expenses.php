<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'paid_by',
        'pay_to',
        'approved_by',
        'receipt_uploaded',
        'type',
        'description',
        'cash_in',
        'cash_out',
    ];

    protected $casts = [
        'date' => 'date',
        'receipt_uploaded' => 'boolean',
        'cash_in' => 'decimal:2',
        'cash_out' => 'decimal:2',
    ];
}