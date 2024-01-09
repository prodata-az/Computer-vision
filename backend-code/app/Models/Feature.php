<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'features';
    protected $guarded = false;

    public function hardLabel(): BelongsTo
    {
        return $this->belongsTo(Hard_label::class);
    }
}
