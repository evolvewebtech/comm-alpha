<!DOCTYPE html>
<html>
<head>
    <title>jQueryMobile</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    
    <link rel="stylesheet" href="css/jquery.mobile-1.0.1.min.css"/>
    <script src="js/jquery.js"></script>
    <script src="js/jquery.mobile-1.0.1.min.js"></script>
    <script type="text/javascript" src="categorie.js"></script>
</head>
<body>
    
    <!-- PAGINA HOME -->
    <div data-role="page" id="home">
        <div data-role="header">
            <h1>Scelta operazioni</h1>
        </div>
        <div data-role="content">
            <div class="scelta_op">
                <div class="button_opz">
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
            <p>Questa è la pagina dei tavoli</p>
            <a href="#ordine" data-role="button" data-icon="grid" class="ui-btn-right">Ordine</a>
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
            <a href="#canc-all" data-role="button" data-icon="home" class="ui-btn-right">Cancella tutto</a>
            <a href="#ordine" data-role="button" data-icon="home" class="ui-btn-right">Annulla</a>
        </div>
    </div>
</body>
</html>