/**
 * This file is part of Nikola Project
 * Author : Saikat Dutta(saikatdutta1991@gmail.com)
 * Company : PrevueLogic
 * 
 * installtion guide :
    npm install express
    npm install fs
    npm install socket.io
    npm install mysql
*
*/

process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";
process.env.TZ = 'UTC' // setting timezone to utc



/** storing db configuration */
var dbConfig = {
    host: 'localhost',
    user: 'root',
    password: 'sudharsan@123',
    database: 'tron',
    connectionLimit: 100,
    waitForConnections: true,
    queueLimit: 0,
    debug: true,
    wait_timeout: 28800,
    connect_timeout: 10,
    timezone: 'UTC',
    dateStrings: [
        'DATE',
        'DATETIME'
    ]
}
/** storing db configuration end*/

/** storing socket server configuration */
var socketConfig = {
    socket_port: 5000,
    is_https: false,
    https_key_path: '',
    https_cert_path: '',
    https_ca_path: '',
    php_server_host: 'http://46.101.106.16/'
}
/** storing socket server configuration end */



/** importing required node modules */
var request = require('request');
var app = require('express')();
var mysql = require('mysql');
var fs = require('fs');
var socketio = require('socket.io');
/** importing required node modules end */




/** configuring socket server based on http or https(ssl)*/
let server = null;
if (socketConfig.is_https) {

    console.log('Socket https enabled')

    var socketOptions = {
        key: fs.readFileSync(socketConfig.socket_port.https_key_path),
        cert: fs.readFileSync(socketConfig.socket_port.https_cert_path),
        ca: fs.readFileSync(socketConfig.socket_port.https_ca_path),
        rejectUnauthorized: false,
        requestCert: false
    };

    server = require('https').createServer(socketOptions, app);

} else {

    console.log('Socket http enabled')

    server = require('http').Server(app);
}

/** configuring socket server end */





/** starting socket server, listening on port defined in config */
server.listen(socketConfig.socket_port, function () {
    console.log('Server started on port : ' + socketConfig.socket_port);
});

//adding socket io to server
let io = socketio(server);


/** starting socket server end*/



/** default route */
app.get('/', function (req, res) {
    res.send('Socket server is running, You are not authorized to access this server.')
})
/** default route end*/



/** initializing mysql db connection */
var conn = mysql.createPool(dbConfig);

conn.on('connection', function (connection) {
    console.log('MYSQL : pool connection established')
    connection.query("SET time_zone='+00:00';", (error) => {
        console.log('Failed to set mysql connection timezone to UTC')
    })
});

conn.on('enqueue', function () {
    console.log('Waiting for available connection slot');
});

conn.on('release', function (connection) {
    console.log('MYSQL : po0l connection %d released', connection.threadId);
});

/** initializing mysql db connection end */


/** helper functions */

function currentUTCTimestampString() {
    var date = new Date();
    var year = date.getUTCFullYear().toString().padStart(2, 0)
    var month = (date.getUTCMonth() + 1).toString().padStart(2, 0)
    var day = date.getUTCDate().toString().padStart(2, 0)
    var hours = (date.getUTCHours() + 1).toString().padStart(2, 0)
    var minutes = (date.getUTCMinutes() + 1).toString().padStart(2, 0)
    var seconds = (date.getUTCSeconds() + 1).toString().padStart(2, 0)
    var now_utc = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`
    return now_utc
}


/** helper functions end*/








/** socket io program starts here */
io.on('connection', (socket) => {

    /** some client connected socket socket and initializing client details
     * if any error happens here disconnect the client
    */
    try {
        console.log('New client connected to socket')
        socket.entity = {
            id: socket.handshake.query.id, // this is user or provider id
            type: socket.handshake.query.type, // this is user or provider,
            room: `${socket.handshake.query.type}_${socket.handshake.query.id}`
        }

        // join the entity to room
        socket.join(socket.entity.room)
        socket.emit('connected', socket.entity)
        console.log('Socket client details : ', socket.entity)
    } catch (e) {
        console.log('Socket client conenct error', e.message)
        socket.disconnect();
    }




    /** sending message from user to provider */
    socket.on('send_message_to_provider', (data) => {
        console.log('send_message_to_provider event', data)

        /** insert messsag to db */
        var timestamp = currentUTCTimestampString()
        var type = 'up';
        var query = `INSERT INTO chat_messages (request_id, user_id, provider_id, message, type, delivered, created_at, updated_at) VALUES (${data.request_id}, ${socket.entity.id}, ${data.provider_id}, '${data.message}', '${type}', 1, '${timestamp}', '${timestamp}')`
        //var query = `INSERT INTO chat_messages (request_id, user_id, provider_id, message, type, delivered, created_at, updated_at) VALUES (${data.request_id}, ${socket.entity.id}, ${data.provider_id}, '${data.message}', '${type}', 1, '2018-09-29 09:56:44', '2018-09-29 09:56:44')`
        console.log('query', query)

        conn.query(query, (err, result) => {

            if (err) {
                console.log('message not inserted', err.message)
                return
            }

            console.log('message inserted to db', result.insertId)

            //send message to provider after modifiing data
            data.id = result.insertId
            data.created_at = timestamp
            data.updated_at = timestamp
            data.type = type
            console.log('room', `provider_${data.provider_id}`)
            io.sockets.in(`provider_${data.provider_id}`).emit('new_message_from_user', data);


            request(socketConfig.php_server_host + '/send_push?isuser=2&user_id=' + data.provider_id + '&request_id=' + data.request_id + '&title=New Message&message=' + data.message, { json: true }, (err, res, body) => {
                /* if (err) { return console.log(err); }
                console.log(body.url);
                console.log(body.explanation); */
            });



        })



    })
    /** sending message from user to provider end*/





    /** sending messag from provider to user */
    socket.on('send_message_to_user', (data) => {
        console.log('send_message_to_user event', data)

        /** insert messsag to db */
        var timestamp = currentUTCTimestampString()
        var type = 'pu';
        var query = `INSERT INTO chat_messages (request_id, user_id, provider_id, message, type, delivered, created_at, updated_at) VALUES (${data.request_id}, ${data.user_id}, ${socket.entity.id}, '${data.message}', '${type}', 1, '${timestamp}', '${timestamp}')`
        //var query = `INSERT INTO chat_messages (request_id, user_id, provider_id, message, type, delivered, created_at, updated_at) VALUES (${data.request_id}, ${socket.entity.id}, ${data.user_id}, '${data.message}', '${type}', 1, '2018-09-29 09:56:44', '2018-09-29 09:56:44')`
        console.log('query', query)

        conn.query(query, (err, result) => {

            if (err) {
                console.log('message not inserted', err.message)
                return
            }

            console.log('message inserted to db', result.insertId)

            //send message to provider after modifiing data
            data.id = result.insertId
            data.created_at = timestamp
            data.updated_at = timestamp
            data.type = type
            io.sockets.in(`user_${data.user_id}`).emit('new_message_from_provider', data);


            request(socketConfig.php_server_host + '/send_push?isuser=1&user_id=' + data.user_id + '&request_id=' + data.request_id + '&title=New Message&message=' + data.message, { json: true }, (err, res, body) => {
                /* if (err) { return console.log(err); }
                console.log(body.url);
                console.log(body.explanation); */
            });

        })



    })
    /** sending messag from provider to user end */



    socket.join(socket.handshake.query.sender);

    socket.emit('connected', 'Connection to server established!');

    socket.on('update sender', function (data) {
        //console.log('update sender', data);
        socket.join(data.sender);
        socket.handshake.query.sender = data.sender;
        socket.emit('sender updated', 'Sender Updated ID:' + data.sender);
    });

    socket.on('send location', function (data) {
        //console.log("send location", data);
        //data.sender = socket.handshake.query.sender;
        data.time = new Date();
        socket.broadcast.to(data.receiver).emit('message', data);
    });






})
/** socket io program starts here end */
