<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 p-4 w-full">
    <div class="space-y-3">
        <div class="flex flex-col">
            <h2 class="text-xl font-bold text-gray-900">{{ $symbol }}</h2>
            <span class="text-sm text-gray-600">{{ $name }}</span>
        </div>
        <div class="flex justify-center items-center py-4">
            <p class="text-gray-500">
                No price there :( Run price fetcher first:
                <code class="bg-gray-100 px-2 py-1 rounded text-sm">php artisan app:fetch-fake-prices</code>
            </p>
        </div>
    </div>
</div>
