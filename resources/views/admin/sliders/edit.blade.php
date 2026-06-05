@extends('layouts.admin')

@section('title', 'Edit Slider')
@section('page_title', 'Edit Slider')
@section('breadcrumb', 'Sliders / Edit')

@section('content')
    <form method="POST" action="{{ route('admin.sliders.update', $slider) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.sliders._form', ['submitLabel' => 'Update Slider'])
    </form>
@endsection
