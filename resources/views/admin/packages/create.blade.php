@extends('layouts.admin')

@section('title', 'Add Package')
@section('page_title', 'Add New Package')
@section('breadcrumb', 'Packages / Create')

@section('content')
    <form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.packages._form', ['submitLabel' => 'Create Package'])
    </form>
@endsection

@push('scripts')
    @include('admin.partials.ckeditor')
@endpush
