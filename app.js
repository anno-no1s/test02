const express = require('express');
const app = express();

app.use(express.static('public'));

app.set('views', __dirname + '/views');
app.set('view engine', 'ejs');

app.get('/', function(req, res){
  res.render('index', {title : 'Running Mario!'});
});

app.listen(3000);
console.log('server starting...');
