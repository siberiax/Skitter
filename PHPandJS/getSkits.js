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

      var query = "select id, displayName from user WHERE id in (select followingID from follow WHERE userID in (SELECT id from user WHERE sessionID = '" + sessionID + "\'));";
      console.log(query);
      connection.query(query, function(err, rows){
        if (err) throw err;
        var client = elasticsearch.Client({
          host: "search-skitter-es-vylzjmu2cn4nsl2ncugfhfskku.us-west-2.es.amazonaws.com"
        });
        var dictionary = {};
        for (var i = 0; i < rows.length; i++){
          dictionary[rows[i].id] = rows[i].displayName;
        }

        for (var i = 0; i < rows.length; i++){
          client.search({
            index: 'testdb',
            type: 'names',
            body: {
              query : {
                match: { "user" : rows[i].id }
              },
            }
          }, function(error, response, status){
            if (error){
              console.log("search error: " + error);
            } else {
              for (var j = 0; j < response.hits.hits.length; j++){
                var message = response.hits.hits[j]['_source']["message"];
                var displayname = dictionary[response.hits.hits[j]["_source"]["user"]];
                var timestamp = response.hits.hits[j]['_source']["timestamp"];
                var skitid = response.hits.hits[j]['_id'];
                console.log(message, displayname, timestamp, skitid);
              }
            }
          })
          res.end();
        }
      })
    }
  })
}).listen(8081, '127.0.0.1');
