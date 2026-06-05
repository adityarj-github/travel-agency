<div class="mx-auto max-w-2xl space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="form-input-base @error('name') border-red-400 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="form-input-base @error('email') border-red-400 @enderror">
                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input-base">
            </div>
            <div>
                <label class="form-label">Role <span class="text-red-500">*</span></label>
                <select name="role" class="form-input-base @error('role') border-red-400 @enderror">
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label class="form-label">Password @if($user->exists)<span class="text-slate-400">(leave blank to keep)</span>@else<span class="text-red-500">*</span>@endif</label>
                <input type="password" name="password" {{ $user->exists ? '' : 'required' }}
                       class="form-input-base @error('password') border-red-400 @enderror" placeholder="••••••••">
                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-input-base" placeholder="••••••••">
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admin.users.index') }}" class="btn-outline">Cancel</a>
    </div>
</div>
