@if(Auth::user()->isAdmin())

    <a href="javascript:void(0)" v-on:click="showPrivate()" v-show="!showPrivateMessages">
        <i class="fa fa-eye text-success"></i>
        Показать приватные сообщения
    </a>
    <a href="javascript:void(0)" v-on:click="hidePrivate()" v-show="showPrivateMessages">
        <i class="fa fa-eye-slash text-danger"></i>
        Скрыть приватные сообщения
    </a>

@endif