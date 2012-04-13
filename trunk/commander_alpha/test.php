<link rel="stylesheet" href="media/css/mosaic.css" type="text/css" media="screen" />
<link rel="stylesheet" href="media/css/main.css" type="text/css" media="screen" />
<script type="text/javascript" src="media/js/jquery-1.7.1.min.js"></script>


<style type="text/css">
    /*Demo Styles*/
    body{ background:#e9e8e4 url('img/bg-black.png'); color: #ffffff; }
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

<table>
    <tr>
        <td>N.</td>
        <td class="second">AB0001</td>

        <td class="third">NUMERO</td>
        <td>NOME</td>
        <td>CREDITO</td>
    </tr>
    <tr>
        <td>NOME</td>
        <td class="second">Band Titti</td>

        <td class="third">AB0001</td>
        <td>Band Titti</td>
        <td>5</td>
    </tr>
    <tr>
        <td>CRED.</td>
        <td class="second">5</td>

        <td class="third" colspan="3" style="font-size:12px; text-align: right;"><small><i><b>Sagra di S.Anna e Gioacchino</b></i></small></td>
    </tr>
</table>
<?php
require_once dirname(__FILE__).'/manager/HTTPSession.php';
require_once dirname(__FILE__).'/manager/Datamanager.php';

require_once dirname(__FILE__). '/lib/tcpdf/config/lang/eng.php';
require_once dirname(__FILE__). '/lib/tcpdf/tcpdf.php' ;


/*
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