@extends('layouts.admin')

@section('title', 'Edit Post')
@section('page_title', 'Edit Post')
@section('breadcrumb', 'Blogs / Edit')

@section('content')
    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.blogs._form', ['submitLabel' => 'Update Post'])
    </form>
@endsection

@push('scripts')
    @include('admin.partials.ckeditor')
@endpush
