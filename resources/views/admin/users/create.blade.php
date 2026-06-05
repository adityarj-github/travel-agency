@extends('layouts.admin')

@section('title', 'Add Staff')
@section('page_title', 'Add Staff Member')
@section('breadcrumb', 'Staff / Create')

@section('content')
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users._form', ['submitLabel' => 'Create Staff Member'])
    </form>
@endsection
