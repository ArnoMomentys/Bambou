/**
 * Process host import
 */

/**
 * include database param connexion
 */
var config = require('./config')
    ,mysql = require('mysql')
    ,connection_host = mysql.createConnection({
        host     : config.db_host,
        database : config.db_name,
        user     : config.db_user,
        password : config.db_pass
    });
    // ,connection_host = mysql.createConnection('mysql://root:root@127.0.0.1/bambou');

// instancier une connexion à la base
connection_host.connect(function(err) {
    if (err) {
        console.error('error user processing connection: ' + err.stack);
        return;
    }
    console.log('Connected for processing users as id ' + connection_host.threadId);
});

// recupération des nouvelles infos de profil
exports.process = function(my_table, uid, eid) {
  var imp = []
      ,emails = []
      ,users = []
      ,c_imp = 0
      ,c_profs = 0
      ,c_jobs = 0
      ,c_contacts = 0
      ,c_invit = 0
      ,c_invithost = 0
      ,hostID;
  //--------------------------------------------------------------------------------------
  // recupération de la liste des contacts importés
  var q1 = connection_host.query('SELECT * FROM ??', [my_table]);
  q1.on('error', function(err) {
    throw err;
  })
  .on('result', function(imported) {
    console.log('-------------------------------');
    console.log('IMPORTED: ', imported);
    console.log('hosts: ', imported.nom, ' - ', imported.adresse_mail);
    emails[emails.length] = imported.adresse_mail;
    imp[imported.adresse_mail] = imported;
    c_imp++;
  })
  .on('end', function() {

    console.log('-------------------------------');
    console.log('[',c_imp,'] contacts to add');
    console.log('-------------------------------');
    //--------------------------------------------------------------------------------------
    // creation de la liste des utilisateurs à traiter
    for (var i in emails) {
      var hostID;
      // insertion de chaque utilisateur dans la table users
      if(emails[i] !== undefined) {
        var current_data = emails[i];
        var current_uid = '';
        var data = [
          emails[i],
          'Yp9tzgZs6t4=', // default password
          3,
          uid
        ];

        console.log('----',data,'----');

        // var qx = connection_host.query('SELECT * FROM users WHERE email = ?', [emails[i]]);
        // qx.on('error', function(err) {
        //   throw err;
        // })
        // .on('result', function(host) {

        // })
        // .on('end', function(host) {
        //   console.log(host, 'no data');
        // });

        var q2 = connection_host.query('INSERT IGNORE INTO users (email, password, level, creatorUid) VALUES (?)', [data]);
        q2.on('error', function(err) {
          throw err;
        })
        .on('result', function(user) {
            // récupération des infos des utilisateurs
            current_uid = user.insertId;
            console.log('HOSTID = ',current_uid);
            console.log('EVENTID = ',eid);

            var q3 = connection_host.query('SELECT * FROM users WHERE uid = ?', [current_uid]);
            q3.on('error', function(err) {
                throw err;
            })
            .on('result', function(host) {

                console.log('JE PASSE PAR ICI ', current_uid);

                // si j'ai les datas
                // création du profil
                var profile = [
                  current_uid,
                  imp[current_data]['civilite'],
                  imp[current_data]['nom'],
                  imp[current_data]['prenom']
                ];
                var q4 = connection_host.query('INSERT IGNORE INTO userprofile (userID,civilite, nom, prenom) VALUES (?)', [profile]);
                q4.on('error', function(err) {
                  throw err;
                })
                .on('result', function(new_profiles) {
                  c_profs++;
                })
                .on('end', function() {});

                // création des infos professionnelles
                var jobinfos = [
                  current_uid,
                  imp[current_data]['fonction']||null,
                  imp[current_data]['branche']||null,
                  imp[current_data]['societe'],
                  imp[current_data]['tel_fixe']||null,
                  imp[current_data]['tel_portable']||null,
                  imp[current_data]['adresse']||null,
                  imp[current_data]['code_postal']||null,
                  imp[current_data]['ville']||null,
                  imp[current_data]['pays']||'France'
                ];
                var q5 = connection_host.query('INSERT IGNORE INTO userjobinfos (userID, fonction, branche, societe, fixe, portable, adresse, cp, ville, pays) VALUES (?)', [jobinfos]);
                    q5.on('error', function(err) {
                      throw err;
                    })
                    .on('result', function(new_jobinfos) {
                      c_jobs++;
                    })
                    .on('end', function() {});

                // création du contact invité - invitant
                var contact = [
                  uid,
                  current_uid
                ];
                var q6 = connection_host.query('INSERT IGNORE INTO usercontacts (hostID, contactID) VALUES (?)', [contact]);
                    q6.on('error', function(err) {
                      throw err;
                    })
                    .on('result', function(new_jobinfos) {
                      c_contacts++;
                    })
                    .on('end', function() {});


                // création du event host
                var event_host = [
                  current_uid,
                  eid
                ];
                var q7 = connection_host.query('INSERT INTO eventhosts (hostID, eventID) VALUES (?) ON DUPLICATE KEY UPDATE eventhosts_index=concat(eventID,hostID)', [event_host]);
                    q7.on('error', function(err) {
                      throw err;
                    })
                    .on('result', function() {})
                    .on('end', function() {});


            })
            .on('end', function() {});
        })
        .on('end', function() { });


      }

        if( i == emails.length-1 ) {
            var del = connection_host.query('DROP TABLE '+my_table);
            del.on('error', function(err) {
              throw err;
            })
            .on('end', function() {
              console.log('Table ',my_table,' deleted...');
              console.log('-------------------------------');
              console.log('-------------------------------');
            });
        }

    }

  });
};