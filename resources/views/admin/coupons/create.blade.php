@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
        {{ __('Buat Kupon Baru') }}
    </h2>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Tambah Kupon</h5>
        </div>
        <div class="card-body">
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        @include('admin.coupons._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
