@if(Auth::user()->isAdmin())

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Создать комнату
    </button>
    <br>
    <br>

@endif