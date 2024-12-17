<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $fillable = ['symbol', 'name'];

    /**
     * Get the prices associated with the stock.
     *
     * @return HasMany<StockPrice, $this>
     */
    public function prices(): HasMany
    {
        return $this->hasMany(StockPrice::class);
    }
}
