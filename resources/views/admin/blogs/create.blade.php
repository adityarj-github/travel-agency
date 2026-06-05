@extends('layouts.admin')

@section('title', 'Add Post')
@section('page_title', 'Add New Post')
@section('breadcrumb', 'Blogs / Create')

@section('content')
    <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.blogs._form', ['submitLabel' => 'Create Post'])
    </form>
@endsection

@push('scripts')
    @include('admin.partials.ckeditor')
@endpush
