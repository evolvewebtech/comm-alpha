
$("#ordine").live('pageshow', function() {

        $.ajax({
            type : "GET",
            url: "categorie.php",
            dataType: 'json',
            cache: false,
            success: onEventoInfoSuccess,
            error: onEventoInfoError
        });
        return false;    
 });


function onEventoInfoSuccess(data, status) {
    //alert("Successo lettura da database con Ajax!")
    //alert(JSON.stringify(data));
}

function onEventoInfoError(data, status) {
    alert("Errore Ajax")
}
