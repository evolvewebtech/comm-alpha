<?php
/**
 * Description of DataManager
 *
 * @author francesco
 */
require_once dirname(__FILE__).'/../user/User.php';
require_once dirname(__FILE__).'/../user/Gestore.php';
require_once dirname(__FILE__).'/../user/Cassiere.php';

class DataManager {

    private static function _getConnection() {
      static $hDB;
      static $database = "commander";

      if(isset($hDB)) {
         return $hDB;
      }

      $hDB = mysql_connect("localhost", "root", "")
         or die("Failure connecting to the database!");

      if (!$hDB){
          die('Could not connect: ' . mysql_error());
      }
      mysql_select_db($database);

      return $hDB;
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

        /*
        var_dump($res);
        echo "<br />";
         * 
         */
        if(! ($res && mysql_num_rows($res))) {
            die("Failed getting cassieri data");
        }
        if(mysql_num_rows($res)) {
              $objs = array();
              while($rec = mysql_fetch_assoc($res)) {
                  /*
                  echo "<pre>";
                  print_r($rec);
                  echo "</pre>";
                  */
                $objs[] = new Cassiere(intval($rec['utente_registrato_id']));
                }
              return $objs;
        } else {
            return array();
            }
        }
    }


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
     * - livello_cassiere
     *
     * @param <int> $userID
     * @return <array cassiere>
     */
    public static function getCassiereData($id){
        $sql = "SELECT * FROM cmd_cassiere WHERE utente_registrato_id=$id";
        //$sql = "SELECT * FROM cmd_cassiere WHERE id=$id";
        /*
        print_r($sql);
        echo "<pre>";
        */
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Cassiere");
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
                die("Errore");
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
                die("Errore");
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
     * @param <int> $livello_cassiere
     * @return <bool>
     */
    public static function inserisciCassiere($cassiere_id, $utente_registrato_id, 
                                             $gestore_id, $username, $password, $nome,
                                             $cognome, $tipo, $livello_cassiere){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        /*
         * inserisco un profilo utente (tabella_ cmd_utente registrato)
         */
        $ret = $db->insert('cmd_utente_registrato', array($utente_registrato_id, $username,
                                                          $password, $nome, $cognome, $tipo));

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
                            array($cassiere_id, $livello_cassiere, $utente_registrato_id2, $gestore_id));
        else
            $ret2 = false;

        /* -- debug
        echo "<br />ins 1: ";
        var_dump($ret);
        echo "<br />ins 2: ";
        var_dump($ret2);
        echo "<br />";
         *
         */
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
     * @param <int> $livello_cassiere
     * @return <bool>
     */
    public static function aggiornaCassiere($cassiere_id, $gestore_id, $username,
                                            $password, $nome, $cognome, $tipo, $livello_cassiere){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * aggiorno il livello del cassiere
         */
        $ret = $db->update('cmd_cassiere',
                            array('livello_cassiere' => $livello_cassiere),
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
            die("Failed getting entity Sala");
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
                die("Failed getting Sala data");
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

    public static function addTavolo($id,$nome,$numero,$nmax_coperti,$posizione,$sala_id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        $ret = $db->insert('cmd_tavolo', array($id,$nome,$numero,$nmax_coperti,$posizione,$sala_id));

        if ($ret) return true;
        else return false;
    }//inserisci tavolo

    public static function delTavolo($id){
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        $ret = $db->delete('cmd_tavolo', "id = ".$id);

        if ($ret) return true;
        else return false;
    }

    public static function getTavolo($id){
        $sql = "SELECT * FROM cmd_tavolo WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Tavolo");
        }
            return mysql_fetch_assoc($res);
        }
    }

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
            die("Failed getting entity Alimento");
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
        $ret = $db->insert('cmd_alimento_esaurito', array($id, $alimento_id, $data_esaurito));

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
         * cancello un alimento esaurito
         */
        $ret = $db->delete('cmd_alimento_esaurito', "alimento_id = ".$alimento_id);

        if ($ret) return true;
        else return false;
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
                die("Failed getting Alimento_ESAURITO data");
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
            die("Failed getting entity Variante");
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
        $ret = $db->insert('cmd_categoria', array($id, $colore_bottone_predef, $nome, $gestore_id));

        if ($ret) return true;
        else return false;
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
            die("Failed getting entity categoria");
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

        if ($ret) return true;
        else return false;
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
        $sql = "SELECT * FROM cmd_menufisso WHERE id=$id";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity menufisso");
        }
            return mysql_fetch_assoc($res);
        }
    }

    /**
     *
     * @return <array>
     */
    public static function getAllMenufisso(){
        $sql = "SELECT * FROM cmd_menufisso";
        if (DataManager::_getConnection()){
        $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                die("Failed getting menufisso data");
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

    

}
?>