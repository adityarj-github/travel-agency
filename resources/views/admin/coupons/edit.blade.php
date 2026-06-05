@extends('layouts.admin')

@section('title', 'Edit Coupon')
@section('page_title', 'Edit Coupon')
@section('breadcrumb', 'Coupons / Edit')

@section('content')
    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
        @csrf
        @method('PUT')
        @include('admin.coupons._form', ['submitLabel' => 'Update Coupon'])
    </form>
@endsection
