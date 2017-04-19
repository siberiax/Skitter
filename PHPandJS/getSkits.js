var mysql = require('mysql');
var elasticsearch = require('elasticsearch');
var connection = mysql.createConnection({
  host : 'skitterdb.cw8xyevide6f.us-west-2.rds.amazonaws.com',
  user : 'Skitter',
  password : 'CSEC380_project',
  database: 'accounts'
});

connection.connect(function(err){
  if (err){
    console.log(err)
  }
});

connection.query("SELECT following_id from follower WHERE follower_id = 1;", function(err, rows){
  if (err) throw err;
  var client = elasticsearch.Client({
    host: "search-skitter-es-vylzjmu2cn4nsl2ncugfhfskku.us-west-2.es.amazonaws.com"
  });
  for (var i = 0; i < rows.length; i++){
    client.search({
      index: 'testdb',
      type: 'names',
      body: {
        query : {
          match: { "user" : rows[i].following_id }
        },
      }
    }, function(error, response, status){
      if (error){
        console.log("search error: " + error);
      } else {
        console.log(response.hits.hits)
      }
    })
  }
})

connection.end(function(err){
  if (err){
    console.log("error ending connection");
  }
});
