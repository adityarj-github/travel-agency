@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page_title', 'Edit Blog Category')
@section('breadcrumb', 'Blog Categories / Edit')

@section('content')
    <form method="POST" action="{{ route('admin.blog-categories.update', $blogCategory) }}">
        @csrf
        @method('PUT')
        @include('admin.blog_categories._form', ['submitLabel' => 'Update Category'])
    </form>
@endsection
