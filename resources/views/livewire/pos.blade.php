<div class="grid grid-cols-1 md:grid-cols-3 gap-4" style="font-family : poppins;">
    <div class="md:col-span-2">
        <div class="flex flex-col md:flex-row  items-center justify-between mb-10">
            <input wire:model.live.debounce.300ms='search' type="text" placeholder="Cari nama atau sku produk ..."
                class="w-full p-2 border rounded-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
            <input wire:model.live='barcode' type="text" placeholder="Scan dengan alat scanner ..." autofocus
                id="barcode"
                class="w-full p-2 border rounded-full mt-2 md:mt-0 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white  md:ml-2">
            <x-filament::button x-data="" x-on:click="$dispatch('toggle-scanner')"
                class="px-2 md:w-20 mt-2 md:mt-0 w-full h-12 bg-yellow-400 rounded-full md:ml-2">
                <img src="{{ asset('images/qrcode-scan.svg') }}" class="w-8" />
            </x-filament::button>

            {{-- MODAL SCAN CAMERA --}}
            <livewire:scanner-modal-component>
        </div>

        <div class="mt-5 px-2.5 overflow-x-auto hide-scrollbar">
            <div class="flex gap-2.5 pb-2.5 whitespace-nowrap">
                @foreach ($categories as $item)
                    <button wire:click="setCategory({{ $item['id'] ?? null }})"
                        class="category-btn px-6 py-2 mb-4 border-2 border-primary-600 rounded-lg transition-colors duration-300 {{ $selectedCategory === $item['id'] ? 'bg-primary-600 text-white' : 'dark:bg-gray-600 dark:text-white text-primary-600' }} hover:bg-primary-100">
                        {{ $item['name'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="mt-5 px-2.5 overflow-x-auto hide-scrollbar">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($products as $item)
                    <div wire:click="addToOrder({{ $item->id }})"
                        class="bg-white dark:bg-gray-700 p-2 rounded-lg border dark:border-none shadow cursor-pointer">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="Product Image"
                            class="w-full h-24 object-cover shadow  border rounded-lg mb-2">
                        <h3 class="text-sm font-semibold">{{ $item->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Rp.
                            {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="py-4">
            <x-filament::pagination :paginator="$products" :page-options="[5, 10, 20, 50, 100, 'all']" current-page-option-property="perPage" />
        </div>
    </div>

    <div class="md:col-span-1 bg-white max-h-[650px] dark:bg-gray-800 shadow-md rounded-lg p-6 block">
        <button wire:click="resetOrder"
            class="w-full h-12 bg-red-500 mt-2 text-white py-2 rounded-lg mb-4">Reset</button>
        @if (count($order_items) === 0)
            <div class="flex flex-col justify-center items-center text-center text-gray-500 dark:text-gray-300 py-30">
                <img src="{{ asset('images/cart-empty.png') }}" alt="Empty Cart" class="w-32 h-32 mb-4">
                <p class="text-lg font-semibold">Keranjang Kosong</p>
            </div>
        @else
            @if (count($order_items) >= 5)
                <div style="height: 400px; overflow-y: auto;" class="mb-4">
                @else
                <div class="mb-4">
            @endif

            @foreach ($order_items as $item)
                <div class="mb-4 ">
                    <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <div class="flex items-center">
                            <img src="{{ asset('storage/' . $item['image_url']) }}" alt="Product Image"
                                class="w-10 h-10 object-cover rounded-lg mr-2">
                            <div class="px-2">
                                <h3 class="text-xs line-clamp-2 font-semibold">{{ $item['name'] }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-xs">Rp
                                    {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <x-filament::button color="warning"
                                wire:click="decreaseQuantity({{ $item['product_id'] }})">-
                            </x-filament::button>
                            <span class="px-4">{{ $item['quantity'] }}</span>
                            <x-filament::button color="success"
                                wire:click="increaseQuantity({{ $item['product_id'] }})">+
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            @endforeach
                </div>
        @endif

        @if (count($order_items) > 0)
            <div class="py-4 border-t border-gray-100 bg-gray-50 dark:bg-gray-700 ">
                <h3 class="text-lg font-semibold text-center">Total: Rp
                    {{ number_format($this->calculateTotal(), 0, ',', '.') }}</h3>
            </div>
        @endif

        <div class="mt-2 ">
            <div class="flex flex-col justify-center items-center">
                <button type="button" wire:click="$set('showCheckoutModal', true)"
                    class=" w-full h-12 bg-green-500 mt-2 text-white py-2 rounded-lg mb-4 items-center justify-center ">
                    Checkout
                </button>
            </div>
        </div>
    </div>




    <!-- Modal -->
    @if ($showCheckoutModal)
        <div wire:ignore.self
            class="fixed antialiased inset-0 bg-black bg-opacity-75 flex justify-center items-center transition-opacity duration-300 ease-out z-[9999]">
            <div
                class="bg-none rounded-lg w-10/12 sm:w-7/12 md:w-5/12 lg:w-3/12 scale-95 transition-transform duration-300 ease-out">
                <form wire:submit="checkout">
                    {{ $this->form }}
                    <div class="flex justify-between mt-3">
                        <button type="button" wire:click="$set('showCheckoutModal', false)"
                            class="w-full rounded-l-full h-12 bg-red-500 hover:bg-red-600 text-white py-2">Batal</button>
                        <button type="submit"
                            class="w-full h-12 rounded-r-full bg-green-500 hover:bg-green-600 text-white py-2">Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($showConfirmationModal)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg w-11/12 sm:w-96">
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-purple-500 text-white rounded-t-lg">
                    <h2 class="text-xl text-center font-semibold">PRINT STRUK</h2>
                </div>
                <!-- Modal Body -->
                <div class="px-6 py-4">
                    <p class="text-gray-800">
                        Apakah Anda ingin mencetak struk untuk pesanan ini?
                    </p>
                </div>
                <!-- Modal Footer -->
                <div class="px-6 py-4 flex justify-center space-x-4">
                    <button wire:click="$set('showConfirmationModal', false)"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-400 focus:ring-2 focus:ring-gray-500">
                        Tidak
                    </button>
                    @if ($print_via_bluetooth == true)
                        <button wire:click="printBluetooth"
                            class="px-4 py-2 bg-purple-500 text-white rounded-full hover:bg-blue-600 focus:ring-2 focus:ring-blue-400">
                            Cetak
                        </button>
                    @else
                        <button wire:click="printLocalKabel"
                            class="px-4 py-2 bg-purple-500 text-white rounded-full hover:bg-blue-600 focus:ring-2 focus:ring-blue-400">
                            Cetak
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
