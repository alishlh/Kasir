<?php

namespace App\Livewire;

use Filament\Forms;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Category;
use Filament\Forms\Form;
use App\Models\Transaction;
use Livewire\WithPagination;
use App\Models\PaymentMethod;
use App\Models\TransactionItem;
use App\Helpers\TransactionHelper;
use App\Services\DirectPrintService;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class Pos extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    public int|string $perPage = 10;
    public $categories;
    public $selectedCategory;
    public $search = '';
    public $print_via_bluetooth = false;
    public $barcode = '';
    public $name = 'Umum';
    public $payment_method_id;
    public $payment_methods;
    public $order_items = [];
    public $total_price = 0;
    public $cash_received;
    public $change;
    public $showConfirmationModal = false;
    public $showCheckoutModal = false;
    public $orderToPrint = null;
    public $is_bjps = false;
    public $jasa_dokter = 0;
    public $jasa_tindakan = 0;
    public string $selectedPriceType = 'price';
    protected $listeners = [
        'scanResult' => 'handleScanResult',
    ];

    public function mount()
    {
        $settings = Setting::first();
        $this->print_via_bluetooth = $settings->print_via_bluetooth ?? $this->print_via_bluetooth = false;

        $this->categories = collect([['id' => null, 'name' => 'Semua']])->merge(Category::all());

        if (session()->has('orderItems')) {
            $this->order_items = session('orderItems');
        }

        $this->payment_methods = PaymentMethod::all();

        // Inisialisasi cash_received dan change dengan format
        $this->cash_received = $this->formatNumber('0');
        $this->change = $this->formatNumber('0');
    }

    public function render()
    {
        return view('livewire.pos', [
            'products' => Product::where('stock', '>', 0)->where('is_active', 1)
                ->when($this->selectedCategory !== null, function ($query) {
                    return $query->where('category_id', $this->selectedCategory);
                })
                ->where(function ($query) {
                    return $query->where('name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('sku', 'LIKE', '%' . $this->search . '%');
                })
                ->paginate($this->perPage)
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->default(fn() => $this->name)
                            ->label('Name Customer')
                            ->nullable()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('total_price')
                            ->hidden()
                            ->reactive()
                            ->default(fn() => $this->total_price ?? 0),

                        Forms\Components\Select::make('payment_method_id')
                            ->required()
                            ->label('Metode Pembayaran')
                            ->placeholder('Pilih')
                            ->options($this->payment_methods->pluck('name', 'id'))
                            ->columnSpan(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $paymentMethod = PaymentMethod::find($state);
                                $isCash = $paymentMethod?->is_cash ?? false;
                                $set('is_cash', $isCash);

                                if ($get('is_bjps')) {
                                    $set('cash_received', $this->formatNumber('0'));
                                    $set('change', $this->formatNumber('0'));
                                } elseif (!$isCash) {
                                    $totalWithJasa = $this->calculateTotalWithJasa($get);
                                    $set('change', $this->formatNumber('0'));
                                    $set('cash_received', $this->formatNumber($totalWithJasa));
                                }
                            })
                            ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, $state) {
                                $paymentMethod = PaymentMethod::find($state);
                                $isCash = $paymentMethod?->is_cash ?? false;

                                if ($get('is_bjps')) {
                                    $set('cash_received', $this->formatNumber('0'));
                                    $set('change', $this->formatNumber('0'));
                                } elseif (!$isCash) {
                                    $totalWithJasa = $this->calculateTotalWithJasa($get);
                                    $set('cash_received', $this->formatNumber($totalWithJasa));
                                    $set('change', $this->formatNumber('0'));
                                }

                                $set('is_cash', $isCash);
                            }),

                        Forms\Components\TextInput::make('is_cash')->hidden()->dehydrated(),

                        Forms\Components\TextInput::make('is_bjps')
                            ->hidden()
                            ->default(fn() => $this->is_bjps)
                            ->reactive(),

                        Forms\Components\TextInput::make('jasa_dokter')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->prefix('Rp')
                            ->label('Jasa Dokter')
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                $this->updateJasaCalculations($set, $get);
                            }),

                        Forms\Components\TextInput::make('jasa_tindakan')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->prefix('Rp')
                            ->label('Jasa Tindakan')
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                $this->updateJasaCalculations($set, $get);
                            }),

                        Forms\Components\TextInput::make('cash_received')
                            ->required()
                            ->reactive()
                            ->prefix('Rp')
                            ->label('Nominal Bayar')
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                // Format input dengan titik
                                $formattedValue = $this->formatNumber($state);
                                if ($formattedValue !== $state) {
                                    $set('cash_received', $formattedValue);
                                }

                                // Jika BJPS aktif, abaikan perubahan manual
                                if ($get('is_bjps')) {
                                    $set('cash_received', $this->formatNumber('0'));
                                    $totalWithJasa = $this->calculateTotalWithJasa($get);
                                    $set('change', $this->formatNumberWithSign(-$totalWithJasa));
                                    return;
                                }

                                $paid = $this->parseNumber($state ?? '0');
                                $totalWithJasa = $this->calculateTotalWithJasa($get);
                                $change = $paid - $totalWithJasa;
                                $set('change', $this->formatNumberWithSign($change));
                            }),

                        Forms\Components\TextInput::make('change')
                            ->required()
                            ->prefix('Rp')
                            ->label('Kembalian')
                            ->readOnly(),
                    ])
            ]);
    }

    /**
     * Hitung total dengan jasa dokter dan jasa tindakan
     */
    private function calculateTotalWithJasa($get)
    {
        $totalPrice = (int) ($get('total_price') ?? 0);
        $jasaDokter = (int) ($get('jasa_dokter') ?? 0);
        $jasaTindakan = (int) ($get('jasa_tindakan') ?? 0);

        return $totalPrice + $jasaDokter + $jasaTindakan;
    }

    /**
     * Update perhitungan ketika jasa diubah
     */
    private function updateJasaCalculations($set, $get)
    {
        $totalWithJasa = $this->calculateTotalWithJasa($get);

        $paymentMethod = PaymentMethod::find($get('payment_method_id'));
        $isCash = $paymentMethod?->is_cash ?? false;

        if (!$isCash && !$get('is_bjps')) {
            $set('cash_received', $this->formatNumber($totalWithJasa));
        }

        if ($isCash && !$get('is_bjps')) {
            $cashReceived = $this->parseNumber($get('cash_received') ?? '0');
            $change = $cashReceived - $totalWithJasa;
            $set('change', $this->formatNumberWithSign($change));
        }
    }

    /**
     * Format angka dengan titik sebagai pemisah ribuan
     */
    private function formatNumber($value)
    {
        if (empty($value) || $value === '0') {
            return '0';
        }

        // Hapus semua karakter non-digit
        $numericValue = preg_replace('/[^0-9]/', '', $value);

        // Format dengan titik jika tidak kosong
        if (!empty($numericValue)) {
            return number_format((int) $numericValue, 0, ',', '.');
        }

        return $value;
    }

    /**
     * Format angka dengan tanda minus jika negatif
     */
    private function formatNumberWithSign($value)
    {
        if ($value === 0 || $value === '0') {
            return '0';
        }

        $isNegative = $value < 0;
        $absoluteValue = abs((int) $value);

        $formatted = number_format($absoluteValue, 0, ',', '.');

        return $isNegative ? '-' . $formatted : $formatted;
    }

    /**
     * Konversi format angka ke numeric
     */
    private function parseNumber($formattedValue)
    {
        if (empty($formattedValue)) {
            return 0;
        }

        // Handle nilai negatif dengan format minus
        $isNegative = strpos($formattedValue, '-') === 0;
        $cleanValue = preg_replace('/[^0-9]/', '', $formattedValue);
        $numericValue = (int) $cleanValue;

        return $isNegative ? -$numericValue : $numericValue;
    }

    public function updatedBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)
            ->where('is_active', true)->first();

        if ($product) {
            $this->addToOrder($product->id);
        } else {
            Notification::make()
                ->title('Product not found ' . $barcode)
                ->danger()
                ->send();
        }

        $this->barcode = '';
    }

    public function handleScanResult($decodedText)
    {
        $product = Product::where('barcode', $decodedText)
            ->where('is_active', true)->first();

        if ($product) {
            $this->addToOrder($product->id);
        } else {
            Notification::make()
                ->title('Product not found ' . $decodedText)
                ->danger()
                ->send();
        }

        $this->barcode = '';
    }

    public function setCategory($categoryId = null)
    {
        $this->selectedCategory = $categoryId;
    }

    public function addToOrder($productId)
    {
        $product = Product::find($productId);
        if (!$product)
            return;

        $selectedPrice = $product->{$this->selectedPriceType};
        $priceToUse = ($selectedPrice > 0) ? $selectedPrice : $product->price;


        $existingItemKey = array_search($productId, array_column($this->order_items, 'product_id'));

        if ($existingItemKey !== false) {
            if ($this->order_items[$existingItemKey]['quantity'] >= $product->stock) {
                Notification::make()
                    ->title('Stok barang tidak mencukupi')
                    ->danger()
                    ->send();
                return;
            } else {
                $this->order_items[$existingItemKey]['quantity']++;
            }
        } else {
            $this->order_items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (int) $priceToUse,
                'cost_price' => $product->cost_price,
                'total_profit' => (int) $priceToUse - $product->cost_price,
                'image_url' => $product->image,
                'quantity' => 1,
            ];
        }
        session()->put('orderItems', $this->order_items);
    }

    public function loadOrderItems($orderItems)
    {
        $this->order_items = $orderItems;
        session()->put('orderItems', $orderItems);
    }

    public function increaseQuantity($product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            Notification::make()
                ->title('Produk tidak ditemukan')
                ->danger()
                ->send();
            return;
        }

        foreach ($this->order_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                if ($item['quantity'] + 1 <= $product->stock) {
                    $this->order_items[$key]['quantity']++;
                } else {
                    Notification::make()
                        ->title('Stok barang tidak mencukupi')
                        ->danger()
                        ->send();
                }
                break;
            }
        }

        session()->put('orderItems', $this->order_items);
    }

    public function decreaseQuantity($product_id)
    {
        foreach ($this->order_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                if ($this->order_items[$key]['quantity'] > 1) {
                    $this->order_items[$key]['quantity']--;
                } else {
                    unset($this->order_items[$key]);
                    $this->order_items = array_values($this->order_items);
                }
                break;
            }
        }
        session()->put('orderItems', $this->order_items);
    }

    public function calculateTotal()
    {
        $total = 0;

        foreach ($this->order_items as $item) {
            $total += $item['quantity'] * $item['price'];
        }

        $this->total_price = $total;
        return $total;
    }

    public function getTotalWithJasa()
    {
        return $this->total_price + $this->jasa_dokter + $this->jasa_tindakan;
    }

    public function resetOrder()
    {
        session()->forget(['orderItems', 'name', 'payment_method_id']);

        $this->order_items = [];
        $this->payment_method_id = null;
        $this->total_price = 0;
        $this->jasa_dokter = 0;
        $this->jasa_tindakan = 0;
        $this->is_bjps = false;
        $this->cash_received = $this->formatNumber('0');
        $this->change = $this->formatNumberWithSign('0');
    }

    public function toggleBjps()
    {
        $this->is_bjps = !$this->is_bjps;

        if ($this->is_bjps) {
            $this->cash_received = $this->formatNumber('0');
            $this->change = $this->formatNumberWithSign(-$this->getTotalWithJasa());

            Notification::make()
                ->title('Mode BJPS Aktif - Pembayaran Gratis')
                ->success()
                ->send();
        } else {
            $this->cash_received = $this->formatNumber($this->getTotalWithJasa());
            $this->change = $this->formatNumberWithSign('0');

            Notification::make()
                ->title('Mode BJPS Nonaktif')
                ->success()
                ->send();
        }
    }

    public function checkout()
    {
        $this->validate([
            'name' => 'string|max:255',
            'payment_method_id' => 'required'
        ]);

        // Parse nilai yang diformat ke numeric (termasuk minus)
        $cashReceivedNumeric = $this->parseNumber($this->cash_received);
        $changeNumeric = $this->parseNumber($this->change);

        $payment_method_id_temp = $this->payment_method_id;
        $totalWithJasa = $this->getTotalWithJasa();

        // Validasi jika uang yang dibayar kurang (kecuali BJPS)
        if (!$this->is_bjps && $cashReceivedNumeric < $totalWithJasa) {
            Notification::make()
                ->title('Pembayaran Kurang')
                ->body('Uang yang dibayar kurang dari total pembayaran. Silakan tambah nominal pembayaran.')
                ->warning()
                ->send();
            return;
        }

        // Validasi: minimal harus ada produk atau jasa
        $hasProducts = !empty($this->order_items) && count($this->order_items) > 0;
        $hasServices = $this->jasa_dokter > 0 || $this->jasa_tindakan > 0;

        if (!$hasProducts && !$hasServices) {
            Notification::make()
                ->title('Transaksi tidak valid')
                ->body('Minimal harus ada produk atau jasa yang dipilih.')
                ->warning()
                ->send();
            return;
        }

        $order = Transaction::create([
            'user_id' => auth()->id(),
            'payment_method_id' => $payment_method_id_temp,
            'transaction_number' => TransactionHelper::generateUniqueTrxId(),
            'name' => $this->name,
            'total' => $totalWithJasa,
            'cash_received' => $this->is_bjps ? 0 : $cashReceivedNumeric,
            'change' => $this->is_bjps ? -$totalWithJasa : $changeNumeric,
            'is_bjps' => $this->is_bjps,
            'jasa_dokter' => $this->jasa_dokter,
            'jasa_tindakan' => $this->jasa_tindakan,
        ]);

        // Hanya buat transaction items jika ada produk
        if ($hasProducts) {
            foreach ($this->order_items as $item) {
                TransactionItem::create([
                    'transaction_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'cost_price' => $item['cost_price'],
                    'total_profit' => $item['total_profit'] * $item['quantity'],
                ]);
            }
        }

        $this->orderToPrint = $order->id;
        $this->showConfirmationModal = true;
        $this->showCheckoutModal = false;

        Notification::make()
            ->title('Transaksi berhasil disimpan')
            ->success()
            ->send();

        // Reset form dengan format yang benar
        $this->name = 'Umum';
        $this->payment_method_id = null;
        $this->total_price = 0;
        $this->jasa_dokter = 0;
        $this->jasa_tindakan = 0;
        $this->cash_received = $this->formatNumber('0');
        $this->change = $this->formatNumberWithSign('0');
        $this->is_bjps = false;
        $this->order_items = [];
        session()->forget(['orderItems']);
    }

    public function printLocalKabel()
    {
        $directPrint = app(DirectPrintService::class);
        $directPrint->print($this->orderToPrint);

        $this->showConfirmationModal = false;
        $this->orderToPrint = null;
    }

    public function printBluetooth()
    {
        $order = Transaction::with(['paymentMethod', 'transactionItems.product'])->findOrFail($this->orderToPrint);
        $items = $order->transactionItems;

        $this->dispatch(
            'doPrintReceipt',
            store: Setting::first(),
            order: $order,
            items: $items,
            date: $order->created_at->format('d-m-Y H:i:s')
        );

        $this->showConfirmationModal = false;
        $this->orderToPrint = null;
    }
}
