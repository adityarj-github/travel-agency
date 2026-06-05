@extends('layouts.admin')

@section('title', 'Add Destination')
@section('page_title', 'Add New Destination')
@section('breadcrumb', 'Destinations / Create')

@section('content')
    <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.destinations._form', ['submitLabel' => 'Create Destination'])
    </form>
@endsection
