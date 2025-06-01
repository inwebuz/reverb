@extends('layout')

@section('content')
    <h1 class="text-2xl font-bold mb-5">Chat with {{ $with->name }}</h1>
    <div class="my-4">
        <a href="{{ route('dashboard') }}" class="py-1 px-3 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">Back to dashboard</a>
    </div>
    <div class="mb-4">
        @foreach ($messages->reverse() as $message)
            <div class="mb-2">
                <small>{{ $message->created_at->format('Y-m-d H:i:s') }}</small> <strong>{{ $message->user->name }}</strong>: <br>
                {{ $message->content }}
            </div>
        @endforeach
    </div>
    <div>
        <form action="{{ route('chats.send', [$chat->id]) }}" method="post">
            @csrf
            <div>
                <input class="w-full border" type="text" name="message" id="message" required>
            </div>
            <div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Send</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let id = {{ $chat->id }};
            Echo.private(`chats.${id}`)
                .listen('ChatMessageSent', (e) => {
                    console.log('Message received:', e);
                });
        });
    </script>
@endpush
