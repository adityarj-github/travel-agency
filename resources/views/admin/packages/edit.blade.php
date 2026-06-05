@extends('layouts.admin')

@section('title', 'Edit Package')
@section('page_title', 'Edit Package')
@section('breadcrumb', 'Packages / Edit')

@section('content')
    <form method="POST" action="{{ route('admin.packages.update', $package) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.packages._form', ['submitLabel' => 'Update Package'])
    </form>
@endsection

@push('scripts')
    @include('admin.partials.ckeditor')
@endpush
