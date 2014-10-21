/*
 Watch files or folder changes
 Replacement for node.js's fs.watch
 INSTALL
    npm install chokidar

 API

    chokidar.watch(paths, options): takes paths to be watched recursively and options:
        options.ignored (regexp or function) files to be ignored.
            This function or regexp is tested against the whole path, not just filename. If it is a function with two arguments, it gets called twice per path - once with a single argument (the path), second time with two arguments (the path and the fs.Stats object of that path).
        options.persistent (default: false).
            Indicates whether the process should continue to run as long as files are being watched.
        options.ignorePermissionErrors (default: false).
            Indicates whether to watch files that don't have read permissions.
        options.ignoreInitial (default: false).
            Indicates whether chokidar should ignore the initial add events or not.
        options.interval (default: 100).
            Interval of file system polling.
        options.binaryInterval (default: 300).
            Interval of file system polling for binary files (see extensions in src/is-binary).
        options.usePolling (default: false on Linux and Windows, true on OS X).
            Whether to use fs.watchFile (backed by polling), or fs.watch. If polling leads to high CPU utilization, consider setting this to false.

    chokidar.watch() produces an instance of FSWatcher. Methods of FSWatcher:

        .add(file / files):
            Add directories / files for tracking. Takes an array of strings (file paths) or just one path.
        .on(event, callback):
            Listen for an FS event. Available events: add, change, unlink, error. Additionally all is available which gets emitted for every add, change and unlink.
        .close():
            Removes all listeners from watched files.


 A RETENIR
    . utilisateur doit être root mysql pour load data infile
    . faire attention à bien mettre les bonnes quotes dans le load data infile

    */

// ,connection = mysql.createConnection('mysql://root:root@127.0.0.1/bambou');

var chokidar = require('chokidar')
    ,config = require('./config')
    ,mysql = require('mysql')
    ,fs = require('fs')
    ,connection = mysql.createConnection({
        host     : config.db_host,
        database : config.db_name,
        user     : config.db_user,
        password : config.db_pass
    });

// instancier un folder-file watcher
var watcher = chokidar.watch(config.upload_folder_hosts, { ignored: /[\/\\]\./, persistent: true });

// instancier une connexion à la base
connection.connect(function(err) {
    if (err) {
        console.error('error import connection: ' + err.stack);
        return;
    }
    console.log('Connected for importing hosts as id ' + connection.threadId);
});

// détails du watcher
watcher.on('add', function(ress) {

    console.log('File', ress, 'has been added');
    console.log('-------------------------------');

    //--------------------------------------------------------------------------------------
    // positionner le watcher sur le fichier importé
    // créer la requête
    var ext = config.ressource.extname(ress);


    console.log(ext, '-------------------------------');

    if(ext=='.csvok') {

        var my_table = config.ressource.basename(ress, ext);
        var queryCT = 'CREATE TABLE IF NOT EXISTS `'+my_table+'` (id int(10) NOT NULL AUTO_INCREMENT, civilite varchar(8) COLLATE utf8_general_ci NOT NULL, nom varchar(50) COLLATE utf8_general_ci NOT NULL, prenom varchar(50) COLLATE utf8_general_ci NOT NULL, fonction varchar(100) COLLATE utf8_general_ci DEFAULT NULL, branche varchar(100) COLLATE utf8_general_ci DEFAULT NULL, bu varchar(100) COLLATE utf8_general_ci DEFAULT NULL, societe varchar(100) COLLATE utf8_general_ci NOT NULL, adresse varchar(150) COLLATE utf8_general_ci DEFAULT NULL, code_postal varchar(20) COLLATE utf8_general_ci DEFAULT NULL, ville varchar(200) COLLATE utf8_general_ci DEFAULT NULL, pays varchar(50) COLLATE utf8_general_ci DEFAULT NULL, tel_fixe varchar(30) COLLATE utf8_general_ci DEFAULT NULL, tel_portable varchar(30) COLLATE utf8_general_ci DEFAULT NULL, adresse_mail varchar(100) COLLATE utf8_general_ci NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY (`adresse_mail`)) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

        console.log('Table', my_table, ' waiting to be created');
        console.log('Table Name length : ', my_table.length);
        console.log('-------------------------------');

        // créer une table du même nom que le fichier importé
        var iq1 = connection.query(queryCT);
        iq1.on('error', function(err) {
            throw err;
        });

        //--------------------------------------------------------------------------------------
        // créer la requete d'insertion de masse
        var queryLD = "LOAD DATA INFILE ? IGNORE INTO TABLE ?? FIELDS TERMINATED BY ';'";

        // insérer les données dans la nouvelle table
        // et supprimer le fichier une fois l'import effectué
        var iq2 = connection.query(queryLD, [ress, my_table])
        iq2
            .on('error', function(err) {
                console.trace('fatal error: ' + err.message);
            })
            .on('end', function() {
                console.log('Table ', my_table, ' created');
                console.log('File loaded into table ', my_table);
                fs.unlink(ress, function (err) {
                if (err) throw err;
                    console.log('Deleting file ', my_table,ext, '...');
            });

            //--------------------------------------------------------------------------------------
            // traitement de la liste de contact
            // recupérer les variables à partir du nom de la table
            var t = my_table                    // ex : g1e100t1404710305
                ,arr = t.match(/(.)(\d+)/g)     // g: guest
                ,status = arr[0].charAt(0)
                ,uid = arr[0].substr(1)         // 1: uid de l'utilisateur qui a importé le fichier
                ,eid = arr[1].substr(1)         // 100: id de l'evenement
                ,time = arr[2].substr(1)        // t: time // 1404710305: timestamp de l'import
                ,ret;                           // process return

            // var hosts = require('./h');
            // console.log('Processing hosts list...');
            // hosts.process(my_table, uid, eid);

        });

    }

})
.on('addDir', function(ress) {
    console.log('Directory', ress, 'has been added');
    console.log('-------------------------------');
})
.on('change', function(ress) {
    console.log('File', ress, 'has been changed');
    console.log('-------------------------------');
})
.on('unlink', function(ress) {
    console.log('File', ress, 'has been removed');
    console.log('-------------------------------');
})
.on('unlinkDir', function(ress) {
    console.log('Directory', ress, 'has been removed');
    console.log('-------------------------------');
})
.on('error', function(error) {
    console.error('Error happened', error);
    console.log('-------------------------------');
});

// Only needed if watching is persistent.
// watcher.close();
