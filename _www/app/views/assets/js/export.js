/* jQuery ajax function
 * post using the comment form & get back response from server & display on page.
 */

/* anonymous func */
$(function()
{
    /* capture the form submit event */
    $(".form-export").submit(function(event)
    {
        /* afficher un loader */
        var spin = $(this).find('.spinner');
        spin.css({'visibility': 'visible'});

        /* stop form from submitting normally */
        event.preventDefault();

        /* setup post function vars */
        var url = $(this).attr('action');
        var postdata = $(this).serialize();

        /* send the data using post and put the results in a div with id="result" */
        /* post(url, postcontent, callback, datatype returned) */
        var request = $.post(
            url,
            postdata,
            formpostcompleted,
            "json"
        ); // end post function

        /* convert json results to csv file */
        function formpostcompleted(data, status)
        {
            JSONToCSVConvertor(data, true);
        }
     }); // end submit function
});


function JSONToCSVConvertor(JSONData, ShowLabel) {

    var jsonData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData,
        arrData = typeof jsonData.results != 'object' ? JSON.parse(jsonData.results) : jsonData.results,
        CSV = '';

    /* Insertion de la ligne d'entête du csv */
    if (ShowLabel) {

        var row = "";

        /* 1ere boucle pour l'extraction du label de la première colonne de chaque élement csv */
        for (var index in arrData[0]) {
            /* conversion de chaque valeur en chaîne de caractère et ajout du séparateur de colonnes */
            row += ';' + index;
        }

        row = row.slice(0, -1);

        /* ajout d'un saut de ligne en fin de ligne */
        CSV += row + '\r\n';
    }

    /* première boucle pour extraire chaque ligne */
    for (var i = 0; i < arrData.length; i++) {

        var row = "";

        /* 2e boucle pour extraire chaque colonne et conversion de chaque valeur en chaîne de caractère et ajout du séparateur de colonnes */
        for (var index in arrData[i]) {
            row += ';"' + arrData[i][index] + '"';
        }

        row.slice(0, row.length - 1);

        /* ajout d'un saut de ligne en fin de ligne */
        CSV += row + '\r\n';
    }

    if (CSV == '') {
        alert("Données Invalides");
        return;
    }

    var reqType = [];
    reqType['host'] = 'invitants';
    reqType['guest'] = 'invites';

    /* générer un nom de fichier */
    var fileName = "Export_"+reqType[JSONData.params._type]+"_event_"+JSONData.params.eid;

    /* Initialisation du format csv ou xls */
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);

    // Now the little tricky part.
    // you can use either>> window.open(uri);
    // but this will not work in some browsers
    // or you will not get the correct file extension

    /* Identification du bouton de fermeture de la modal */
    var close = $("#myModal-export"+JSONData.params._type+"s-"+JSONData.params.eid);
    var spin = $("#form-export"+JSONData.params._type+"s-"+JSONData.params.eid).find('.spinner');

    /* génération d'un lien html temporaire */
    var link = document.createElement("a");
    link.href = uri;

    /* cacher le lien temporaire */
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";

    /* ajout du lien temporaire dans le html, clic et suppression auto */
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    /* cacher le loader */
    spin.css({'visibility': 'hidden'});

    /* fermeture de la modal */
    $(close).find('button.close').click();

}