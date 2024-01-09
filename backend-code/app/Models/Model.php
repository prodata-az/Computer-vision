<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'models';
    protected $guarded = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function configs(): HasMany
    {
        return $this->hasMany(Config::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function hardLabels(): HasMany
    {
        return $this->hasMany(Hard_label::class);
    }
}