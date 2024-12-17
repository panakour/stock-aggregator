<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-wrap justify-center gap-4">
            @foreach($stocks as $stock)
                <div class="w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1rem)] xl:w-[calc(25%-1rem)]">
                    <x-stock-price :symbol="$stock['symbol']" :name="$stock['name']" />
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
