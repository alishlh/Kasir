<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    </style>

    @livewire('pos')


    {{-- Script untuk handle printer --}}
    <script src="{{ asset('js/printer-thermal.js') }}"></script>

    {{-- Script untuk Layar Fullscreen --}}
    <script src="{{ asset('js/full-screen.js') }}"></script>

</x-filament-panels::page>