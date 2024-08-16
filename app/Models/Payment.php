<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier',
        'description',
        'currency',
        'amount',
        'ct',
        'ac_name',
        'settle_by',
        'paid_status',

    ];
    public function department(){
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
