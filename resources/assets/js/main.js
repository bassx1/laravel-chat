var Vue = require('vue');
var _ = require('underscore');

var socket = io('http://127.0.0.1:3000');

new Vue({
    el: "#chat",
    data:{
        messages: [],
        newMessage: "",
        token: document.querySelector('meta[role=token]').content,
        userId: null,
        rooms: [],
        room: "default_room",
        notifications: [],
        closeTriggers: document.querySelectorAll('.fout'),
        showPrivateMessages: 0
    },

    ready: function(){
        this.loadMessages();
        this.initListener();
        this.loadRooms();
        this.setUser();
    },

    methods: {
        sendMessage: function(e){
            e.preventDefault();
            if(this.newMessage == "") return;
            var that = this;

            $.post('/messages', {_token: this.token, room_id: this.room, message: this.newMessage })
                .done(function(){
                    that.newMessage = "";
                });
        },

        answer: function (msg) {
            this.newMessage = '#'+ msg.author.name + ', ';
            this.$els.input.focus();
        },

        initListener: function(){
            var that = this;

            socket.on(that.room, function(msg){
                if(msg.room_id == that.room){
                    that.messages.push(msg)
                } else if(msg.room != that.room && msg.to == that.user.id) {
                    that.notifications.push(msg);
                    that.initCloseAlertTriggers();
                }
                that.scrollBottom();
            });

            socket.on('messageHasBeenDeleted', function(msgId){
                var msg = _.findWhere(that.messages, {id: msgId});
                that.messages.$remove(msg);
            });

            socket.on('roomHasBeenDeleted', function(roomId){
                var room = _.findWhere(that.rooms, {id: roomId});
                that.rooms.$remove(room);
                if(that.room == roomId){
                    that.changeRoom(0);
                    that.notifications.push({
                        author: {name: 'System'},
                        message: 'К сожалению, комната в которой вы находитесь удалена. ' +
                                    'Вы перенаправлены в комнату по умолчанию'
                    });
                    that.initCloseAlertTriggers();
                }
            });

            socket.on('roomHasBeenCreated', function(room){
                that.rooms.push(room);
            })
        },

        loadMessages:function(){
            var that = this;
            $.ajax({
                url:'/messages',
                method: 'GET',
                data: { room: that.room, showPrivate: that.showPrivateMessages },
                cache:false
            }).done(function(messages){
                that.messages = messages;
            });
        },

        remove: function(msg){
            $.post('/messages/'+ msg.id, {_token: this.token, _method: 'DELETE'}).done(function(){
                socket.emit('delete', msg);
            });
        },


        loadRooms: function(){
            var that = this;
            $.ajax({
                url: '/rooms',
                method: 'GET'
            }).done(function(rooms){
                that.rooms = rooms;
            })
        },

        changeRoom: function(roomId){
            var that = this;
            this.room = roomId;
            that.loadMessages();
            $.post('/users/set_room', {_token: this.token, room: roomId}).done(function(){
                that.scrollBottom();
            })
        },

        removeRoom: function(roomId){
            var that = this;
            $.post('/rooms/' + roomId, {_token: this.token, _method:"DELETE"}).done(function(){
                socket.emit('deleteRoom', roomId);
            })
        },


        setUser: function(){
            var that = this;
            $.ajax({
                url: "/users/get_user",
                method: 'GET',
                cache:false
            }).done(function(user){
                that.user = user;
                socket.emit('register', user);
            });
        },


        scrollBottom: function(){
            var messageBox = this.$els.msgs;
            $(messageBox).animate({'scrollTop': messageBox.scrollHeight}, 'slow');
        },




        initCloseAlertTriggers: function(){
            setTimeout(function(){
                $('.fout').click(function(){
                    $(this).closest('.alert').fadeOut('fast');
                });
            },300);
        },

        showPrivate: function(){
            this.showPrivateMessages = 1;
            this.loadMessages();
        },

        hidePrivate: function(){
            this.showPrivateMessages = 0;
            this.loadMessages();
        },

        messageClass: function(msg){
            if(msg.to == this.user.id) return 'privateMsg';
            if(msg.user_id == this.user.id ) return 'myMsg';
        },


        createRoom: function(){
            var that = this;
            $.post('/rooms', {_token:this.token, title: this.$els.room.value}).done(function(room){
                socket.emit('createRoom', room);
                that.$els.room.value = '';
            })
        }


    },


});
