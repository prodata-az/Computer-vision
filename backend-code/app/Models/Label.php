<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Label extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'labels';
    protected $guarded = false;

    public function config(): BelongsTo
    {
        return $this->belongsTo(Config::class);
    }
}