@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
        {{ __('Edit Kupon: ') . $coupon->code }}
    </h2>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Kupon</h5>
        </div>
        <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('admin.coupons._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
