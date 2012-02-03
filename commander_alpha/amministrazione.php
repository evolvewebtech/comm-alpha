<?php
require_once dirname(__FILE__).'/manager/HTTPSession.php';

$objSession = new HTTPSession();

$sess_id = $objSession->GetSessionIdentifier();
$log_in = $objSession->IsLoggedIn();
$utente_registrato = $objSession->__get('UTENTE_REGISTRATO_ID');

?>
<!--
-->
<link rel="stylesheet" href="media/css/mosaic.css" type="text/css" media="screen" />
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />
<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="media/js/mosaic.js"></script>

<script type="text/javascript">
        jQuery(function($){

                $('.circle').mosaic({
                        opacity		:	0.8		//Opacity for overlay (0-1)
                });

                $('.fade').mosaic();

                $('.bar').mosaic({
                        animation	:	'slide'		//fade or slide
                });

                $('.bar2').mosaic({
                        animation	:	'slide'		//fade or slide
                });

                $('.bar3').mosaic({
                        animation	:	'slide',	//fade or slide
                        anchor_y	:	'top'		//Vertical anchor position
                });

                $('.cover').mosaic({
                        animation	:	'slide',	//fade or slide
                        hover_x		:	'400px'		//Horizontal position on hover
                });

                $('.cover2').mosaic({
                        animation	:	'slide',	//fade or slide
                        anchor_y	:	'top',		//Vertical anchor position
                        hover_y		:	'80px'		//Vertical position on hover
                });

                $('.cover3').mosaic({
                        animation	:	'slide',	//fade or slide
                        hover_x		:	'400px',	//Horizontal position on hover
                        hover_y		:	'300px'		//Vertical position on hover
                });

    });

</script>
<style type="text/css">
    /*Demo Styles*/
    body{ background:#e9e8e4 url('img/bg-black.png'); }
    #content{ width:920px; margin:20px auto; padding:10px 30px; }
    .clearfix{ display: block; height: 0; clear: both; visibility: hidden; }
    .details{ margin:15px 20px; }	
    h4{ font:300 16px 'Helvetica Neue', Helvetica, Arial, sans-serif;
        line-height:160%; letter-spacing:0.15em; color:#fff;
        text-shadow:1px 1px 0 rgb(0,0,0); }
    p{ font:300 12px 'Lucida Grande', Tahoma, Verdana, sans-serif;
       color:#aaa; text-shadow:1px 1px 0 rgb(0,0,0);}
    a{ text-decoration:none; }

</style>
<div id="content">
    <?php
    if($objSession->IsLoggedIn()){
        $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
        if(get_class($gestore) == 'Gestore') {
    ?>
        <!--Fade-->
        <div class="mosaic-block fade">
            <a href="test.php" class="mosaic-overlay">
                    <div class="details">
                            <h4>CONFIGURA IL RISTORANTE</h4>
                            <p>Crea le sale, aggiungi i tavoli e rinominali!</p>
                    </div>
            </a>
            <div style="background-color:#fff" class="mosaic-backdrop">
                <img alt="ristorante" src="img/restaurant.png"/>
            </div>
        </div>

        <!--Bar-->
        <div class="mosaic-block bar">
            <a href="#" target="_blank" class="mosaic-overlay">
                    <div class="details">
                            <h4>GESTISCI I CASSIERI</h4>
                            <p>Aggiungi nuovi cassieri al tuo ristorante,
                                modifica i dati di un cassiere gi&agrave;
                                esistente o elimanane l'account.</p>
                    </div>
            </a>
            <div style="background-color:#fff" class="mosaic-backdrop">
                <img src="img/waiter.png"/>
            </div>
        </div>

        <!--Bar 2-->
        <div class="mosaic-block bar2">
            <a href="#" target="_blank" class="mosaic-overlay">
                <div class="details">
                        <h4>GESTISCI GLI ALIMENTI</h4><br/>
                        <p>
                            Iserisci tutte le categorie (primi - secondi - contorni...),
                            iserisci nuovi alimentie ed iserisci le varianti ai tuoi piatti!
                        </p>
                </div>
            </a>
            <div style="background-color:#fff" class="mosaic-backdrop">
                <img src="img/food.png"/>
            </div>
        </div>

        <!--Bar 3-->
        <div class="mosaic-block bar3">
            <a href="#" target="_blank" class="mosaic-overlay">
                    <div class="details">
                            <h4>GESTISCI I MENU</h4>
                            <p>Crea e personalizza i tuoi men&ugrave.</p>
                    </div>
            </a>
            <div style="background-color:#fff" class="mosaic-backdrop">
                <img style="padding:60px"src="img/menu.png"/>
            </div>
        </div>

        <!--Cover-->
        <div class="mosaic-block cover">
            <div class="mosaic-overlay" style="background-color:#fff">
                <img src="img/printer.png"/>
            </div>
            <a href="#" target="_blank" class="mosaic-backdrop">
                <div class="details">
                        <h4>GESTISCI LE STAMPANTI</h4>
                        <p>Aggiungi nuove stampanti e associale alle casse del tuo ristorante!</p>
                </div>
            </a>
        </div>

        <!--Cover 2-->
        <div class="mosaic-block cover2">
            <div style="background-color:#fff" class="mosaic-overlay"><img src="img/stat.png"/></div>
            <a href="#" target="_blank" class="mosaic-backdrop">
                    <div class="details">
                            <h4>STATISTICHE</h4>
                            <p>Qui trovi tutte le informazioni che ti interessano.</p>
                    </div>
            </a>
        </div>

        <div class="clearfix"></div>
        <h4 style="margin-left: 10px;">
            <a style="color:#fff;" href="logout.php">esci</a> |
            <a style="color:#fff;" href="license.php">credit</a>
        </h4>
</div>

<!--
<br />ISLOGGEDIN:[ <?//= var_dump($log_in) ?> ]
<br />SESSION ID: [ <?//= $sess_id ?> ]
<br />variabile di sessione UTENTE_REGISTRATO_ID: [ <?//= $utente_registrato ?> ]
<br />variabile di sessione RUOLO: [ <?//= $objSession->RUOLO ?> ]
-->
<?php
            //prova sala
            /*
            //$ret_addSala = $gestore->addSala('NULL','sala343','NULL');
            if ($ret_addSala){
                echo "<p>Sala aggiunta correttamente!</p>";
            }
            $allSala = $gestore->getAllSala();
            if ($allSala){
                echo "<br />TUTTE LE SALE: <pre>";
                echo print_r($allSala);
                echo "</pre>";
            }

            //$ret_addCassiere = $gestore->addCassiere('NULL', 'NULL', 'utenteProva',
              'passProva', 'nome1', 'nome2', 'G', 1);
            echo "<pre>";
            print_r($gestore->getAllCassiere());
            echo "</pre>";
            
            $ret_aggiornaCassiere = $gestore->editCassiere(10, 'piero',
               '827ccb0eea8a706c4c34a16891f84e7b', 'piero', 'po', 'C', 3);
            if($ret_aggiornaCassiere){
                echo "TUTTI I CASSIERI:";
                echo "<pre>";
                print_r($gestore->getAllCassiere());
                echo "</pre>";
            }

            $el = $gestore->delCassiere(18);
            var_dump($el);
             * 
             */

            //prova tavoli
            /*
            echo "<br />aggiungo tavolo... ";
            $addT = $gestore->addTavolo('NULL','A1',1,4,'NULL',32);
            var_dump($addT);

            echo "<br />get tavolo... ";
            $getT = $gestore->getTavolo(2);
            var_dump($getT);

            echo "<br />modifico tavolo... ";
            $editT = $gestore->editTavolo(3,'A2',2,8,'NULL',31);
            var_dump($editT);
            
            echo "<br />TAVOLI:<pre>";
            print_r($gestore->getAllTavolo());
            echo "</pre>";
            
            echo "<br />Eliminazione tavolo... ";
            //$delT = $gestore->delTavolo(1);
            var_dump($delT);

            echo "<br />TAVOLI:<pre>";
            print_r($gestore->getAllTavolo());
            echo "</pre>";
            */

            //prova alimento
            /*
            echo "<br />aggiungo alimento... ";
            $addT = $gestore->addAlimento('NULL', 'pasta al pomodoro', 5, 0, 'yellow',
                                'tagliatelle al pomodoro', 0, 'images/', '00345F',
                                10000, 2, 1, 1);
            var_dump($addT);

            
            echo "<br />get Alimento... ";
            $getT = $gestore->getAlimento(18);
            var_dump($getT);

            echo "<br />modifico Alimento... ";
            $editT = $gestore->editAlimento(18, 'pasta al pesto', 4, 0, 'red',
                                'pennette al pesto', 0, 'images/', '00345G',
                                10000, 2, 1, 1);
            var_dump($editT);

            echo "<br />ALIMENTI:<pre>";
            print_r($gestore->getAllAlimento());
            echo "</pre>";
            
            echo "<br />Eliminazione Alimento... ";
            $delT = $gestore->delAlimento(18);
            var_dump($delT);
            */

        }//gestore
        else{
            echo "<h4>Non possiedi i permessi necessari per visualizzare questa pagina.
                Contatta l'amministratore.</h4>";
        }
    }//isLoggedin
    else {
       echo '<h4 style="margin-left: 10px;">Sessione scaduta o autenticazione errata.
                <a style="color:#fff;" href="logout.php"> --> LOGIN</a>
            </h4>';
    }
?>