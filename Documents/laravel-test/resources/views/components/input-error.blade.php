@props(['messages' => []])

@if (!empty($messages))
    <div {{ $attributes->merge(['class' => 'text-red-600 text-sm mt-2']) }}>
        @if (is_array($messages) || $messages instanceof \Illuminate\Support\MessageBag)
            @foreach ($messages as $message)
                <p>{{ $message }}</p>
            @endforeach
        @elseif ($messages)
            <p>{{ $messages }}</p>
        @endif
    </div>
@endif
