@extends('layouts.admin')

@section('title', 'Add Coupon')
@section('page_title', 'Add New Coupon')
@section('breadcrumb', 'Coupons / Create')

@section('content')
    <form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf
        @include('admin.coupons._form', ['submitLabel' => 'Create Coupon'])
    </form>
@endsection
