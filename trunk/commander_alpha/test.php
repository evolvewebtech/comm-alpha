<?php
    require_once dirname(__FILE__).'/manager/HTTPSession.php';
    $objSession = new HTTPSession();
?>
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />
<style type="text/css">
    body{ color: #ffffff; }
    #content{ width:920px; margin:20px auto; padding:10px 30px; }
    .clearfix{ display: block; height: 0; clear: both; visibility: hidden; }
    .details{ margin:15px 20px; }
    h4{ font:300 16px 'Helvetica Neue', Helvetica, Arial, sans-serif;
        line-height:160%; letter-spacing:0.15em; color:#fff;
        text-shadow:1px 1px 0 rgb(0,0,0); }
    p{ font:300 12px 'Lucida Grande', Tahoma, Verdana, sans-serif;
       color:#aaa; text-shadow:1px 1px 0 rgb(0,0,0);}
    a{ text-decoration:none; }
    table{
        background-color: #fff;
        color: #000;
        border: #000 1px solid;
    }
    tr {
        
    }
    td {
        /*border: #000 1px solid;*/
        padding: 1px;
    }
    td.third {
        /*border: #000 1px solid;*/
        padding-left: 5px;
    }

    td.second {
        border-right: 2px #000 dotted;
        padding-right: 5px;
}
</style>
<div id="content" style="color:#fff;">
<?php

    if($objSession->IsLoggedIn()){

        $objUser = $objSession->GetUserObject();
        
        if(get_class($objUser[0]) == 'Gestore') {

            $gestore = $objUser[0];

            echo "<h2>cassa test</h2>";
            echo "<p>visualizza cassa: ";
            $ret = $gestore->visualizzaCassa(1);
            if($ret==0){
                echo "Nisba";
            }else{
                echo "saldo = " . $ret['saldo'];
                if ($ret['consegnato']==1){
                    echo "<br />Saldo gi&agrave; consegnato.";                    
                }
            }
            echo "</p>";


            echo "<p>azzera cassa: ";
            $ret = $gestore->azzeraCassa(1);
            if($ret==0){
                echo "Cassa gi&agrave; azzerata.";
            }else{
                echo "saldo consegnato = " . $ret['saldo'];
                if ($ret['consegnato']==1){
                    echo "<br />Cassa azzerata con successo.";
                }
            }
            echo "</p>";
        
        }//gestore
        elseif(get_class($objUser[0]) == 'Cassiere'){

            $cassiere = $objUser[0];

            echo "<p>aggiorna cassa: ";
            $ret = $cassiere->aggiornaCassa(10);
            if(!$ret){
                echo "Errore nell'aggionramento";
            }else{
                echo "OK";
            }
            echo "</p>";
        }
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
?>
</div>
<div id="debug"></div>
