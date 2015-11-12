@if(Auth::user()->isAdmin())

    <a href="javascript:void(0)" class="remove remove-msg" v-on:click="remove(msg)">
        <i class="fa fa-close"></i>
    </a>

@endif