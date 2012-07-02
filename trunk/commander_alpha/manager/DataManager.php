<?php
/**
 * Description of DataManager
 *
 * @author francesco
 */
require_once dirname(__FILE__).'/DataManager2.php';

require_once dirname(__FILE__).'/../user/User.php';
require_once dirname(__FILE__).'/../user/Gestore.php';
require_once dirname(__FILE__).'/../user/Cassiere.php';

require_once dirname(__FILE__).'/../food/Alimento.php';
require_once dirname(__FILE__).'/../food/BuonoPrepagato.php';
require_once dirname(__FILE__).'/../food/Categoria.php';
require_once dirname(__FILE__).'/../food/Ordine.php';
require_once dirname(__FILE__).'/../food/RigaOrdine.php';
require_once dirname(__FILE__).'/../food/Stampante.php';
require_once dirname(__FILE__).'/../food/Variante.php';
require_once dirname(__FILE__).'/../food/MenuFisso.php';
require_once dirname(__FILE__).'/../food/CatMenu.php';

require_once dirname(__FILE__).'/AppConfig.php';

class DataManager {

    private static function _getConnection() {
      static $hDB;
//      static $database = "commander";

      if(isset($hDB)) {
         return $hDB;
      }

      $hDB = mysql_connect(AppConfig::instance()->DB_HOST, AppConfig::instance()->DB_USER, AppConfig::instance()->DB_PASS)
         or die("Failure connecting to the database!");

      if (!$hDB){
          die('Could not connect: ' . mysql_error());
      }
      mysql_select_db(AppConfig::instance()->DB_NAME);
      mysql_set_charset('utf8',$hDB);


      return $hDB;
    }


    /**
     *
     * @param <type> $q
     * @return <type> 
     */
    public static function search_ordine($q){

        $tabella = "cmd_ordine";

        $sql = "SELECT $tabella.id".
               " FROM $tabella".
               " WHERE $tabella.seriale LIKE '%{$q}%'";


        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return false;
            }else{
                if(mysql_num_rows($res)) {
                      $objs = array();
                      while($rec = mysql_fetch_assoc($res)) {
                        $objs[] = new Ordine(intval($rec['id']));
                      }
                      return $objs;
                } else {
                    return array();
                    }
            }
        }

    }


    /**
     *
     * @param <type> $q2
     * @return Ordine 
     */
    public static function search_tavolo($q2) {

        $tabella = "cmd_tavolo";
        $tabella_ordine = "cmd_ordine";

        $sql = "SELECT $tabella_ordine.id".
               " FROM $tabella_ordine".
               " INNER JOIN $tabella".
               " ON $tabella.id=$tabella_ordine.tavolo_id".
               " WHERE $tabella.numero LIKE '%{$q2}%'".
               " OR $tabella.nome LIKE '%{$q2}%'";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return false;
            }else{
                if(mysql_num_rows($res)) {
                      $objs = array();
                      while($rec = mysql_fetch_assoc($res)) {
                        $objs[] = new Ordine(intval($rec['id']));
                      }
                      return $objs;
                } else {
                    return array();
                    }
            }
        }
    }

    /**
     *
     * controllo se esiste o meno una relazione nel db tra due elementi
     *
     * @param <type> $param1
     * @param <type> $param2
     * @param <type> $table
     * @return <bool>
     *
     */
    public static function controllo_relazione($param1, $param2, $table){
        if ($table=='rel_alimento_stampante'){
            $sql = "SELECT * FROM $table WHERE alimento_id=$param1 AND stampante_id=$param2";
        }elseif($table=='rel_alimentomenu_alimento'){
            $sql = "SELECT * FROM $table WHERE alimento_menu_id=$param1 AND alimento_id=$param2";
        }elseif($table=='rel_cassiere_ordine'){
            $sql = "SELECT * FROM $table WHERE cassiere_id=$param1 AND ordine_id=$param2";
        }elseif($table=='rel_ordine_alimento'){
            $sql = "SELECT * FROM $table WHERE ordine_id=$param1 AND alimento_id=$param2";
        }elseif($table=='rel_variante_alimento'){
            $sql = "SELECT * FROM $table WHERE variante_id=$param1 AND alimento_id=$param2";
        }elseif($table=='cmd_alimento_menu'){
            $sql = "SELECT * FROM $table WHERE menu_fisso_id=$param1 AND nome_cat='".$param2."'";
        }elseif($table=='rel_livello_cassiere'){
            $sql = "SELECT * FROM $table WHERE id_livello=$param1 AND id_cassiere='".$param2."'";
        }else{
            return false;
        }
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return false;
            }
            return true;
        }
    }


    /**
     *
     * @param int $id_alimento
     * @return boolean
     */
    public static function controlloAlimentoEsaurito($alimento_id) {
        $sql = "SELECT * FROM cmd_alimento_esaurito WHERE alimento_id=$alimento_id AND record_attivo=1";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return false;
            }else{
                return mysql_fetch_assoc($res);
            }           
        }
    }


    /**
     * questa funzione mi ritorna true se un dato alimento
     * è stato associat a un menu fisso
     *
     * @param <int> $id_alimento
     */
    public static function appartieneMenuFisso($id_alimento){
        $sql = "SELECT * FROM rel_alimentomenu_alimento WHERE alimento_id=$id_alimento";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return false;
            }else{
                return mysql_fetch_assoc($res);
            }
        }
    }


    /**
     *  visualizzo i giorni in cui ci sono stati degli ordini, 0 altrimenti
     */
    public static function visualizzaGiorni(){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        $res = $db->select('cmd_ordine_chiuso', 'timestamp');
        $days = $db->getResult();
//        var_dump($ordine_id);
        if(!$res) {
            die("Failed getting ordine_chiuso");
        }
        $row = $days;//mysql_fetch_assoc($days);
        if($row) {
          return $row;
        } else {
          return null;
        }
    }



    /**
     * ritorno l'id con valore massimo
     * all'interno della tabella di nome
     * $table_name
     * 
     * 
     * @param <string> $table_name
     * @return <int> 
     */
    public static function getMAXID($table_name){
        $q = "select MAX(id) from $table_name";
        $result = mysql_query($q);
        $data = mysql_fetch_array($result);

        return $data[0];
    }

    /**
     * ritorno l'oggetto Gestore prendendo in ingresso l'id dell'utente
     *
     * @param <int> $cassiereID
     * @return Gestore
     */
    public static function getGestore($cassiereID){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        $res = $db->select('cmd_cassiere', 'gestore_id', "id='$cassiereID'");
        $gestore_id = $db->getResult();
//        var_dump($gestore_id);
        if(!$res) {
            die("Failed getting gestore info for cassiere number $cassiereID");
        }
        $row = $gestore_id;//mysql_fetch_assoc($gestore_id);
        if($row) {
          return new Gestore(intval($row['gestore_id']));
        } else {
          return null;
        }
    }


    /**
     *
     * prendo tutti i cassieri associati al gestore avente, li restituisco
     * come oggetti
     *
     * @param <id> $gestoreID
     * @return Cassiere
     */
    public static function getTuttiCassieri($gestoreID){
        $sql = "SELECT * FROM cmd_cassiere WHERE gestore_id='$gestoreID'";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);

        if(! ($res && mysql_num_rows($res))) {
            return 0;
        }
        if(mysql_num_rows($res)) {
              $objs = array();
              while($rec = mysql_fetch_assoc($res)) {

                $objs[] = new Cassiere(intval($rec['utente_registrato_id']));
                }
              return $objs;
        } else {
            return array();
            }
        }
    }

    /*
     * ------------------------------------------------------
     * ------------------------------------------------------
     * GESTIONE PERMESSI
     */

    /**
     *
     * @param <int> $livello_id
     * @param <int> $cassiere_id
     * @return <bool>
     */
    public function eliminaPermesso($livello_id, $cassiere_id) {
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione livello - cassiere
         */
        $ret = $db->delete('rel_livello_cassiere', "id_livello = ".$livello_id.
                        " AND "."id_cassiere = ".$cassiere_id);

        if ($ret) return true;
        else return false;
    }

    /*
     * elimina un permesso dalla tabella
     */
    public function eliminaPermessoCassiere($nome_tabella,$livello_id, $cassiere_id) {
        $sql = "DELETE FROM $nome_tabella WHERE id_cassiere=$cassiere_id AND id_livello=$livello_id";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            return $res;
        }else return false;
    }

    /*
     * elimina un permesso dalla tabella
     */
    public function eliminaPermessoMenu($nome_tabella,$id_menu_fisso) {
        $sql = "DELETE FROM $nome_tabella WHERE id_menufisso=$id_menu_fisso";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            return $res;
        }else return false;
    }

    /*
     * elimina un permesso dalla tabella
     */
    public function eliminaPermessoCategoria($nome_tabella,$id_categoria) {
        $sql = "DELETE FROM $nome_tabella WHERE id_categoria=$id_categoria";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            return $res;
        }else return false;
    }

    /*
     * elimina un permesso dalla tabella cmd_livello
     */
    public function eliminaPermessoById($id_livello) {
        $sql = "DELETE FROM cmd_livello WHERE id=$id_livello";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            return $res;
        }else return false;
    }


    /**
     *
     * @param <int> $livello_id
     * @param <int> $cassiere_id
     * @return <bool>
     */
    public function aggiungiPermesso($livello_id, $cassiere_id) {
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione livello - cassiere
         */
        $ret = $db->insert('rel_livello_cassiere', array($cassiere_id, $livello_id));

        if ($ret) return true;
        else return false;
    }

    /**
     *
     * @param <int> $cassiere_id
     * @return <array>
     */
    public static function getLivelliCassiere($cassiere_id){
        $sql = "SELECT * FROM rel_livello_cassiere WHERE id_cassiere='$cassiere_id'";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);

        if(! ($res && mysql_num_rows($res))) {
            return 0;
        }
        if(mysql_num_rows($res)) {
              $objs = array();
              while($rec = mysql_fetch_assoc($res)) {

                $objs[] = $rec['id_livello'];
                }
              return $objs;
        } else {
            return array();
            }
        }
    }

    /**
     *
     * @return <type>
     */
    public static function getAllPermessi() {
        $sql = "SELECT * FROM cmd_livello";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante byID data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    /**
     *
     * @param <int> $cassiere_id
     * @param <int> $gestore_id
     * @return <type>
     */
    public static function aggiornaCassa($cassiere_id, $gestore_id, $saldo) {
        $sql = "SELECT * FROM cmd_cassa WHERE cassiere_id='$cassiere_id' AND record_attivo=1";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(!($res && mysql_num_rows($res))) {
                //non esistono record attivi, ne creo uno
                $sql2 = "INSERT INTO cmd_cassa (id, cassiere_id,gestore_id, saldo, consegnato, ora_consegna, record_attivo) VALUES (NULL, $cassiere_id, $gestore_id, $saldo, 0, NULL, 1)";
                $res = mysql_query($sql2);
                return $res;
            }
            /*
             * esiste un record attivo:
             * lo aggiorno se non ancora consegnato
             *  altrimenti lo imposto a zero e crao un nuovo record.
             */
            $row = mysql_fetch_assoc($res);
            $id = intval($row['id']);
            
            if ($row['consegnato']==0){
                //nuovo saldo
                $new_saldo = floatval($saldo) + floatval($row['saldo']);

                $sq3 = "UPDATE cmd_cassa".
                       " SET record_attivo=1, consegnato=0, saldo=$new_saldo".
                       " WHERE id=".$id;
                $res2 = mysql_query($sq3);
                if ($res2){
                    return $res2;
                }else {
                    return false;
                }
            }elseif($row['consegnato']==1) {

                $sql4 = "UPDATE cmd_cassa".
                       " SET record_attivo=0".
                       " WHERE id=".$id;
                $res4 = mysql_query($sql4);
                if (!$res4){
                    return $res4;
                    }

                $sql5 = "INSERT INTO cmd_cassa (id, cassiere_id,gestore_id, saldo, consegnato, ora_consegna, record_attivo) VALUES (NULL, $cassiere_id, $gestore_id, $saldo, 0, NULL, 1)";
                $res5 = mysql_query($sql5);
                if ($res5){
                    return $res5;
                }

            }

        }
    }

    //---------------------------------------------------------------

    /**
     * ritorno tutti i dati di un utente registrato:
     * - id
     * - nome
     * - cognome
     * - username
     * - md5(password)
     *
     * @param <int> $userID
     * @return <array string>
     */
    public static function getUserData($userID){
        $sql = "SELECT * FROM cmd_utente_registrato WHERE id=$userID";
        //print_r($sql);
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Failed getting entity User2");
            }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     * - id
     * - utente_registrato_id
     * -
     *
     * @param <int> $userID
     * @return <array string>
     */
    public static function getGestoreData($userID){
        $sql = "SELECT * FROM cmd_gestore WHERE utente_registrato_id=$userID";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Failed getting entity Gestore");
            }
            return mysql_fetch_assoc($res);
        }       
    }

    /**
     * - id
     * - utente_registrato_id
     * - gestore_id
     *
     * @param <int> $userID
     * @return <array cassiere>
     */
    public static function getCassiereData($id){
        $sql = "SELECT * FROM cmd_cassiere WHERE utente_registrato_id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     * - id
     * - utente_registrato_id
     * - gestore_id
     *
     * @param <int> $userID
     * @return <array cassiere>
     */
    public static function getCassiereDataByCassiereID($id){
        $sql = "SELECT * FROM cmd_utente_registrato INNER JOIN cmd_cassiere ON cmd_utente_registrato.id=cmd_cassiere.utente_registrato_id WHERE cmd_cassiere.id=$id";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return 0;
            }
                return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <Utente registrato object
     */
    public static function getAllEntitiesAsObjects() {

        $sql = "SELECT id,type FROM cmd_utente_registrato";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore getAllEntitiesAsObjects");
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                if($row['type'] == 'G') {
                    $id = intval($row['id']);
                    $objs[] = new Gestore($id);
                } elseif ($row['type'] == 'C') {
                    $id = intval($row['id']);
                    $objs[] = new Cassiere($id);
                } else {
                    die("Unknown entity type {$row['type']} encountered!");
                }
              }
              return $objs;
            } else {
              return array();
            }
    }


    /**
     *
     * ritorno l'oggetto Cassiere o Gestore in base all'id dell'utente_registrato
     *
     * @param <int> $id
     * @return Utente_registrato object
     */
    public static function getUserAsObject($id){
        $sql = "SELECT * FROM cmd_utente_registrato WHERE id=$id";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore getUserAsObject");
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                if($row['type'] == 'G') {
                    $id = intval($row['id']);
                    $objs[] = new Gestore($id);
                } elseif ($row['type'] == 'C') {
                    $id = intval($row['id']);
                    $objs[] = new Cassiere($id);
                } else {
                    die("Unknown entity type {$row['type']} encountered!");
                }
              }
              return $objs;
            } else {
              return array();
            }

    }

    /**
     *
     * eseguo il logut del cassiere
     *
     * @param <array> $cassiere
     * @return <type>
     */
    public static function logoutCassiere($cassiere){

        $cassiere_id = intval($cassiere['utente_registrato_id']);
        $sql = "UPDATE http_session".
              " INNER JOIN cmd_cassiere".
              " ON cmd_cassiere.utente_registrato_id=http_session.user_id".
              " SET http_session.logged_in=false, http_session.user_id=0".
              " WHERE cmd_cassiere.utente_registrato_id =".$cassiere_id;

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            return $res;
           }
        

    }

    /**
     * azzero la quantità in cassa di un cassiere
     *
     * @param <cassiere> $cassiere
     * @return <bool>
     */
    public static function azzeraCassa($cassiere){
        $cassiere_id = intval($cassiere['id']);
        $sql = "SELECT * FROM cmd_cassa WHERE record_attivo=1 AND cassiere_id=".$cassiere_id;
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //non è ancora presente un record nel db
                return 0;
            }
            $row = mysql_fetch_assoc($res);
            $id = intval($row['id']);
            //aggiorno l'ultimo record attivo di questo cassiere, il saldo ciò che consegna
            $sql = "UPDATE cmd_cassa".
                   " SET record_attivo=1, consegnato=1, ora_consegna=now()".
                   " WHERE id=".$id;
            $res2 = mysql_query($sql);
            if ($res2){
                return $row;
            }else {
                return false;
            }

        }
    }


    public static function visualizzaCassa($cassiere) {
        $cassiere_id = intval($cassiere['id']);
        $sql = "SELECT * FROM cmd_cassa WHERE record_attivo=1 AND cassiere_id=".$cassiere_id;
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return 0;
            }
            return mysql_fetch_assoc($res);
        }
    }

    //--------------------------------------------------------------------------

    /**
     *
     * inserisce un cassiere al'interno del db.
     *
     * utilizzo: DataManager::inserisciCassiere('NULL','NULL','NULL',...)
     *
     *
     * @param <int> $cassiere_id
     * @param <int> $utente_registrato_id
     * @param <int> $utente_registrato_id2
     * @param <int> $gestore_id
     * @param <string> $username
     * @param <string> $password
     * @param <string> $email
     * @param <string> $nome
     * @param <string> $cognome
     * @return <bool>
     */
    public static function inserisciCassiere($cassiere_id, $utente_registrato_id, 
                                             $gestore_id, $username, $password, $nome,
                                             $cognome, $tipo){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * inserisco un profilo utente (tabella_ cmd_utente registrato)
         */
        $ret = $db->insert('cmd_utente_registrato', array($utente_registrato_id, $username,
                                                          md5($password), $nome, $cognome, $tipo));

        /*
         * prelevo l'id appena assegnato (AI) dalla tabella cmd_utente_registrato
         */
        $db->select('cmd_utente_registrato', 'id', "username='$username'");
        $utente_registrato_id2 = $db->getResult();
        $utente_registrato_id2 = $utente_registrato_id2['id'];

        /*
         * inserisco il nuovo cassiere e lo associo al gestore e al suo profilo
         * (solo se l'operzione precedente è andata a bun fine.)
         */
        if ($ret)
            $ret2 = $db->insert('cmd_cassiere',
                            array($cassiere_id, $utente_registrato_id2, $gestore_id));
        else
            $ret2 = false;

        if ($ret && $ret2)
            return true;
        else {
            if ($ret)
                $db->delete('cmd_utente_registrato', "username='$username'");
            return false;
        }

    }//end inserisciCassiere


        /**
     *
     * aggiorna un cassiere all'interno del db.
     *
     * utilizzo: DataManager::aggiornaCassiere($cassiere_id, $gestore_id, $username, ...);
     *
     * @param <int> $cassiere_id
     * @param <int> $gestore_id
     * @param <string> $username
     * @param <string> $password
     * @param <string> $email
     * @param <string> $nome
     * @param <string> $cognome
     * @return <bool>
     */
    public static function aggiornaCassiere($cassiere_id, $gestore_id, $username,
                                            $password, $nome, $cognome, $tipo){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();


        /*
         * aggiorno il cassiere
         */
        $ret = $db->update('cmd_cassiere',
                            array('gestore_id' => $gestore_id),
                            array('id',$cassiere_id));
        /*
         * prelevo l'id dalla tabella cmd_cassiere appena aggiornata
         */
        $db->select('cmd_cassiere', 'utente_registrato_id', "id='$cassiere_id'");
        $utente_registrato_id = $db->getResult();

        /*
         * trasformo per sicurezza utente_registrato in un numero intero
         */
        $utente_registrato_id = intval($utente_registrato_id['utente_registrato_id']);

        if ($ret) {
            /*
             * aggiorno il profilo del cassiere
             */
            $ret2 = $db->update('cmd_utente_registrato',
                                    array(
                                        'username'   => $username,
                                        'md5_pw'     => $password,
                                        'first_name' => $nome,
                                        'last_name'  => $cognome,
                                        'type'       => $tipo),
                                    array(
                                        'id',$utente_registrato_id)
                                );
            if ($ret2==false){
                   $ret=false;
                }
            }
        return $ret;
    }//end aggiornaCassiere

    /**
     *
     * @param <type> $id
     * @return <bool>
     */
    public static function delCassiere($id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        $ret1 = $db->delete('cmd_utente_registrato', "id = ".$id);
        $ret2 = $db->delete('cmd_cassiere', "utente_registrato_id = ".$id);

//        $ret1 = true;
//        $ret2 = true;
        if ($ret1 && $ret2) return true;
        else return false;
    }
    

    //--------------------------------------------------------------------------

    /**
     *
     * inserisco una sala nel db
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $posizione utilizzo futuro per la mappa del locale
     * @return <type>
     */
    public static function addSala($id, $nome, $posizione){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        $ret = $db->insert('cmd_sala', array($id,$nome, $posizione));

        if ($ret) return true;
        else return false;
    }//inserisci sala

    /**
     *
     * @param <type> $id
     * @return <bool>
     */
    public static function delSala($id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        $ret = $db->delete('cmd_sala', "id = ".$id);

        if ($ret) return true;
        else return false;
    }

    /**
     *
     * @param <type> $id
     * @return <array>
     */
    public static function getSala($id){
        $sql = "SELECT * FROM cmd_sala WHERE id=$id";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return 0;
            }
        return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @param <type> $id
     * @param <type> $nome
     * @param <type> $posizione
     * @return <type> 
     */
    public static function editSala($id, $nome, $posizione){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        $ret = $db->update('cmd_sala', array('nome'      => $nome,
                                             'posizione' => $posizione
                                            ),
                                        array('id', $id)
                            );
        if ($ret) return true;
        else return false;
    }

    /**
     *
     * @return <array>
     */
    public static function getAllSala(){
        $sql = "SELECT * FROM cmd_sala";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Sala data");
                $objs = array();
                return $objs;
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    //--------------------------------------------------------------------------

    /**
     *
     * @param <int> $id
     * @param <type> $nome
     * @param <int> $numero
     * @param <int> $nmax_coperti
     * @param <type> $posizione
     * @param <int> $sala_id
     * @return <bool>
     */
    public static function addTavolo($id,$nome,$numero,$nmax_coperti,$posizione,$sala_id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        $ret = $db->insert('cmd_tavolo', array($id,$nome,$numero,$nmax_coperti,$posizione,$sala_id));

        if ($ret) return true;
        else return false;
    }//inserisci tavolo

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public static function delTavolo($id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        $ret = $db->delete('cmd_tavolo', "id = ".$id);

        if ($ret) return true;
        else return false;
    }

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    public static function getTavolo($id){
        $sql = "SELECT * FROM cmd_tavolo WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @param <int> $id
     * @param <type> $nome
     * @param <int> $numero
     * @param <int> $nmax_coperti
     * @param <type> $posizione
     * @param <int> $sala_id
     * @return <bool>
     */
    public static function editTavolo($id, $nome, $numero, $nmax_coperti, $posizione,$sala_id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        $ret = $db->update('cmd_tavolo', array('nome'         => $nome,
                                               'numero'       => $numero,
                                               'nmax_coperti' => $nmax_coperti,
                                               'posizione'    => $posizione,
                                               'sala_id'      => $sala_id
                                              ),
                                         array('id', $id)
                            );
        if ($ret) return true;
        else return false;
    }

    /**
     *
     * @param <int> $sala_id
     * @return <array>
     */
    public static function getAllTavoloBySalaID($sala_id){
        $sql = "SELECT * FROM cmd_tavolo WHERE sala_id=$sala_id ORDER BY nome, numero";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    /**
     *
     * @param <type> $sala_id
     * @return <type>
     */
    public static function delAllTavoloBySalaID($sala_id){
        $sql = "DELETE FROM cmd_tavolo WHERE sala_id=$sala_id";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if($res){
                return true;
            }else{
                return false;
            }
        }

    }

    /**
     *
     * @return <array>
     */
    public static function getAllTavolo(){
        $sql = "SELECT * FROM cmd_tavolo";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                die("Failed getting Tavolo data");
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    //--------------------------------------------------------------------------

    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <double> $prezzo
     * @param <double> $iva
     * @param <string> $colore_bottone
     * @param <text> $descrizione
     * @param <tinyint> $apeso
     * @param <string> $path_image
     * @param <string> $codice_prodotto
     * @param <int> $quantita
     * @param <int> $gestore_id
     * @param <int> $categoria_id
     * @param <int> $alimento_id
     * @return <bool>
     */
    static function inserisciAlimento($id, $nome, $prezzo, $iva, $colore_bottone,
                                      $descrizione, $apeso, $path_image, $codice_prodotto, $quantita,
                                      $gestore_id, $categoria_id, $alimento_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco un alimento
         */
        $ret = $db->insert('cmd_alimento', array($id, $nome, $prezzo, $iva, $colore_bottone,
            $descrizione, $apeso, $path_image, $codice_prodotto, $quantita, $gestore_id,
            $categoria_id, $alimento_id));

        /*
        echo "<pre>";
        print_r(array($id, $nome, $prezzo, $iva, $colore_bottone,
            $descrizione, $apeso, $path_image, $codice_prodotto, $quantita, $gestore_id,
            $categoria_id, $alimento_id));
        echo "</pre>";

        echo "<br />ret1: ";
        var_dump($ret);
        */
        
        if ($ret) return true;
        else return false;
    }//end inserisciAlimento

    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <double> $prezzo
     * @param <double> $iva
     * @param <string> $colore_bottone
     * @param <text> $descrizione
     * @param <tinyint> $apeso
     * @param <string> $path_image
     * @param <string> $codice_prodotto
     * @param <int> $quantita
     * @param <int> $gestore_id
     * @param <int> $categoria_id
     * @param <int> $alimento_id
     * @return <bool>
     */
    static function aggiornaAlimento($id, $nome, $prezzo, $iva, $colore_bottone,
            $descrizione, $apeso, $path_image, $codice_prodotto, $quantita,
            $gestore_id, $categoria_id, $alimento_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico un alimento
         */
        $ret = $db->update('cmd_alimento', array('nome'            => $nome,
                                                 'prezzo'          => $prezzo,
                                                 'iva'             => $iva,
                                                 'colore_bottone'  => $colore_bottone,
                                                 'descrizione'     => $descrizione,
                                                 'apeso'           => $apeso,
                                                 'path_image'      => $path_image,
                                                 'codice_prodotto' => $codice_prodotto,
                                                 'quantita'        => $quantita,
                                                 'gestore_id'      =>  $gestore_id,
                                                 'categoria_id'    => $categoria_id,
                                                 'alimento_id'     => $alimento_id),
                                           array('id', $id)
                );

        if ($ret) return true;
        else return false;
    }//end aggiornaAlimento



    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    static function cancellaAlimento($id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello un alimento
         */
        $ret = $db->delete('cmd_alimento', "id = ".$id);

        if ($ret) return true;
        else return false;
    }//end cancellaAlimento


    /**
     *
     * @param <int> $id
     * @return <array> 
     */
    public static function getAlimento($id){
        $sql = "SELECT * FROM cmd_alimento WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Alimento");
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <array>
     */
    public static function getAllAlimento(){
        $sql = "SELECT * FROM cmd_alimento";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                die("Failed getting Alimento data");
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    /**
     *
     * @param <int> $gestore_id
     * @return <array>
     */
    public static function getAllAlimentoByGestoreID($gestore_id){
        $sql = "SELECT * FROM cmd_alimento WHERE gestore_id=$gestore_id ORDER BY nome";
        
        $db = DataManager::_getConnection();
        if ($db){

            mysql_set_charset('utf8',$db);
            $res = mysql_query($sql);

            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante byID data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    //--------------------------------------------------------------------------


    /**
     *
     * @param <int> $id
     * @param <int> $alimento_id
     * @param <datetime> $data_esaurito
     * @return <bool>
     */
    static function inserisciAlimentoEsaurito($id, $alimento_id, $data_esaurito){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco un alimento esaurito
         */
        $ret = $db->insert('cmd_alimento_esaurito', array($id, $alimento_id, $data_esaurito, true));

        if ($ret) return true;
        else return false;
    }//end inserisciAlimentoEsaurito



    /**
     *
     * @param <int> $id
     * @param <int> $alimento_id
     * @param <datetime> $data_esaurito
     * @return <bool>
     */
    static function aggiornaAlimentoEsaurito($id, $alimento_id, $data_esaurito){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico un alimento esaurito
         */
        $ret = $db->update('cmd_alimento_esaurito', array('alimento_id'   => $alimento_id,
                                                          'data_esaurito' =>  $data_esaurito),
                                                    array('id', $id)
                    );

        if ($ret) return true;
        else return false;
    }//end aggiornaAlimentoEsaurito



    /**
     *
     * @param <int> $alimento_id
     * @return <bool>
     */
    static function cancellaAlimentoEsaurito($alimento_id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico un alimento esaurito
         */
        $ret = $db->update('cmd_alimento_esaurito', array('record_attivo' => 0,
                                                          ),
                                                    array('alimento_id', $alimento_id)
                    );

        if ($ret) return true;
        else return false;

        /*
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        //cancello un alimento esaurito
         
        $ret = $db->delete('cmd_alimento_esaurito', "alimento_id = ".$alimento_id);

        if ($ret) return true;
        else return false;
        */
    }//end cancellaAlimentoEsaurito

    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public static function getAlimentoEsaurito($id){
        $sql = "SELECT * FROM cmd_alimento_esaurito WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Alimento_esaurito");
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <array>
     */
    public static function getAllAlimentoEsaurito(){
        $sql = "SELECT * FROM cmd_alimento_esaurito";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Alimento_ESAURITO data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    public static function getIDbyAlimentoID($alimento_id){
        $sql = "SELECT id FROM cmd_alimento_esaurito WHERE alimento_id=$alimento_id";
        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return 0;
            }
        return mysql_fetch_assoc($res);
        }
    }

    //--------------------------------------------------------------------------

   /**
     *
     * @param <int> $id
     * @param <string> $descrizione
     * @param <double> $prezzo
     * @param <double> $iva
     * @param <int> $gestore_id
     * @return type
     */
    static function inserisciVariante($id, $descrizione, $prezzo, $iva, $gestore_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una variante
         */
        $ret = $db->insert('cmd_variante', array($id, $descrizione, $prezzo, $iva, $gestore_id));

        if ($ret) return true;
        else return false;
    }//end inserisciVariante



    /**
     *
     * @param <int> $id
     * @param <string> $descrizione
     * @param <double> $prezzo
     * @param <double> $iva
     * @param <int> $gestore_id
     * @return type
     */
    static function aggiornaVariante($id, $descrizione, $prezzo, $iva, $gestore_id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * modifico una variante
         */
        $ret = $db->update('cmd_variante', array('descrizione' => $descrizione,
                                                 'prezzo'      => $prezzo,
                                                 'iva'         => $iva,
                                                 'gestore_id'  => $gestore_id),
                                            array('id', $id)
                    );
        if ($ret) return true;
        else return false;
    }//end aggiornaVariante

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    static function cancellaVariante($id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * cancello una variante
         */
        $ret = $db->delete('cmd_variante', "id = ".$id);

        if ($ret) return true;
        else return false;
    }//end cancellaVariante

    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public static function getVariante($id){
        $sql = "SELECT * FROM cmd_variante WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Variante");
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <array>
     */
    public static function getAllVariante(){
        $sql = "SELECT * FROM cmd_variante";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                die("Failed getting Variante data");
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }


    public static function getAllVarianteByGestoreID($gestore_id){
        $sql = "SELECT * FROM cmd_variante WHERE gestore_id=$gestore_id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }
    //--------------------------------------------------------------------------

    /**
     *
     * @param <int> $id
     * @param <varchar> $nome
     * @param <varchar> $colore_bottone_predef
     * @param <int> $gestore_id
     * @return <bool> 
     */
    static function inserisciCategoria($id, $nome, $colore_bottone_predef, $gestore_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una categoria
         */
        $ret = $db->insert('cmd_categoria', array($id, $nome, $colore_bottone_predef, $gestore_id));
        if ($ret) {

            /*
             * prelevo l'id appena assegnato (AI) dalla tabella cmd_categoria
             */
            $db->select('cmd_categoria', 'id', "nome='$nome'");
            $categoria_id2 = $db->getResult();
            $categoria_id2 = $categoria_id2['id'];

            /*
             * inserisco la categoria nella tabella livelli
             * - id
             * - nome
             */
            $ret = $db->insert('cmd_livello', array('NULL', $nome));
            if ($ret){

                /*
                 * prelevo l'id appena assegnato (AI) dalla tabella cmd_livello
                 */
                $db->select('cmd_livello', 'id', "nome='$nome'");
                $livello_id2 = $db->getResult();
                $livello_id2 = $livello_id2['id'];

                /*
                 * inserisco l'associazione rel_livello_categoria
                 * - categoria_id
                 * - livello_id
                 *
                 */
                $ret = $db->insert('rel_livello_categoria', array($categoria_id2, $livello_id2));
                if ($ret){
                    return true;
                }else return false;


            }else return false;
            
        }else return false;



    }//end inserisciCategoria


    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <string> $colore_bottone_predef
     * @param <int> $gestore_id
     * @return <bool>
     */
    static function aggiornaCategoria($id, $nome, $colore_bottone_predef, $gestore_id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * modifico una categoria
         */
        $ret = $db->update('cmd_categoria', array('colore_bottone_predef' => $colore_bottone_predef,
                                                  'nome'                  => $nome,
                                                  'gestore_id'            => $gestore_id),
                                            array('id', $id)
                    );
        if ($ret) return true;
        else return false;
    }//end aggiornaCategoria

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    static function cancellaCategoria($id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * cancello una categoria
         */
        $ret = $db->delete('cmd_categoria', "id = ".$id);

        if ($ret) return true;
        else return false;
    }//end cancellaCategoria

    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public static function getCategoria($id){
        $sql = "SELECT * FROM cmd_categoria WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <array>
     */
    public static function getAllCategoria(){
        $sql = "SELECT * FROM cmd_categoria";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                die("Failed getting categoria data");
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    public static function getAllCategoriaByGestoreID($gestore_id){
        $sql = "SELECT * FROM cmd_categoria INNER JOIN rel_livello_categoria ON cmd_categoria.id = rel_livello_categoria.id_categoria WHERE gestore_id=$gestore_id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    //--------------------------------------------------------------------------

   /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <double> $prezzo
     * @param <double> $iva
     * @param <string> $descrizione
     * @param <int> $gestore_id
     * @return <bool>
     */
    static function inserisciMenufisso($id, $nome, $prezzo, $iva, $descrizione, $gestore_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco un menu fisso
         */
        $ret = $db->insert('cmd_menu_fisso', array($id, $nome, $prezzo, $iva, $descrizione, $gestore_id));
        if($ret){
            /*
             * prelevo l'id appena assegnato (AI) dalla tabella cmd_menu_fisso
             */
            $db->select('cmd_menu_fisso', 'id', "nome='$nome'");
            $menu_fisso_id2 = $db->getResult();
            $menu_fisso_id2 = $menu_fisso_id2['id'];

            /*
             * inserisco il menu_fisso nella tabella livelli
             * - id
             * - nome
             */
            $ret = $db->insert('cmd_livello', array('NULL', $nome));
            if ($ret){

                /*
                 * prelevo l'id appena assegnato (AI) dalla tabella cmd_livello
                 */
                $db->select('cmd_livello', 'id', "nome='$nome'");
                $livello_id2 = $db->getResult();
                $livello_id2 = $livello_id2['id'];

                /*
                 * inserisco l'associazione rel_livello_categoria
                 * - categoria_id
                 * - livello_id
                 *
                 */
                $ret = $db->insert('rel_livello_menufisso', array($menu_fisso_id2, $livello_id2));
                if ($ret){
                    return true;
                }else return false;

            }else return false;
        }else return false;
    }//end inserisciMenuFisso



    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <double> $prezzo
     * @param <double> $iva
     * @param <string> $descrizione
     * @param <int> $gestore_id
     * @return <bool>
     */
    static function aggiornaMenufisso($id, $nome, $prezzo, $iva, $descrizione, $gestore_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico un menu fisso
         */
        $ret = $db->update('cmd_menu_fisso', array('nome' => $nome,
                                                  'prezzo' => $prezzo,
                                                  'iva' => $iva,
                                                  'descrizione' => $descrizione,
                                                  'gestore_id' => $gestore_id),
                                            array('id', $id)
                    );

        if ($ret) return true;
        else return false;
    }//end aggiornaMenuFisso



    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    static function cancellaMenufisso($id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * cancello un menu fisso
         */
        $ret = $db->delete('cmd_menu_fisso', "id = ".$id);

        if ($ret) return true;
        else return false;
    }//end cancellaMenuFisso

    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public static function getMenufisso($id){
        $sql = "SELECT * FROM cmd_menu_fisso WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity menufisso");
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <array>
     */
    public static function getAllMenufisso(){
        $sql = "SELECT * FROM cmd_menu_fisso";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting menufisso data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }


    /**
     *
     * @param <int> $gestore_id
     * @return <array>
     */
    public static function getAllMenuByGestoreID($gestore_id){
        $sql = "SELECT * FROM cmd_menu_fisso INNER JOIN rel_livello_menufisso ON cmd_menu_fisso.id = rel_livello_menufisso.id_menufisso WHERE gestore_id=$gestore_id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante byID data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }


    //--------------------------------------------------------------------------


   /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <string> $posizione
     * @param <string> $indirizzo
     * @param <int> $gestore_id
     * @return <bool>
     */
    static function inserisciStampante($id, $nome, $posizione, $indirizzo, $gestore_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una stampante
         */
        $ret = $db->insert('cmd_stampante', array($id, $nome, $posizione, $indirizzo, $gestore_id));
        
        if ($ret) return true;
        else return false;
    }//end inserisciStampante


    /**
     *
     * @param <int> $id
     * @param <string> $nome
     * @param <string> $posizione
     * @param <string> $indirizzo
     * @param <int> $gestore_id
     * @return <bool>
     */
    static function aggiornaStampante($id, $nome, $posizione, $indirizzo, $gestore_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una stampante
         */
        $ret = $db->update('cmd_stampante', array('nome'       => $nome,
                                                  'posizione'  => $posizione,
                                                  'indirizzo'  => $indirizzo,
                                                  'gestore_id' =>  $gestore_id),
                                            array('id', $id)
                );

        if ($ret) return true;
        else return false;
    }//end aggiornaStampante



    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    static function cancellaStampante($id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una stampante
         */
        $ret = $db->delete('cmd_stampante', "id = ".$id);

        if ($ret) return true;
        else return false;
    }//end cancellaStampante


    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public static function getStampante($id){
        $sql = "SELECT * FROM cmd_stampante WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting stampante data");
            //return array();
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <array>
     */
    public static function getAllStampante(){
        $sql = "SELECT * FROM cmd_stampante";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    /**
     *
     * @param <int> $gestore_id
     * @return <array>
     */
    public static function getAllStampanteByGestoreID($gestore_id){
        $sql = "SELECT * FROM cmd_stampante WHERE gestore_id=$gestore_id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante byID data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }
    //--------------------------------------------------------------------------

    /**
     *
     * @param <int> $alimento_id
     * @param <int> $stampante_id
     * @return <bool>
     */
    static function inserisciAlimentoStampante($alimento_id, $stampante_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione alimento_stampante
         */
        $ret = $db->insert('rel_alimento_stampante', array($alimento_id, $stampante_id));

        if ($ret) return true;
        else return false;
    }//end inserisciAlimentoStampante



    /**
     *
     * @param <int> $alimento_id
     * @param <int> $stampante_id
     * @param <int> $new_alimento_id
     * @param <int> $new_stampante_id
     * @return <bool>
     */
    static function aggiornaAlimentoStampante($alimento_id, $stampante_id, $new_alimento_id, $new_stampante_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una relazione alimento_stampante
         */
        $ret = $db->update('rel_alimento_stampante', array('alimento_id' => $new_alimento_id,
                                                  'stampante_id' =>  $new_stampante_id),
                                            array('alimento_id', $alimento_id, 'stampante_id', $stampante_id)
                    );

        if ($ret) return true;
        else return false;
    }//end aggiornaAlimentoStampante



    /**
     *
     * @param <int> $alimento_id
     * @param <int> $stampante_id
     * @return <bool>
     */
    static function cancellaAlimentoStampante($alimento_id, $stampante_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione alimento_stampante
         */
        $ret = $db->delete('rel_alimento_stampante', "alimento_id = ".$alimento_id.
                        " AND "."alimento_id = ".$alimento_id);

        if ($ret) return true;
        else return false;
    }//end cancellaAlimentoStampante


    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public static function getAlimentoStampante($id){
        $sql = "SELECT * FROM cmd_alimento_stampante WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting stampante data");
            //return array();
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * 
     * @return <array>
     */
    public static function getAllAlimentoStampante(){
        $sql = "SELECT * FROM rel_alimento_stampante";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante byID data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }
    //--------------------------------------------------------------------------


    /**
     *
     * @param <int> $variante_id
     * @param <int> $alimento_id
     * @return <bool>
     */
    static function inserisciAlimentoVariante($alimento_id, $variante_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione variante_alimento
         */
        $ret = $db->insert('rel_variante_alimento', array($variante_id, $alimento_id));

        if ($ret) return true;
        else return false;

    }//end inserisciVarianteAlimento



    /**
     *
     * @param <int> $variante_id
     * @param <int> $alimento_id
     * @param <int> $new_variante_id
     * @param <int> $new_alimento_id
     * @return <bool>
     */
    static function aggiornaAlimentoVariante($alimento_id, $variante_id, $new_alimento_id, $new_variante_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una relazione variante_alimento
         */
        $ret = $db->update('rel_variante_alimento', array('variante_id' => $new_variante_id,
                                                  'alimento_id' => $new_alimento_id),
                                            array('variante_id', $variante_id, 'alimento_id', $alimento_id)
                    );

        if ($ret) return true;
        else return false;
    }//end aggiornaVarianteAlimento



    /**
     *
     * @param <int> $variante_id
     * @param <int> $alimento_id
     * @return <bool>
     */
    static function cancellaAlimentoVariante($alimento_id, $variante_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione variante_alimento
         */
        $ret = $db->delete('rel_variante_alimento', "variante_id = ".$variante_id.
                        " AND "."alimento_id = ".$alimento_id);

        if ($ret) return true;
        else return false;
    }//end cancellaVarianteAlimento

    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public static function getAlimentoVariante($id){
        $sql = "SELECT * FROM cmd_alimento_variante WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting stampante data");
            //return array();
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     *
     * @return <array>
     */
    public static function getAllAlimentoVariante(){
        $sql = "SELECT * FROM rel_variante_alimento";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante byID data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }
    //--------------------------------------------------------------------------

    /**
     *
     * @param <int> $variante_id
     * @param <int> $alimento_id
     * @return <bool>
     */
    static function inseriscinomeCatMenu($id,$menu_fisso_id, $nome_cat){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione variante_alimento
         */
        $ret = $db->insert('cmd_alimento_menu', array($id, $nome_cat, $menu_fisso_id));

        if ($ret) return true;
        else return false;

    }//end inserisciVarianteAlimento


    /**
     *
     * @param <int> $variante_id
     * @param <int> $alimento_id
     * @return <bool>
     */
    static function cancellaNomeCatMenu($menu_fisso_id, $nome_cat){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione variante_alimento
         */
        $ret = $db->delete('cmd_alimento_menu', "menu_fisso_id = ".$menu_fisso_id.
                        " AND "."nome_cat ='".$nome_cat ."'");

        if ($ret) return true;
        else return false;
    }//end cancellaVarianteAlimento


    public static function getAllNomeCategoria(){
        $sql = "SELECT * FROM cmd_alimento_menu";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante byID data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }


    /**
     *
     * @param <int> $menu_fisso_id
     * @return <array>
     */
    public static function getAllCategoriaByMenuID($menu_fisso_id){
        $sql = "SELECT * FROM cmd_alimento_menu WHERE menu_fisso_id=$menu_fisso_id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    

    //--------------------------------------------------------------------------


    /**
     *
     * @param <type> $alimento_menu_id
     * @param <type> $menu_id
     * @return <type>
     */
    static function inserisciAlimentoMenuAlimento($alimento_menu_id, $alimento_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione variante_alimento
         */
        $ret = $db->insert('rel_alimentomenu_alimento', array($alimento_menu_id, $alimento_id));

        if ($ret) return true;
        else return false;

    }//end inserisciVarianteAlimento

    /**
     *
     * @param <type> $alimento_menu_id
     * @param <type> $variante_id
     * @return <type>
     */
    static function cancellaAlimentoMenuAlimento($alimento_menu_id, $alimento_id){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione variante_alimento
         */
        $ret = $db->delete('rel_alimentomenu_alimento', "alimento_menu_id = ".$alimento_menu_id.
                        " AND "."alimento_id = ".$alimento_id);

        if ($ret) return true;
        else return false;
    }//end cancellaVarianteAlimento

    /**
     *
     * @param <type> $id
     * @return <type>
     */
    public static function getAlimentoMenuAlimento($id){
        $sql = "SELECT * FROM rel_alimentomenu_alimento WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting stampante data");
            //return array();
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <type> 
     */
    public static function getAllAlimentoMenu(){
        $sql = "SELECT * FROM rel_alimentomenu_alimento";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                //die("Failed getting Allstampante byID data");
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }
    

    //--------------------------------------------------------------------------

    /**
     *
     * @param <type> $id
     * @param <type> $seriale
     * @param <type> $credito
     * @param <type> $nominativo
     * @param <type> $gestore_id
     * @return <type> 
     */
    static function inserisciBuonoPrepagato($id, $seriale, $credito, $nominativo, $gestore_id, $record_attivo){

        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una variante
         */
        $ret = $db->insert('cmd_buoni_prepagati', array($id, $seriale, $credito, $nominativo, $gestore_id, $record_attivo));

        if ($ret) return true;
        else return false;
    }//end inserisciVariante


    /**
     *
     * @param <type> $id
     * @param <type> $seriale
     * @param <type> $credito
     * @param <type> $nominativo
     * @param <type> $gestore_id
     * @return <type>
     */
    static function aggiornaBuonoPrepagato($id, $seriale, $credito, $nominativo, $gestore_id, $record_attivo){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * modifico una variante
         */
        $ret = $db->update('cmd_buoni_prepagati', array('seriale'       => $seriale,
                                                        'credito'       => $credito,
                                                        'nominativo'    => $nominativo,
                                                        'gestore_id'    => $gestore_id,
                                                        'record_attivo' => $record_attivo),
                                                        array('id', $id)
                    );
        if ($ret) return true;
        else return false;
    }//end aggiornaVariante

    /**
     *
     * @param <int> $id
     * @return <bool>
     */
    static function cancellaBuonoPrepagato($id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * cancello una variante
         */
        $ret = $db->delete('cmd_buoni_prepagati', "id = ".$id);

        if ($ret) return true;
        else return false;
    }//end cancellaVariante

    /**
     *
     * @param <int> $id
     * @return <array>
     */
    public static function getBuonoPrepagato($id){
        $sql = "SELECT * FROM cmd_buoni_prepagati WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Variante");
            return 0;
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <array>
     */
    public static function getAllBuonoPrepagato(){
        $sql = "SELECT * FROM cmd_buoni_prepagati";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                die("Failed getting Buoni data");
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    public static function getAllBuonoPrepagatoAttiviByGestoreID($gestore_id) {
        $sql = "SELECT * FROM cmd_buoni_prepagati WHERE gestore_id=$gestore_id AND record_attivo=1";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    /**
     *
     * @param <type> $gestore_id
     * @return <type>
     */
    public static function getAllBuonoPrepagatoByGestoreID($gestore_id){
        $sql = "SELECT * FROM cmd_buoni_prepagati WHERE gestore_id=$gestore_id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                return array();
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec;
                    }
                  return $objs;
            } else {
                return array();
                }
            }
    }

    /**
     *
     * @return Ordine
     */
    public static function getAllOrdiniChiusi() {

        $sql = "SELECT * FROM cmd_ordine_chiuso";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  //$id = intval($row['id']);
                  //$objs[] = new Ordine($id);
                  $objs[] = $row;
              }
              return $objs;
        } else {
          return array();
        }
    }


    /**
     *
     * @return Ordine
     */
    public static function getOrdineByOrdineID($id) {

        $sql = "SELECT * FROM cmd_ordine WHERE id=".$id;

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return null;
            }
              $objs = mysql_fetch_assoc($res);
              return $objs;
        } else {
          return array();
       }
    }

    /**
     *
     * @return Ordine
     */
    public static function getRigheOrdineByOrdineID($id) {

        $sql = "SELECT * FROM cmd_riga_ordine WHERE ordine_id=".$id;

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  //$id = intval($row['id']);
                  //$objs[] = new Ordine($id);
                  $objs[] = $row;
              }
              return $objs;
        } else {
          return array();
       }
    }

    /**
     *
     * @param <type> $dataQuery
     * @return Ordine 
     */
    public static function getAllOrdiniDateAsObjects($dataQuery) {

        $sql = "SELECT * FROM cmd_ordine WHERE date(timestamp)=date('$dataQuery') ORDER BY timestamp DESC";

        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getAllOrdiniDateAsObjects)");
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  //$id = intval($row['id']);
                  //$objs[] = new Ordine($id);
                  $objs[] = $row;
              }
              return $objs;
        } else {
          return array();
        }
    }



//---------- statistiche

    /**
     *
     * @param int $cassiere_id
     * @param timestamp $start_timestamp
     * @param timestamp $end_timestamp
     * @return Ordine
     */
    public static function getAllOrdiniByCassiereAsObjects($cassiere_id,$start_timestamp, $end_timestamp) {

        $sql = "SELECT DISTINCT cmd_ordine.id, cmd_ordine.seriale, cmd_ordine.timestamp, cmd_ordine.n_coperti, cmd_ordine.tavolo_id".
               " FROM cmd_ordine".
               " INNER JOIN cmd_riga_ordine".
               " ON cmd_ordine.id=cmd_riga_ordine.ordine_id".
               " WHERE cmd_riga_ordine.cassiere_id=$cassiere_id".
               " AND TIMESTAMP(cmd_ordine.timestamp)>=STR_TO_DATE('$start_timestamp','%m/%d/%Y%H:%i:%s')".//es: " AND TIMESTAMP(cmd_ordine.timestamp)>=STR_TO_DATE('04/26/2012 23:17:44','%m/%d/%Y%H:%i:%s')".
               " AND TIMESTAMP(cmd_ordine.timestamp)<=STR_TO_DATE('$end_timestamp','%m/%d/%Y%H:%i:%s')".
               " ORDER BY cmd_ordine.timestamp DESC";

//        print_r($sql);

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllOrdiniDateAsObjects)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  $id = intval($row['id']);
                  $objs[] = new Ordine($id);
              }
              return $objs;
        } else {
          return array();
        }
    }


    /**
     *
     * @param timestamp $start_timestamp
     * @param timestamp $end_timestamp
     * @return Ordine
     */
    public static function getAllOrdiniByDateStartEndAsObjects ($start_timestamp, $end_timestamp) {

        $sql = "SELECT DISTINCT cmd_ordine.id, cmd_ordine.seriale, cmd_ordine.timestamp, cmd_ordine.n_coperti, cmd_ordine.tavolo_id".
               " FROM cmd_ordine".
               " INNER JOIN cmd_riga_ordine".
               " ON cmd_ordine.id=cmd_riga_ordine.ordine_id".
               " WHERE TIMESTAMP(cmd_ordine.timestamp)>=STR_TO_DATE('$start_timestamp','%m/%d/%Y%H:%i:%s')".//es: " AND TIMESTAMP(cmd_ordine.timestamp)>=STR_TO_DATE('04/26/2012 23:17:44','%m/%d/%Y%H:%i:%s')".
               " AND TIMESTAMP(cmd_ordine.timestamp)<=STR_TO_DATE('$end_timestamp','%m/%d/%Y%H:%i:%s')".
               " ORDER BY cmd_ordine.timestamp DESC";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllOrdiniDateAsObjects)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  $id = intval($row['id']);
                  $objs[] = new Ordine($id);
              }
              return $objs;
        } else {
          return array();
        }
    }


    /**
     *
     * @param <type> $alimento_id
     * @param <type> $start_timestamp
     * @param <type> $end_timestamp
     * @return <type>
     */
    public static function getTotaleAlimentoConsumato ($alimento_id, $start_timestamp, $end_timestamp) {

        $sql = " SELECT SUM(cmd_riga_ordine.numero),cmd_alimento.nome,cmd_alimento.prezzo,cmd_alimento.quantita".
               " FROM cmd_riga_ordine".
               " INNER JOIN cmd_ordine_chiuso".
               " ON cmd_riga_ordine.ordine_id = cmd_ordine_chiuso.ordine_id".
               " INNER JOIN cmd_alimento".
               " ON cmd_riga_ordine.alimento_id = cmd_alimento.id".
               " WHERE cmd_riga_ordine.alimento_id=$alimento_id".
               " AND TIMESTAMP(cmd_ordine_chiuso.timestamp)>=STR_TO_DATE('$start_timestamp','%m/%d/%Y%H:%i:%s')".
               " AND TIMESTAMP(cmd_ordine_chiuso.timestamp)<=STR_TO_DATE('$end_timestamp','%m/%d/%Y%H:%i:%s')".
               " ORDER BY cmd_ordine_chiuso.timestamp DESC";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllOrdiniDateAsObjects)");
                return null;
            }

              $totale = array();
              while($row = mysql_fetch_assoc($res)) {
                  $totale['quantita_consumata'] = $row['SUM(cmd_riga_ordine.numero)'];
                  $totale['nome'] = $row['nome'];
                  $totale['prezzo'] = $row['prezzo'];
                  $totale['quantita'] = $row['quantita'];
              }

              return $totale;
              
        } else {
          return array();
        }
    }

    /**
     *
     * @param <type> $start_timestamp
     * @param <type> $end_timestamp
     * @return <type>
     */
    public static function getTotaliAlimentiConsumati ($start_timestamp, $end_timestamp) {

        $sql = " SELECT DISTINCT cmd_riga_ordine.alimento_id".
               " FROM cmd_riga_ordine".
               " INNER JOIN cmd_ordine_chiuso".
               " ON cmd_riga_ordine.ordine_id = cmd_ordine_chiuso.ordine_id".
               " WHERE TIMESTAMP(cmd_ordine_chiuso.timestamp)>=STR_TO_DATE('$start_timestamp','%m/%d/%Y%H:%i:%s')".
               " AND TIMESTAMP(cmd_ordine_chiuso.timestamp)<=STR_TO_DATE('$end_timestamp','%m/%d/%Y%H:%i:%s')".
               " ORDER BY cmd_ordine_chiuso.timestamp DESC";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllOrdiniDateAsObjects)");
                return null;
            }
              $alimenti = array();
              while($row = mysql_fetch_assoc($res)) {
                  $alimento_id = intval($row['alimento_id']);
                  $alimenti[] = $alimento_id;

              }

              $objs = array();
              foreach ($alimenti as $alimento_id) {

                  $totale_consumato = DataManager::getTotaleAlimentoConsumato($alimento_id, $start_timestamp, $end_timestamp);
                  $objs[$alimento_id] = $totale_consumato;
              }

              return $objs;
              
        } else {
          return array();
        }
    }


    public static function getTotaliAlimentiConsumatiByCameriere ($start_timestamp, $end_timestamp, $cameriere_id) {

        $sql = " SELECT DISTINCT cmd_riga_ordine.alimento_id".
               " FROM cmd_riga_ordine".
               " INNER JOIN cmd_ordine_chiuso".
               " ON cmd_riga_ordine.ordine_id = cmd_ordine_chiuso.ordine_id".
               " WHERE TIMESTAMP(cmd_ordine_chiuso.timestamp)>=STR_TO_DATE('$start_timestamp','%m/%d/%Y%H:%i:%s')".
               " AND TIMESTAMP(cmd_ordine_chiuso.timestamp)<=STR_TO_DATE('$end_timestamp','%m/%d/%Y%H:%i:%s')".
               " AND cmd_riga_ordine.cassiere_id = $cameriere_id".
               " ORDER BY cmd_ordine_chiuso.timestamp DESC";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllOrdiniDateAsObjects)");
                return null;
            }
              $alimenti = array();
              while($row = mysql_fetch_assoc($res)) {
                  $alimento_id = intval($row['alimento_id']);
                  $alimenti[] = $alimento_id;

              }

              $objs = array();
              foreach ($alimenti as $alimento_id) {

                  $totale_consumato = DataManager::getTotaleAlimentoConsumato($alimento_id, $start_timestamp, $end_timestamp);
                  $objs[$alimento_id] = $totale_consumato;
              }

              return $objs;

        } else {
          return array();
        }
    }


    public static function getTotaleLastWeek () {

        $sql = " SELECT DISTINCT cmd_riga_ordine.alimento_id
                FROM cmd_riga_ordine
                INNER JOIN cmd_ordine_chiuso ON cmd_riga_ordine.ordine_id = cmd_ordine_chiuso.ordine_id
                WHERE TIMESTAMP( cmd_ordine_chiuso.timestamp ) >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY ) ";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllOrdiniDateAsObjects)");
                return null;
            }
              $alimenti = array();
              while($row = mysql_fetch_assoc($res)) {
                  $alimento_id = intval($row['alimento_id']);
                  $alimenti[] = $alimento_id;

              }

              $objs = array();
              foreach ($alimenti as $alimento_id) {

                  $totale_consumato = DataManager::getTotaleAlimentoLastWeek($alimento_id);
                  $objs[$alimento_id] = $totale_consumato;
              }

              return $objs;
              
        } else {
          return array();
        }
    }

    /**
     *
     * @param <type> $alimento_id
     * @return <type> 
     */
    public static function getTotaleAlimentoLastWeek($alimento_id){
        $sql = "SELECT SUM( cmd_riga_ordine.numero ) , cmd_alimento.nome, cmd_alimento.prezzo, cmd_alimento.quantita
                FROM cmd_riga_ordine
                INNER JOIN cmd_ordine_chiuso ON cmd_riga_ordine.ordine_id = cmd_ordine_chiuso.ordine_id
                INNER JOIN cmd_alimento ON cmd_riga_ordine.alimento_id = cmd_alimento.id
                WHERE cmd_riga_ordine.alimento_id = $alimento_id
                AND TIMESTAMP( cmd_ordine_chiuso.timestamp ) >= DATE_SUB( CURDATE( ) , INTERVAL 7 DAY )";

        if (DataManager::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllOrdiniDateAsObjects)");
                return null;
            }

              $totale = array();
              while($row = mysql_fetch_assoc($res)) {
                  $totale['quantita_consumata'] = $row['SUM(cmd_riga_ordine.numero)'];
                  $totale['nome'] = $row['nome'];
                  $totale['prezzo'] = $row['prezzo'];
                  $totale['quantita'] = $row['quantita'];
              }

              return $totale;

        } else {
          return array();
        }


    }


}
?>