<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="bookings";
    protected $fillable=["name",'user_id',
    'email', 'phone', 'address', 'total','status','note','cancel_reason'
];
}
