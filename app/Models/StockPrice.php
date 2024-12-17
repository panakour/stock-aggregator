<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockPrice extends Model
{
    protected $fillable = ['stock_id', 'price', 'fetched_at'];

    /**
     * Get the stock associated with this price.
     *
     * @return BelongsTo<Stock, $this>
     */
    public function stock(): belongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
