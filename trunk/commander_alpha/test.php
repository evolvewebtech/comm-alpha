<link rel="stylesheet" href="media/css/mosaic.css" type="text/css" media="screen" />
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />
<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>
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
<?php
/*
require_once dirname(__FILE__).'/manager/HTTPSession.php';
$objSession = new HTTPSession();
    
if ($objSession->IsLoggedIn()){


            $objUser = $objSession->GetUserObject();
        $gestore = $objUser[0];
    if(get_class($objUser[0]) == 'Gestore') {

            //$ret_addCassiere = $gestore->addCassiere('NULL', 'NULL', 'utente','passProva', 'nome1', 'nome2', 'C', 1);
            $ret_aggiornaCassiere = $gestore->editCassiere(1, 'al','827ccb0eea8a706c4c34a16891f84e7b', 'Ale', 'Sarzina', 'C', 3);
            if($ret_aggiornaCassiere){
                echo "TUTTI I CASSIERI:";
                echo "<pre>";
                print_r($gestore->getAllCassiere());
                echo "</pre>";
            }else{
                echo "merda";
            }

        }//gestore
        else{
            echo "<h4>Non possiedi i permessi necessari per visualizzare questa pagina.
                Contatta l'amministratore.</h4>";
        }
    }//isLoggedin
    else {
       echo '<h4 style="margin-left: 10px;">Sessione scaduta o autenticazione errata.
                <br /><a style="color:#fff;" href="logout.php"> <-- LOGIN</a>
            </h4>';
    }
*/
?>