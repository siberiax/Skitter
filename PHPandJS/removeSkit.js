var elasticsearch = require('elasticsearch');

var client = elasticsearch.Client({
  host: "search-skitter-es-vylzjmu2cn4nsl2ncugfhfskku.us-west-2.es.amazonaws.com"
});

client.index({
  index: "testdb",
  type: "names",
  id: some_id
})
