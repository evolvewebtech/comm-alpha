<!DOCTYPE html>
<html>
<head>
    <title>Comander</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    
    <link rel="stylesheet" href="css/jquery.mobile-1.0.1.min.css"/>
    <style>.ui-mobile body{height:0%}</style> <!-- Per rimuovere la barra nera in basso -->
    <link rel="stylesheet" href="css/jquery.mobile.datebox-1.0.1.min.css"/>
    <style>
        .modalWindow{
            width: 96%;
            height: 95%;
            position: absolute;
            z-index: 1500;
            background: white;
            opacity: 0.7;
        }
        .ui-loader{
            z-index: 1501;
        }
    </style>
<!--    <script src="js/jquery.js"></script>-->
    <script src="../isotope/js/jquery-1.7.1.min.js"></script>
    <script>
        //Initialization jquerymobile
        $(document).bind("mobileinit", function(){
            $.extend(  $.mobile , {
                defaultPageTransition: 'none',
                defaultDialogTransition: 'none'
            }); 
        });
    </script>
    <script src="js/jquery.mobile-1.0.1.min.js"></script>
    <script src="js/jquery.mobile.datebox-1.0.1.min.js"></script>
    <script>
        var idTavolo = 0;
        var numTavolo = 0;
        var numCoperti = 0;
        var totale = 0;
        var contanti = 0;
        var sconto = 0;
        var scontato = 0;
        var arrAlim = new Array();
        var arrMenu = new Array();
        var arrMenuSel = new Array();
        var arrList = new Array();
        var show_opt = false;
        var mem_index = -1;
        var mem_ord_type = "cat";
        var usa_buono = false;
        var buono_ser = "";
        var buono_nom = "";
        var buono_cred = 0;
        var buono_cred_us = 0;
        var refreshAlim = false;
        var ann_voci = false;
        var refreshTab = true;
        var livelli = new Array();
        var sconti = new Array();
        var cassa_fissa = false;
        var sale = new Array();
        var firstUpdate = true;
    </script>
    <script type="text/javascript" src="../lib/contrast_color.js"></script>
    <script type="text/javascript" src="../lib/formato_data.js"></script>
    <script type="text/javascript" src="../lib/accounting.js"></script>
    <script type="text/javascript" src="menu.js"></script>
    <script type="text/javascript" src="lista_ordini.js"></script>
    <script type="text/javascript" src="page_show.js"></script>
    
    <link rel="stylesheet" href="css/style-isotope.css"/>
    <link rel="stylesheet" href="css/comm_checkbox.css" />
    <script type="text/javascript">
        //Altezza div list-ord
        $("#ordine").live('pageshow', function() {
            if ($(window).width() <= 980) {
                $('#view-menu').hide();
                $('#view-list').show();
                $('#comm-a').hide();
                $('#comm-b').show();
            }
            else {
                $('#view-menu').hide();
                $('#view-list').hide();
                $('#comm-a').show();
                $('#comm-b').show();
                $('#container').show();
            }
        });
        
        var prevWidth = 0;
        
        $(function(){      
            document.location.href="#home";
            
            $(window).resize(function(){
                if ($(window).width() <= 980) {
                    if (prevWidth != $(window).width() ) {
                        prevWidth = $(window).width();
                        $('#view-menu').hide();
                        $('#view-list').show();
                        $('#comm-a').hide();
                        $('#comm-b').show();
                    }
                }
                else {
                    if (prevWidth != $(window).width() ) {
                        prevWidth = $(window).width();
                        $('#view-menu').hide();
                        $('#view-list').hide();
                        $('#comm-a').show();
                        $('#comm-b').show();
                        $('#container').show();
                    }
                }
            });
        }); 
                 
        // Edit to suit your needs.
        var ADAPT_CONFIG = {
        // Where is your CSS?
        path: 'css/',

        // false = Only run once, when page first loads.
        // true = Change on window resize and page tilt.
        dynamic: true,

        // Optional callback... myCallback(i, width)
        //callback: myCallback,

        // First range entry is the minimum.
        // Last range entry is the maximum.
        // Separate ranges by "to" keyword.
        range: [
            '0px  to 980px  = style-comm-800.css',
            '980px  to 1600px = style-comm.css'
        ]
        };        
    </script>
    <script type="text/javascript" src="../lib/adapt.min.js"></script>
    
    <!-- Hide address bar in mobile-web-app -->
    <script type="text/javascript">
        function hideAddressBar(){
        if(document.documentElement.scrollHeight<window.outerHeight/window.devicePixelRatio)
            document.documentElement.style.height=(window.outerHeight/window.devicePixelRatio)+'px';
            //setTimeout(window.scrollTo(1,1),0);
        }
        window.addEventListener("load",function(){hideAddressBar();});
        window.addEventListener("orientationchange",function(){hideAddressBar();});
    </script>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    
    <!-- DA TESTARE -->
    <meta content='True' name='HandheldFriendly' />
    <meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' />
    <meta name="viewport" content="width=device-width, user-scalable=no" />

    <script type="text/javascript">
        function logged(err) {
            //Verifica se utente loggato
            if ((err == 'E001') || (err == 'E002')) {
                //utente non loggato correttamente 
                var str = '';
                if (err == 'E002') str = 'Utente non autenticato o sessione scaduta';
                else str = 'Non possiedi i permessi per visualizzare questa pagina!';
                console.log(err + ': ' + str);
                document.getElementById('log-err-text').innerHTML = str;
                //apertura pagina avviso
                document.location.href="#diag-log-err";
                return false;
            }
            else return true;
        }
    </script>
    
    <script>
        function showModal(){
            $("body").append('<div class="modalWindow"/>');
            //$.mobile.showPageLoadingMsg();
        }

        function hideModal(){
            $(".modalWindow").remove();
            //$.mobile.hidePageLoadingMsg();
        }
    </script>

    
</head>
<body>
    
    <!-- PAGINA HOME -->
    <div data-role="page" id="home">
        <div data-role="header">
            <h1 style="margin-top: 17px">Scelta operazioni</h1>
            <div id="user01" style="float: right; margin-top: -34px; margin-right: 90px"></div>
            <a id="logoutBt" data-icon="delete" class="ui-btn-right">Esci</a> 
        </div>
        <div data-role="content"> 
            <?php include dirname(__FILE__).'/pg_home.php';  ?>   
        </div>
    </div>
    
    <!-- PAGINA INFO ORDINI -->
    <div data-role="page" id="info-ordini">
        <div data-role="header">
            <h1 style="margin-top: 17px">Info ordini</h1>
            <a href="#home" data-icon="arrow-l" class="ui-btn-left">Indietro</a>
        </div>
        <div data-role="content">
            <div class="lista-date">
                <label for="ord-datepicker">Selezionare una data:</label>
                <input name="ord-datepicker" id="ord-datepicker" type="date" data-role="datebox" data-options='{"mode": "calbox"}'>
            </div>     
            <div id="lista-vecchi-ordini" class="lista_ordini"></div>           
        </div>
    </div>
    
    <!-- PAGINA VECCHIO ORDINE -->
    <div data-role="page" id="ristampa-ordine">
        <div data-role="header">
            <h1 style="margin-top: 17px">Riepilogo ordine</h1>
            <a href="#info-ordini" data-icon="arrow-l" class="ui-btn-left">Indietro</a>
        </div>
        <div data-role="content">
            <?php include dirname(__FILE__).'/pg_vecchio_ordine.php';  ?>          
        </div>
    </div>
    
    <!-- PAGINA APERTURA TAVOLO -->
    <div data-role="page" id="tavoli">
        <div data-role="header">
            <h1 style="margin-top: 17px">Scelta tavolo</h1>
        </div>
        <div data-role="content">
            <?php include dirname(__FILE__).'/pg_ap_tavolo.php';  ?>
        </div>
    </div>
    
    <!-- PAGINA ORDINE -->
    <div data-role="page" id="ordine">
        <div data-role="header">
            <h1 style="margin-top: 17px">Ordinazione</h1>
            <a id="pg-ord-back" href="#tavoli" data-icon="arrow-l" class="ui-btn-left">Torna ai tavoli</a>
            <div id="ord01" style="float: right; margin-top: -34px; margin-right: 20px"></div>
        </div>
        <div data-role="content">          
            <?php include dirname(__FILE__).'/pg_ordine.php';  ?>
        </div>
    </div>
    
    <!-- PAGINA CONFERMA E CHIUSURA -->
    <div data-role="page" id="chiusura">
        <div data-role="header">
            <h1 style="margin-top: 17px">Chisura ordine</h1>
            <a href="#ordine" data-icon="arrow-l" class="ui-btn-left">Torna a ordinazione</a>
            <a href="#diag-conf-canc-ord2" data-icon="delete" data-rel="dialog" class="ui-btn-right">Annulla ordine</a>
        </div>
        <div data-role="content"> 
            <?php include dirname(__FILE__).'/pg_chiusura.php';  ?>
        </div>
    </div>
    
    <!-- PAGINA BUONI PREPAGATI -->
    <div data-role="page" id="buoni-pre">
        <div data-role="header">
            <h1 style="margin-top: 17px">Buoni prepagati</h1>
            <a href="#chiusura" data-icon="arrow-l" class="ui-btn-left">Indietro</a>
        </div>
        <div data-role="content"> 
            <?php include dirname(__FILE__).'/pg_buoni.php';  ?>
        </div>
    </div>
    
    <!-- DIALOG VARIANTI MENU FISSO -->
    <div data-role="page" id="diag-var-menu">
        <div data-role="header" data-theme="d">
            <h1>Selezionare le varianti</h1>
        </div>
        <div data-role="content">
            <div id="var-menu-cont"></div>
            <a href="#ordine" data-role="button" data-icon="arrow-l" class="ui-btn-right">Indietro</a>
        </div>
    </div>
    
    <!-- DIALOG CONFERMA CANCELLA TUTTO -->
    <div data-role="page" id="diag-conf-canc-all">
        <div data-role="header">
            <h1>Cancellare tutto?</h1>
        </div>
        <div data-role="content">
            <a href="#ordine" data-role="button" data-icon="check" class="ui-btn-right canc-all-conf">Cancella tutto</a>
            <a href="#ordine" data-role="button" data-icon="delete" class="ui-btn-right canc-ann">Annulla</a>
        </div>
    </div>
    
    <!-- DIALOG CONFERMA CANCELLA ORDINE -->
    <div data-role="page" id="diag-conf-canc-ord">
        <div data-role="header">
            <h1>Annullare l'ordine?</h1>
        </div>
        <div data-role="content">
            <a id="canc-ord" href="#home" data-role="button" data-icon="check" class="ui-btn-right canc-all-conf">Sì</a>
            <a href="#ordine" data-role="button" data-icon="delete" class="ui-btn-right canc-ann">No</a>
        </div>
    </div>
    
    <!-- DIALOG CONFERMA CANCELLA ORDINE 2 -->
    <div data-role="page" id="diag-conf-canc-ord2">
        <div data-role="header">
            <h1>Annullare l'ordine?</h1>
        </div>
        <div data-role="content">
            <a id="canc-ord" href="#home" data-role="button" data-icon="check" class="ui-btn-right canc-all-conf">Sì</a>
            <a href="#chiusura" data-role="button" data-icon="delete" class="ui-btn-right canc-ann">No</a>
        </div>
    </div>
    
    <!-- DIALOG SCELTA SCONTO -->
    <div data-role="page" id="diag-sconto">
        <div data-role="header">
            <h1>Sconto</h1>
        </div>
        <div data-role="content">
            <div style="padding-left: 80px; padding-right: 80px; margin-bottom: 15px">
                <div id="diag-sconto-text" style="margin-left: -80px">Utente non abilitato per sconto</div>
                <div id="diag-sconto-bt"></div>
            </div>
            <a href="#chiusura" data-role="button" data-icon="arrow-l" class="ui-btn-right canc-ann">Indietro</a>
        </div>
    </div>
    
    <!-- DIALOG INSERIMENTO CONTANTI -->
    <div data-role="page" id="diag-ins-cont">
        <div data-role="header">
            <h1>Pagamento</h1>
        </div>
        <div data-role="content">
            <?php include dirname(__FILE__).'/pg_diag_ins_cont.php';  ?>
        </div>
    </div>
    
    <!-- DIALOG CONFERMA INVIO ORDINE -->
    <div data-role="page" id="diag-conf-ord">
        <div data-role="header">
            <h1>Confermare la stampa dell'ordine?</h1>
        </div>
        <div data-role="content">
            <a id="conferma-ordine" data-role="button" data-icon="check" class="ui-btn-right">Sì</a>
            <a href="#chiusura" data-role="button" data-icon="delete" class="ui-btn-right">No</a>
            <div id="debug-sim-01"></div>
        </div>
    </div>
    
    <!-- DIALOG UTENTE NON LOGGATO -->
    <div data-role="page" id="diag-log-err">
        <div data-role="header">
            <h1>Verifica utente</h1>
        </div>
        <div data-role="content">
            <h3 id="log-err-text">Non possiedi i permessi per visualizzare questa pagina!</h3>
            <a id="diag-log-back" data-role="button" data-icon="alert" class="ui-btn-right">Indietro</a>
        </div>
    </div>
    
    <script type="text/javascript">
        $('#logoutBt').live("click", function() { logout() });
        $('#diag-log-back').live("click", function() { logout() });
        
        function logout() {
            document.location.href="../logout.php";
        }
    </script>  
</body>
</html>