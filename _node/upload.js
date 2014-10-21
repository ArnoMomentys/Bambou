// upload manager with formidable, expressJs...
var formidable = require('formidable'),
http = require('http'),
util = require('util'),
fs   = require('fs-extra');

http.createServer(function(req, res) {
  /* Process the form uploads */
  if (req.url == '/' && req.method.toLowerCase() == 'post') {
    var form = new formidable.IncomingForm();
    form.parse(req, function(err, fields, files) {
      res.writeHead(200, {'content-type': 'text/plain'});
      res.write('received upload:\n\n');
      res.end(util.inspect({fields: fields, files: files}));
  });

    form.on('progress', function(bytesReceived, bytesExpected) {
      var percent_complete = (bytesReceived / bytesExpected) * 100;
      console.log(percent_complete.toFixed(2));
  });

    form.on('error', function(err) {
      console.error(err);
  });

    form.on('end', function(fields, files) {
      /* Temporary location of our uploaded file */
      var temp_path = this.openedFiles[0].path;
      /* The file name of the uploaded file */
      var file_name = this.openedFiles[0].name;
      /* Location where we want to copy the uploaded file */
      var new_location = '../_www/tmp/uploads/';

      fs.copy(temp_path, new_location + file_name, function(err) {  
        if (err) {
                // voir l'action à faire en cas d'erreur
                // peut-être juste afficher une erreur type
                console.error(err);
            } else {
                // tout le process dans importController
                console.log("success!")
            }
        });
  });

    return;
}

/* Display the file upload form. */
res.writeHead(200, {'content-type': 'text/html'});
res.end(
    '<form action="/" enctype="multipart/form-data" method="post">'+
    '<input type="text" name="title"><br>'+
    '<input type="file" name="upload" multiple="multiple"><br>'+
    '<input type="submit" value="Upload">'+
    '</form>'
    );

}).listen(3000);
