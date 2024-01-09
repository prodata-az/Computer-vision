<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'configs';
    protected $guarded = false;
    protected $casts = [
      'confidence_score' => 'array',
    ];

    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }
}
