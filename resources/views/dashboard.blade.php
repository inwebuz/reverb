@extends('layout')

@section('content')
    <div>
        <div>User ID: {{ $user->id }}</div>
        @if ($user->isOperator())
            @foreach ($users as $user1)
                <div>
                    <form action="{{ route('chats.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="with" value="{{ $user1->id }}">
                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ $user1->name }}</button>
                        </div>
                    </form>
                </div>
            @endforeach
        @else
            <div>
                <form action="{{ route('chats.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="with" value="1">
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Chat with operator</button>
                    </div>
                </form>
            </div>
        @endif
        <div>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                @method('post')
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
@endsection
