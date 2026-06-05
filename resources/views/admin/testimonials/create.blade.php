@extends('layouts.admin')

@section('title', 'Add Testimonial')
@section('page_title', 'Add New Testimonial')
@section('breadcrumb', 'Testimonials / Create')

@section('content')
    <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.testimonials._form', ['submitLabel' => 'Create Testimonial'])
    </form>
@endsection
