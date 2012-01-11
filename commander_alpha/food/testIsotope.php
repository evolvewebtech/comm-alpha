<html>
    <head>
        <title>Titolo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="testIsotope.css">
    </head>
        
    <body>
        <div id="container">
            <div id="intestazione">Prova Isotope</div>
            
                <section id="options" class="clearfix">
                    <div class="option-combo">
                    <h3>Filter:</h3>
                    <ul id="filter" class="option-set clearfix" data-option-key="filter">
                    <li><a href="#show-all" data-option-value="*" class="selected">show all</a></li>
                    <li><a href="#elements" data-option-value=".element:not(.feature)">elements</a></li>
                    <li><a href="#features" data-option-value=".feature">features</a></li>
                    <li><a href="#examples" data-option-value=".example">examples</a></li>
                    </ul>
                    </div>
                </section>
            
                <div id="items">
                    <div class="item">a</div>
                    <div class="item">b</div>
                    <div class="item">c</div>
                </div>

            
            <script>
                $('#container').isotope({ filter: '.my-selector' }, function( $items ) {
                    var id = this.attr('id'),
                    len = $items.length;
                    console.log( 'Isotope has filtered for ' + len + ' items in #' + id );
                });
            </script>    
            
            
            <?php
            
            echo "<form NAME=ordine ACTION=".$PHP_SELF.">"; ?>
                
            
            </form>
            
            <div id="conto">
            <?php
                $pr = $_GET['primo'];
                $sec = $_GET['secondo'];
            ?>
            </conto>
            
        </div>
    </body>
</html>
