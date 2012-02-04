<!DOCTYPE html>
<html>
<head>
    <title>jQueryMoblie</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/latest/jquery.mobile.min.css" />
    <script src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/latest/jquery.mobile.min.js"></script></a>
</head>
<body>
    
    <!-- PAGINA HOME -->
    <div data-role="page" id="home">
        <div data-role="header">
            <h1>Scelta operazioni</h1>
        </div>
        <div data-role="content">
            <p>Questa è la prima pagina</p>
            <a href="#tavoli" data-icon="home" class="ui-btn-right">Nuovo ordine</a>
        </div>
    </div>
    
    <!-- PAGINA APERTURA TAVOLO -->
    <div data-role="page" id="tavoli">
        <div data-role="header">
            <h1>Scelta tavolo</h1>
        </div>
        <div data-role="content">
            <p>Questa è la pagina dei tavoli</p>
            <a href="#ordine" data-icon="home" class="ui-btn-right">Ordine</a>
        </div>
    </div>
    
    <!-- PAGINA ORDINE -->
    <div data-role="page" id="ordine">
        <div data-role="header">
            <h1>Ordinazione</h1>
            <link rel="stylesheet" href="css/style.css" />
        </div>
        <div data-role="content">
            <p>Questa è la pagina dell'ordine</p>
            <a href="#chiusura" data-icon="home" class="ui-btn-right">Conferma ordine</a>
            <?php include dirname(__FILE__).'/scelta_ordine.php';  ?>
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
        </div>
    </div>
</body>
</html>