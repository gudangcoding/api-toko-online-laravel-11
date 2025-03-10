@extends('layouts.app')

@section('content')
    <div class="container px-4 py-8 mx-auto">
        <h1 class="text-2xl font-semibold text-center text-black dark:text-white">Dashboard Pengguna</h1>
        <div class="grid gap-8 mt-8 md:grid-cols-2 lg:grid-cols-3">
            <!-- Info Profil -->
            <div class="p-6 bg-white rounded-lg shadow dark:bg-zinc-900">
                <h2 class="text-xl font-semibold text-black dark:text-white">Profil Saya</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Nama: {{ Auth::user()->name }}</p>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Email: {{ Auth::user()->email }}</p>
                <!-- Tambahkan informasi lainnya jika perlu -->
            </div>

            <!-- Riwayat Belanja -->
            <div class="p-6 bg-white rounded-lg shadow dark:bg-zinc-900">
                <h2 class="text-xl font-semibold text-black dark:text-white">Riwayat Belanja</h2>
                <ul class="mt-4 space-y-2">
                    @foreach ($orders as $order)
                        <li class="flex justify-between py-2 border-b">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Order ID: {{ $order->id }}</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total: ${{ $order->total }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Produk Favorit -->
            <div class="p-6 bg-white rounded-lg shadow dark:bg-zinc-900">
                <h2 class="text-xl font-semibold text-black dark:text-white">Produk Favorit</h2>
                <ul class="mt-4 space-y-2">
                    @foreach ($favorites as $product)
                        <li class="flex justify-between py-2 border-b">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $product->name }}</span>
                            <a href="{{ route('products.show', $product) }}"
                                class="text-sm text-blue-500 hover:underline">Lihat</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
