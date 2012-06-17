
/*
 * PAGINA "HOME"
 */
$("#home").live('pageshow', function() {
    homePageShow();
});


/*
 * PAGINA "TAVOLI"
 */
$("#tavoli").live('pageshow', function() {
    tavoliPageShow();
});


/*
 * PAGINA "INFO ORDINI"
 */
$("#info-ordini").live('pageshow', function() {
    if (memPayload != '') setTimeout("viewResult(8)", 500);
});


/*
 * PAGINA "CHIUSURA"
 */
$("#chiusura").live('pageshow', function() {
    chiusuraPageShow();
});


/*
 * PAGINA-DIALOG "INSERIMENTO CONTANTI"
 */
$("#diag-ins-cont").live('pageshow', function() {
    document.getElementById('cont-ric').value = '';
});


/*
 * PAGINA "BUONI PREPAGATI"
 */
$("#buoni-pre").live('pageshow', function() {
    buoniprePageShow();
});
