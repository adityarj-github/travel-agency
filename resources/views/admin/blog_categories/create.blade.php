@extends('layouts.admin')

@section('title', 'Add Category')
@section('page_title', 'Add Blog Category')
@section('breadcrumb', 'Blog Categories / Create')

@section('content')
    <form method="POST" action="{{ route('admin.blog-categories.store') }}">
        @csrf
        @include('admin.blog_categories._form', ['submitLabel' => 'Create Category'])
    </form>
@endsection
