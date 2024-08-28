<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $client_id
 * @property string $number
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id', 'number'
    ];

    public static function getNewNumber() {
        return Str::upper(Str::random(10));
    }
}
