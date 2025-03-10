@extends('layouts.app')

@section('content')
    <div class="container px-4 pt-16 mx-auto">
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/2">
                <img src="{{ asset('tema/assets/images/prod-1.png') }}" alt="product" class="object-cover w-full h-full">
            </div>
            <div class="w-full lg:w-1/2 lg:pl-10">
                <h1 class="mt-0 mb-2 text-3xl font-bold leading-tight">{{ $product->name }}</h1>
                <p class="text-xl leading-relaxed text-gray-600">{{ $product->description }}</p>
                <p class="mt-0 mb-2 text-3xl font-bold leading-tight">{{ $product->price }}</p>
                <button class="px-4 py-2 font-bold text-white bg-orange-500 rounded hover:bg-orange-700">Add to
                    Cart</button>
            </div>
        </div>
    </div>
@endsection
