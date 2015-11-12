var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');
var redisMessenger = new Redis;
var redisRooms = new Redis;




redisRooms.subscribe('rooms');
var rooms = [];
redisRooms.on('message', function(channel, message){
    var room = JSON.parse(message).room;
    redisMessenger.subscribe(room);
});

var users = [];
io.sockets.on('connection', function (socket) {
    socket.on("register", function(user) {
        users[user.id] = socket.id;
    });

    socket.on('delete', function(msg){
        io.emit('messageHasBeenDeleted', msg.id);
    });

    socket.on('deleteRoom', function(roomId){
        io.emit('roomHasBeenDeleted', roomId);
    });

    socket.on('createRoom', function(room){
        io.emit('roomHasBeenCreated', room);
    });

});


redisMessenger.on('message', function(channel, message){

    message = JSON.parse(message);
    if(!!message.to){
        io.to(users[message.user_id]).emit(channel, message);
        io.to(users[message.to]).emit(channel, message);
    } else {
        io.emit(channel, message);
    }
});

server.listen(3000);