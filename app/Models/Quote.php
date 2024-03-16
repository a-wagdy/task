<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
    ];

    protected function casts(): array
    {
        return [
            'symbol' => 'string',
        ];
    }

    /**
     * Get the prices for the quote.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
}
