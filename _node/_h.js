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
    // console.log('IMPORTED: ', imported);
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
        var data = [
          emails[i],
          'Yp9tzgZs6t4=', // default password
          3,
          uid
        ];

        console.log('----',data,'----');

        var qx = connection_host.query('SELECT * FROM users WHERE email = ?', [emails[i]]);
        qx.on('error', function(err) {
          throw err;
        })
        .on('result', function(host) {
          var q2 = connection_host.query('INSERT IGNORE INTO users (email, password, level, creatorUid) VALUES (?)', [data]);
          q2.on('error', function(err) {
            throw err;
          })
          .on('result', function(user) {
            // récupération des infos des utilisateurs
            hostID = user.insertId;
            console.log('HOSTID = ',hostID);
            console.log('EVENTID = ',eid);
            var q3 = connection_host.query('SELECT * FROM users WHERE uid = ?', [hostID]);
            q3.on('error', function(err) {
              throw err;
            })
            .on('result', function(host) {
              // création du profil
              var profile = [
                hostID,
                imp[host.email]['civilite'],
                imp[host.email]['nom'],
                imp[host.email]['prenom']
              ];
              var q4 = connection_host.query('INSERT IGNORE INTO userProfile (userID,civilite, nom, prenom) VALUES (?)', [profile]);
              q4.on('error', function(err) {
                throw err;
              })
              .on('result', function(new_profiles) {
                c_profs++;
              })
              .on('end', function() {});
              // création des infos professionnelles
              var jobinfos = [
                hostID,
                imp[host.email]['fonction']||null,
                imp[host.email]['branche']||null,
                imp[host.email]['societe'],
                imp[host.email]['tel_fixe']||null,
                imp[host.email]['tel_portable']||null,
                imp[host.email]['adresse']||null,
                imp[host.email]['code_postal']||null,
                imp[host.email]['ville']||null,
                imp[host.email]['pays']||'France'
              ];
              var q5 = connection_host.query('INSERT IGNORE INTO userJobinfos (userID, fonction, branche, societe, fixe, portable, adresse, cp, ville, pays) VALUES (?)', [jobinfos]);
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
                hostID
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
                hostID,
                eid
              ];
              var q7 = connection_host.query('INSERT INTO eventhosts (hostID, eventID) VALUES (?) ON DUPLICATE KEY UPDATE   eventhosts_index=concat(eventID,hostID)', [event_host]);
              q7.on('error', function(err) {
                throw err;
              })
              .on('result', function() {})
              .on('end', function() {});
            })
            .on('end', function() {

            });
          })
          .on('end', function() {

          });
        })
        .on('end', function(host) {
          console.log(host, 'no data');
        });
      }
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
  });
};