<!DOCTYPE html>
<html>
<head>
    <title>jQueryMobile</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    
    <link rel="stylesheet" href="css/jquery.mobile-1.0.1.min.css"/>
    <link rel="stylesheet" href="css/jquery.mobile.datebox-1.0.1.min.css"/>
    <script src="js/jquery.js"></script>
    <script src="js/jquery.mobile-1.0.1.min.js"></script>
    <script src="js/jquery.mobile.datebox-1.0.1.min.js"></script>
    <script>
        //Dichiarazione variabili globali;
        var numTavolo = 0;
        var numCoperti = 0;
        var totale = 0;
        var contanti = 0;
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
    </script>
    <script type="text/javascript" src="page_show.js"></script>
    <script type="text/javascript" src="menu.js"></script>
    <script type="text/javascript" src="lista_ordini.js"></script>
</head>
<body>
    
    <!-- PAGINA HOME -->
    <div data-role="page" id="home">
        <div data-role="header">
            <h1>Scelta operazioni</h1>
        </div>
        <div data-role="content">
            <div class="scelta_op">
                <div class="button_opz" >
                    <a href="#tavoli"><img src="images/symbol_add.png"/></a>
                </div>
                <div class="button_opz">
                    <a href="#info-ordini"><img src="images/symbol_information.png"/></a>
                </div>
                <!--
                <a href="#tavoli" data-role="button" data-icon="home" class="ui-btn-right">Nuovo tavolo</a>
                <a data-role="button" data-icon="home" class="ui-btn-right">Info</a>
                <a data-role="button" data-icon="home" class="ui-btn-right">Ordini aperti</a> -->
            </div>    
        </div>
    </div>
    
    <!-- PAGINA INFO ORDINI -->
    <div data-role="page" id="info-ordini">
        <div data-role="header">
            <h1>Info ordini</h1>
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
    
    <!-- PAGINA RISTAMPA ORDINE -->
    <div data-role="page" id="ristampa-ordine">
        <div data-role="header">
            <h1>Riepilogo ordine</h1>
            <a href="#info-ordini" data-icon="arrow-l" class="ui-btn-left">Indietro</a>
        </div>
        <div data-role="content">
            <?php include dirname(__FILE__).'/pg_rist_ordine.php';  ?>          
        </div>
    </div>
    
    <!-- PAGINA APERTURA TAVOLO -->
    <div data-role="page" id="tavoli">
        <div data-role="header">
            <h1>Scelta tavolo</h1>
        </div>
        <div data-role="content">
            <div class="ui-grid-b">
                <div class="ui-block-a"></div>
        	<div class="ui-block-b">
                    <h1></h1>
                    <label for="text-num-t">Inserire il numero del tavolo:</label>
                    <input type="text" name="name" id="text-num-t" value="" placeholder="Numero tavolo" />
                    <h1></h1>
                    <form>
                        <label for="slider-0">Inserire il numero di coperti:</label>
                        <input type="range" name="slider" id="slider-0" value="1" min="0" max="50"  />
                    </form>
                    <h1></h1>
                    <a id="sel-table" href="#ordine" data-role="button" data-icon="grid" class="ui-btn-right ui-disabled">Inserimento ordine</a>
                    <a href="#home" data-role="button" data-icon="delete" class="ui-btn-right">Annulla ordine</a>
                </div>
                <div class="ui-block-c"></div>
            </div><!-- /grid-b -->
            <script>
                $("#text-num-t").live("change" , function() {
                    enDisButton();
                });
                
                $("#slider-0").live("change" , function() {
                    enDisButton();
                });
                
                function enDisButton () {
                    //Abilita/disabilita pulsante "Inserimento ordine"
                    if ((document.getElementById('text-num-t').value == "") | (document.getElementById('slider-0').value <= 0) ) {
                        $('#sel-table').addClass('ui-disabled');
                    }
                    else $('#sel-table').removeClass('ui-disabled');
                }
            </script>
        </div>
    </div>
    
    <!-- PAGINA ORDINE -->
    <div data-role="page" id="ordine">
        <div data-role="header">
            <h1>Ordinazione</h1>
            <a href="#tavoli" data-icon="arrow-l" class="ui-btn-left">Indietro</a>
            <link rel="stylesheet" href="css/style.css" />
        </div>
        <div data-role="content">          
            <?php include dirname(__FILE__).'/pg_ordine.php';  ?>
        </div>
    </div>
    
    <!-- PAGINA CONFERMA E CHIUSURA -->
    <div data-role="page" id="chiusura">
        <div data-role="header">
            <h1>Chisura ordine</h1>
            <a href="#ordine" data-icon="arrow-l" class="ui-btn-left">Indietro</a>
        </div>
        <div data-role="content"> 
            <?php include dirname(__FILE__).'/pg_chiusura.php';  ?>
        </div>
    </div>
    
    <!-- PAGINA BUONI PREPAGATI -->
    <div data-role="page" id="buoni-pre">
        <div data-role="header">
            <h1>Buoni prepagati</h1>
            <a href="#chiusura" data-icon="arrow-l" class="ui-btn-left">Indietro</a>
        </div>
        <div data-role="content"> 
            <?php include dirname(__FILE__).'/pg_buoni.php';  ?>
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
    
    <!-- DIALOG INSERIMENTO CONTANTI -->
    <div data-role="page" id="diag-ins-cont">
        <div data-role="header">
            <h1>Pagamento</h1>
        </div>
        <div data-role="content">
            <?php include dirname(__FILE__).'/pg_diag_ins_cont.php';  ?>
        </div>
    </div>
</body>
</html>