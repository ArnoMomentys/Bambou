/**
 * Process guest import
 */

/**
 * include database param connexion
 */
var config = require('./config')
    ,mysql = require('mysql')
    // ,connection_guest = mysql.createConnection({
    //     host     : config.db_host,
    //     database : config.db_name,
    //     user     : config.db_user,
    //     password : config.db_pass
    // });
    ,connection_guest = mysql.createConnection('mysql://root:root@127.0.0.1/bambou');


// instancier une connexion à la base
connection_guest.connect(function(err) {
    if (err) {
        console.error('error user processing connection: ' + err.stack);
        return;
    }
    console.log('Connected for processing users as id ' + connection_guest.threadId);
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
      ,c_invitguest = 0
      ,guestID;


    //--------------------------------------------------------------------------------------
    // recupération de la liste des contacts importés

    var q1 = connection_guest.query('SELECT * FROM ??', [my_table]);
    q1
      .on('error', function(err) {
        throw err;
      })
      .on('result', function(imported) {
        console.log('-------------------------------');
        // console.log('IMPORTED: ', imported);
        console.log('Guests: ', imported.nom, ' - ', imported.adresse_mail);
        var clean_mail = imported.adresse_mail.replace(/(\r\n|\n|\r)/gm,'');
        imported.adresse_mail = clean_mail;
        emails[emails.length] = clean_mail;
        imp[clean_mail] = imported;
        c_imp++;
      })
      .on('end', function() {
        console.log('-------------------------------');
        console.log('[',c_imp,'] contacts to add');
        console.log('-------------------------------');

        //--------------------------------------------------------------------------------------
        // creation de la liste des utilisateurs à traiter

        for (var i in emails) {

          var guestID;

          // insertion de chaque utilisateur dans la table users
          if(emails[i] !== null) {

              var data = [
                emails[i],
                'Yp9tzgZs6t4=', // default password
                4,
                uid
              ];

              var q2 = connection_guest.query('INSERT INTO users (email, password, level, creatorUid) VALUES (?) ON DUPLICATE KEY UPDATE email=?, uid=LAST_INSERT_ID(uid)', [data, emails[i]]);
              q2.on('error', function(err) {
                  throw err;
                })
                .on('result', function(user) {

                  // récupération des infos des utilisateurs
                  guestID = user.insertId;

                  var q3 = connection_guest.query('SELECT * FROM users WHERE uid = ?', [guestID]);
                  q3.on('error', function(err) {
                      throw err;
                    })
                    .on('result', function(guest) {

                      // création du profil
                      var profile = [
                        guest.uid,
                        imp[guest.email]['civilite'],
                        imp[guest.email]['nom'],
                        imp[guest.email]['prenom']
                      ];
                      var q4 = connection_guest.query('INSERT IGNORE INTO userProfile (userID,civilite, nom, prenom) VALUES (?)', [profile]);
                      q4.on('error', function(err) {
                          throw err;
                        })
                        .on('result', function(new_profiles) {
                          c_profs++;
                        })
                        .on('end', function() { });

                      // création des infos professionnelles
                      var jobinfos = [
                          guest.uid,
                          imp[guest.email]['fonction']||null,
                          imp[guest.email]['branche']||null,
                          imp[guest.email]['societe'],
                          imp[guest.email]['tel_fixe']||null,
                          imp[guest.email]['tel_portable']||null,
                          imp[guest.email]['adresse']||null,
                          imp[guest.email]['code_postal']||null,
                          imp[guest.email]['ville']||null,
                          imp[guest.email]['pays']||'France'
                      ];
                      var q5 = connection_guest.query('INSERT IGNORE INTO userJobinfos (userID, fonction, branche, societe, fixe, portable, adresse, cp, ville, pays) VALUES (?)', [jobinfos]);
                      q5.on('error', function(err) {
                              throw err;
                        })
                        .on('result', function(new_jobinfos) {
                          c_jobs++;
                        })
                        .on('end', function() { });

                      // création du contact invité - invitant
                      var contact = [
                          uid,
                          guest.uid
                      ];
                      var q6 = connection_guest.query('INSERT IGNORE INTO usercontacts (hostID, contactID) VALUES (?)', [contact]);
                      q6.on('error', function(err) {
                              throw err;
                        })
                        .on('result', function(new_jobinfos) {
                          c_contacts++;
                        })
                        .on('end', function() { });

                      // création de l'invitation
                      var invitation = [
                          guest.uid,
                          uid,
                          eid
                      ];
                      var q7 = connection_guest.query('INSERT IGNORE INTO invitations (guestID, hostID, eventID) VALUES (?)', [invitation]);
                      q7.on('error', function(err) {
                              throw err;
                        })
                        .on('result', function(new_invitation) {
                          c_invit++;

                          // création de la relation invitation - invité
                          if(new_invitation.insertId != 0||undefined||null) {

                            var invitationGuest = [
                                new_invitation.insertId
                            ];
                            var q8 = connection_guest.query('INSERT IGNORE INTO invitationGuests (invitationID) VALUES (?)', [invitationGuest]);
                            q8
                              .on('error', function(err) {
                                throw err;
                              })
                              .on('result', function(new_invitationGuest) {
                                c_invitguest++;
                              })
                              .on('end', function() { });
                          }

                        })
                        .on('end', function() { });

                    })
                    .on('end', function() { });

                })
                .on('end', function() { });

            }

        }

        var del = connection_guest.query('DROP TABLE '+my_table);
        del
          .on('error', function(err) {
            throw err;
          })
          .on('end', function() {
            console.log('Table ',my_table,' deleted...');
            console.log('-------------------------------');
            console.log('-------------------------------');
          });

      });

};