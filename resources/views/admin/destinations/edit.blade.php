@extends('layouts.admin')

@section('title', 'Edit Destination')
@section('page_title', 'Edit Destination')
@section('breadcrumb', 'Destinations / Edit')

@section('content')
    <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.destinations._form', ['submitLabel' => 'Update Destination'])
    </form>
@endsection
