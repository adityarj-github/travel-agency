@extends('layouts.admin')

@section('title', 'Add Slider')
@section('page_title', 'Add New Slider')
@section('breadcrumb', 'Sliders / Create')

@section('content')
    <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.sliders._form', ['submitLabel' => 'Create Slider'])
    </form>
@endsection
