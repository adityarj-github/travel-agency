@extends('layouts.admin')

@section('title', 'Staff Users')
@section('page_title', 'Staff & Roles')
@section('breadcrumb', 'Manage admin panel users and their roles')

@section('page_actions')
    <a href="{{ route('admin.users.create') }}" class="btn-primary">+ Add Staff</a>
@endsection

@section('content')
    <div class="mb-5 grid gap-3 sm:grid-cols-3">
        @php
            $roleHints = [
                'Editor' => 'Manages content: packages, destinations, blogs, sliders, gallery, testimonials.',
                'Manager' => 'Everything an editor can do, plus bookings, coupons and inquiries.',
                'Admin' => 'Full access including settings and staff management.',
            ];
        @endphp
        @foreach ($roleHints as $role => $hint)
            <div class="rounded-xl border border-slate-200 bg-white p-4">
                <p class="text-sm font-semibold text-slate-800">{{ $role }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
            </div>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Added</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 font-semibold text-brand-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <span class="font-medium text-slate-800">{{ $user->name }}
                                        @if ($user->id === auth()->id())<span class="text-xs text-slate-400">(you)</span>@endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $user->role_label }}</span>
                            </td>
                            <td class="px-5 py-3 text-xs text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-brand-600 hover:underline">Edit</a>
                                    @if ($user->id !== auth()->id())
                                        <x-admin.delete-form :action="route('admin.users.destroy', $user)" message="Delete this staff member?" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No staff users yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $users->links() }}</div>
@endsection
