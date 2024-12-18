<?php

namespace App\View\Components;

use App\Repositories\StockRepository;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StockPrice extends Component
{
    public function __construct(
        private readonly StockRepository $repository,
        public readonly string $symbol,
        public readonly string $name
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = $this->repository->getLatestPrices($this->symbol);

        if (! isset($data['symbol'])) {
            return view('components.stock-price-empty', [
                'symbol' => $this->symbol,
                'name' => $this->name,
            ]);
        }

        return view('components.stock-price', [
            'symbol' => $data['symbol'],
            'name' => $this->name,
            'currentPrice' => $data['current_price'],
            'previousPrice' => $data['previous_price'],
            'percentageChange' => $data['percentage_change'],
        ]);
    }
}
