@if(Auth::user()->isAdmin())

    <a href="javascript:void(0)" class="remove remove-room" v-on:click="removeRoom(_room.id)">
        <i class="fa fa-remove"></i>
    </a>

@endif