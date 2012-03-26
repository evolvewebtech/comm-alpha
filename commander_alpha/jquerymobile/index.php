<!DOCTYPE html>
<html>
<head>
    <title>jQueryMobile</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    
    <link rel="stylesheet" href="css/jquery.mobile-1.0.1.min.css"/>
    <script src="js/jquery.js"></script>
    <script src="js/jquery.mobile-1.0.1.min.js"></script>
    <script>
        //Dichiarazione variabili globali;
        var numTavolo = 0;
        var numCoperti = 0;
        var arrAlim = new Array();
        var arrMenu = new Array();
        var arrMenuSel = new Array();
        var arrList = new Array();
        var show_opt = false;
        var mem_index = -1;
        var mem_ord_type = "cat";
    </script>
    <script type="text/javascript" src="menu.js"></script>z
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
                    <img src="images/symbol_information.png"/>
                </div>
                <!--
                <a href="#tavoli" data-role="button" data-icon="home" class="ui-btn-right">Nuovo tavolo</a>
                <a data-role="button" data-icon="home" class="ui-btn-right">Info</a>
                <a data-role="button" data-icon="home" class="ui-btn-right">Ordini aperti</a> -->
            </div>    
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
                    <label for="basic">Inserire il numero del tavolo:</label>
                    <input type="text" name="name" id="basic" value="" placeholder="Numero tavolo" />
                    <h1></h1>
                    <form>
                        <label for="slider-0">Inserire il numero di coperti:</label>
                        <input type="range" name="slider" id="slider-0" value="1" min="0" max="100"  />
                    </form>
                    <h1></h1>
                    <a id="sel-table" href="#ordine" data-role="button" data-icon="grid" class="ui-btn-right">Inserimento ordine</a>
                    <a href="#home" data-role="button" data-icon="delete" class="ui-btn-right">Annulla ordine</a>
                </div>
                <div class="ui-block-c"></div>
            </div><!-- /grid-b -->
        </div>
    </div>
    
    <!-- PAGINA ORDINE -->
    <div data-role="page" id="ordine">
        <div data-role="header">
            <h1>Ordinazione</h1>
            <link rel="stylesheet" href="css/style.css" />
        </div>
        <div data-role="content">          
            <div class="comm-a">
                <?php include dirname(__FILE__).'/lista_ordine.php';  ?>
            </div>
            <div class="comm-b">
                <?php include dirname(__FILE__).'/scelta_ordine.php';  ?>
            </div> 
        </div>
    </div>
    
    <!-- PAGINA CONFERMA E CHIUSURA -->
    <div data-role="page" id="chiusura">
        <div data-role="header">
            <h1>Chisura ordine</h1>
            <a href="#home" data-icon="home" class="ui-btn-right">home</a>
        </div>
        <div data-role="content">
            <p>Questa è la pagina di chiusura ordine</p>
            <a href="#home" data-role="button" data-icon="home" class="ui-btn-right">Chiudi ordine</a>
            <a href="#home" data-role="button" data-icon="home" class="ui-btn-right">Mantieni ordine aperto</a>
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
</body>
</html>