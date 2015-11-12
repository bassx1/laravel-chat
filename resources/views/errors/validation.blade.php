@if($errors->all())

    @foreach($errors->all() as $error)

        <p>{{ $error }}</p>

    @endforeach

@endif