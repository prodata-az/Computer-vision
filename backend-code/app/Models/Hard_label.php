<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hard_label extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'hard_labels';
    protected $guarded = false;

    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }
}
