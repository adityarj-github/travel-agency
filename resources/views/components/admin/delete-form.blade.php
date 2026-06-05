@props(['action', 'label' => 'Delete', 'message' => 'Are you sure you want to delete this item? This cannot be undone.'])

<form method="POST" action="{{ $action }}" onsubmit="return confirm('{{ $message }}')" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'text-red-600 hover:text-red-800']) }}>
        {{ $label }}
    </button>
</form>
