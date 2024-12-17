<div
    x-data="{
        price: {{ number_format($currentPrice, 2) }},
        prevPrice: {{ $previousPrice ? number_format($previousPrice, 2) : 'null' }},
        percentChange: {{ number_format($percentageChange, 2) }},
        symbol: '{{ $symbol }}',

        init() {
            this.fetchPrice();
            setInterval(() => this.fetchPrice(), 60000);
        },

        fetchPrice() {
            fetch(`/api/stocks/${this.symbol}`)
                .then(response => response.json())
                .then(data => {
                    this.prevPrice = this.price;
                    this.price = data.current_price;
                    this.percentChange = data.percentage_change;
                })
                .catch(error => {
                    console.error('Error fetching stock price:', error);
                });
        }
    }"
    class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 p-4 w-full"
>
    <div class="space-y-3">
        <div class="flex flex-col">
            <h2 class="text-xl font-bold text-gray-900">{{ $symbol }}</h2>
            <span class="text-sm text-gray-600">{{ $name }}</span>
        </div>

        <div class="flex justify-between items-center">
            <div
                class="flex items-center space-x-1"
                :class="{
                    'text-green-600': Number(percentChange) > 0,
                    'text-red-600': Number(percentChange) < 0,
                    'text-gray-600': Number(percentChange) === 0
                }"
            >
                <template x-if="Number(percentChange) > 0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </template>
                <template x-if="Number(percentChange) < 0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </template>
                <span x-text="Math.abs(Number(percentChange)).toFixed(2) + '%'" class="text-sm font-medium"></span>
            </div>
            <div
                x-text="'$' + Number(price).toFixed(2)"
                class="text-2xl font-bold"
                :class="{
                    'text-green-600': Number(percentChange) > 0,
                    'text-red-600': Number(percentChange) < 0,
                    'text-gray-600': Number(percentChange) === 0
                }"
            ></div>
        </div>
    </div>
</div>
