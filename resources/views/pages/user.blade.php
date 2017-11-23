@extends('master')

@section("title", "User")

@section("style")

    <link rel="stylesheet" href="{{ URL::to('css/confetti.css') }}" />
      
@endsection

@section("script")
    <script src="{{ URL::to('js/confetti.js') }}"></script>
@endsection

@section("content")
<div class='container'>
    
    <canvas id="world"></canvas>
    @if(isset($name) && $name !== '')
        
        <h3>Name</h1>
        <p>{{ $name }}</p>

    @endif
    
    @if(isset($userId) &&  $userId !== '')
        <h3>USER ID</h1>
        <p>{{ $userId }}</p>
    @endif

    @if(isset($phone) && $phone !== '')

        <h3>Phone Number</h1>
        <p>{{ $phone }}</p>

    @endif

    @if(isset($email) && $email !== '')

        <h3>Email</h1>
        <p>{{ $email }}</p>

    @endif

    <p>
        <small class="form-text text-muted">This is a one time page. Go back and log in again to see it again.</small>
    </p>

    <p>
        <a href="{{ route('home') }}" class='btn btn-danger'> GO BACK </a>
    </p>
</div>


@endsection