@extends('layouts.admin')

@section('title', 'Edit Staff')
@section('page_title', 'Edit Staff Member')
@section('breadcrumb', 'Staff / Edit')

@section('content')
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('admin.users._form', ['submitLabel' => 'Update Staff Member'])
    </form>
@endsection
