var express = require('express');
var http = require('http');
var qs = require('querystring');
var mysql = require('mysql');
var elasticsearch = require('elasticsearch');
var request = require('request')


http.createServer(function(req, res){

  fields = req.headers.cookie.split("=")
  sessionID = fields[1];

  request("http://localhost/isAuthenticated?sessionId=" + sessionID, function(error, response, body){
    if (error) throw error;
    if (response.statusCode == 200){
      var connection = mysql.createConnection({
        host : 'skitterdb.cw8xyevide6f.us-west-2.rds.amazonaws.com',
        user : 'Skitter',
        password : 'CSEC380_project',
        database: 'accounts'
      });

      connection.query("SELECT id from user WHERE sessionID = '" + sessionID + '\';', function(err, rows){
        if (err) throw err;
        var id = rows[0].id;
        console.log(id);
        if (req.method == "POST"){
          var body = '';
          req.on('data', function (data){
            body += data;
          })
          req.on('end', function(){
            var POST = qs.parse(body);
            var skit = POST.skit;
            var client = elasticsearch.Client({
              host: "search-skitter-es-vylzjmu2cn4nsl2ncugfhfskku.us-west-2.es.amazonaws.com"
            });

            client.index({
              index: "testdb",
              type: "names",
              body: {
                user: id,
                message: skit,
                timestamp: Date(),
              }
            });
          })

        res.end();
        }
      })
    }
  })
}).listen(8081, '127.0.0.1');
