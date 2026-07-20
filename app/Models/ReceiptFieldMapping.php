<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptFieldMapping extends Model
{
    protected $fillable = [
        'field_key',
        'x_coordinate',
        'y_coordinate',
        'font_size',
        'font_color',
        'font_weight',
        'text_align',
    ];
}
