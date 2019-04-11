var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var debug = require('debug')('SmartCar:sockets');
var request = require('request');
var port = process.env.PORT || '3000';

// Sender will user_id and receiver will provider_id
server.listen(port);

io.on('connection', function (socket) {

    debug('new connection established');
    debug('socket.handshake.query.sender', socket.handshake.query.sender);

    socket.join(socket.handshake.query.sender);

    socket.emit('connected', 'Connection to server established!');

    socket.on('update sender', function(data) {
        console.log('update sender', data);
        socket.join(data.sender);
        socket.handshake.query.sender=data.sender;
        socket.emit('sender updated', 'Sender Updated ID:'+data.sender);
    });


    socket.on('send location', function(data) {
        console.log("send location",data);
        data.sender = socket.handshake.query.sender;
        data.time = new Date();
        socket.broadcast.to(data.receiver).emit('message', data);
        // console.log(socket.broadcast.to(data.receiver).emit('message', data));

        // request('http://139.59.35.215/location_update_trip?sender='+data.sender+'&receiver='+data.receiver+'&latitude='+data.latitude+'&longitude='+data.longitude+'&status='+data.status+'&request_id='+data.request_id, function (error, response, body) {
        //     console.log('http://139.59.35.215/location_update_trip?sender='+data.sender+'&receiver='+data.receiver+'&latitude='+data.latitude+'&longitude='+data.longitude+'&status='+data.status+'&request_id='+data.request_id);
        //     console.log(body)
            // if (error && response.statusCode != 200) {
            //     console.log(body) // Show the HTML for the Google homepage.
            // }
        // });
    });

    socket.on('disconnect', function(data) {
        debug('disconnect', data);
    });
});



io.on('connection', function (socket) {

    debug('new connection for message established');
    debug('socket.handshake.query.sender', socket.handshake.query.sender);

    socket.join(socket.handshake.query.sender);

    socket.emit('connected', 'Connection to server established!');

    socket.on('update sender message', function(data) {
        console.log('update sender message', data);
        socket.join(data.sender);
        socket.handshake.query.sender=data.sender;
        socket.emit('sender updated', 'Sender Updated ID:'+data.sender);
    });

    socket.on('send message', function(data) {
        console.log("Send message",data);
        data.sender = socket.handshake.query.sender;
        data.time = new Date();
        socket.broadcast.to(data.receiver).emit('message', data);
        // console.log(socket.broadcast.to(data.receiver).emit('message', data));

        // request('http://139.59.35.215/location_update_trip?sender='+data.sender+'&receiver='+data.receiver+'&latitude='+data.latitude+'&longitude='+data.longitude+'&status='+data.status+'&request_id='+data.request_id, function (error, response, body) {
        //     console.log('http://139.59.35.215/location_update_trip?sender='+data.sender+'&receiver='+data.receiver+'&latitude='+data.latitude+'&longitude='+data.longitude+'&status='+data.status+'&request_id='+data.request_id);
        //     console.log(body)
            // if (error && response.statusCode != 200) {
            //     console.log(body) // Show the HTML for the Google homepage.
            // }
        // });
    });

    socket.on('disconnect', function(data) {
        debug('disconnect', data);
    });
});
