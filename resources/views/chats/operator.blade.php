@extends('layout')

@section('content')
<div>
    <form action="{{ route('chat.send') }}" method="post">
        @csrf
        <div>
            <label for="message">Message</label>
            <input class="w-full border" type="text" name="message" id="message" required>
        </div>
        <div>
            <button type="submit">Send</button>
        </div>
    </form>
</div>
@endsection
