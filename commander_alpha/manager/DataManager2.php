<?php
/**
 * Description of DataManager2
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/../object/food/Alimento.php';
require_once dirname(__FILE__).'/../object/food/BuonoPrepagato.php';
require_once dirname(__FILE__).'/../object/food/Categoria.php';
require_once dirname(__FILE__).'/../object/food/Ordine.php';
require_once dirname(__FILE__).'/../object/food/RigaOrdine.php';
require_once dirname(__FILE__).'/../object/food/Sala.php';
require_once dirname(__FILE__).'/../object/food/Stampante.php';
require_once dirname(__FILE__).'/../object/food/Tavolo.php';
require_once dirname(__FILE__).'/../object/food/Variante.php';
require_once dirname(__FILE__).'/../object/food/MenuFisso.php';
require_once dirname(__FILE__).'/../object/food/CatMenu.php';

require_once dirname(__FILE__).'/AppConfig.php';

class DataManager2 {
    
    
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
        $ret = $db->update('cmd_alimento', array('nome' => $nome,
                                                  'prezzo' => $prezzo,
                                                  'iva' => $iva,
                                                  'colore_bottone' => $colore_bottone,
                                                  'descrizione' => $descrizione,
                                                  'apeso' => $apeso,
                                                  'path_image' => $path_image,
                                                  'codice_prodotto' => $codice_prodotto,
                                                  'quantita' => $quantita,
                                                  'gestore_id' =>  $gestore_id,
                                                  'categoria_id' => $categoria_id,
                                                  'alimento_id' => $alimento_id),
                                            array('id', $id)
                );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaAlimento
    
    
    
    /**
     *
     * @param <bool> $id
     * @return boolean 
     */
    static function aggiornaQuantitaAlimento($id, $quantita_dec){
        
        $sql = "UPDATE cmd_alimento SET quantita=quantita-$quantita_dec WHERE id=$id AND quantita>0";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if($res) {
                return true;
            }
            else {
                return false;
            }
        }
    }//end aggiornaQuantitaAlimento
    
    
    
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
        $ret = $db->update('cmd_alimento_esaurito', array('alimento_id' => $alimento_id,
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
     * @param <int> $alimento_id
     * @param <int> $menu_fisso_id
     * @return <bool> 
     */
    static function inserisciAlimentoMenu($id, $alimento_id, $menu_fisso_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione alimento_menu
         */
        $ret = $db->insert('cmd_alimento_menu', array($id, $alimento_id, $menu_fisso_id));
                
        if ($ret) return true;
        else return false;
    }//end inserisciAlimentoMenu
    
    
    
    /**
     *
     * @param <int> $id
     * @param <int> $alimento_id
     * @param <int> $menu_fisso_id
     * @return <bool> 
     */
    static function aggiornaAlimentoMenu($id, $alimento_id, $menu_fisso_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una relazione alimento_menu
         */
        $ret = $db->update('cmd_alimento_menu', array('alimento_id' => $alimento_id,
                                                  'menu_fisso_id' =>  $menu_fisso_id),
                                            array('id', $id)
                    );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaAlimentoMenu
    
    
    
    /**
     *
     * @param <int> $id
     * @return <bool> 
     */
    static function cancellaAlimentoMenu($id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione alimento_menu 
         */
        $ret = $db->delete('cmd_alimento_menu', "id = ".$id);
                
        if ($ret) return true;
        else return false;
    }//end cancellaAlimentoMenu
    
    
    
    /**
     *
     * @param <int> $alimento_menu_id
     * @param <int> $alimento_id
     * @return <bool> 
     */
    static function inserisciAlimentomenuAlimento($alimento_menu_id, $alimento_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();
        
        /*
         * inserisco una relazione alimento_menu alimento
         */
        $ret = $db->insert('rel_alimentomenu_alimento', array($alimento_menu_id, $alimento_id));
                
        if ($ret) return true;
        else return false;
    }//end inserisciAlimentomenuAlimento
    
    
    
    /**
     *
     * @param <int> $alimento_menu_id
     * @param <int> $alimento_id
     * @param <int> $new_alimento_menu_id
     * @param <int> $new_alimento_id
     * @return <bool> 
     */
    static function aggiornaAlimentomenuAlimento($alimento_menu_id, $alimento_id, $new_alimento_menu_id, $new_alimento_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una relazione alimento_menu alimento
         */
        $ret = $db->update('rel_alimentomenu_alimento', array('alimento_menu_id' => $new_alimento_menu_id,
                                                  'alimento_id' =>  $new_alimento_id),
                                            array('alimento_menu_id', $alimento_menu_id, 'alimento_id', $alimento_id)
                    );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaAlimentomenuAlimento
    
    
    
    /**
     *
     * @param <int> $alimento_menu_id
     * @param <int> $alimento_id
     * @return <bool> 
     */
    static function cancellaAlimentomenuAlimento($alimento_menu_id, $alimento_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione alimento_menu alimento
         */
        $ret = $db->delete('rel_alimentomenu_alimento', "alimento_menu_id = ".$alimento_menu_id.
                        " AND "."alimento_id = ".$alimento_id);
                
        if ($ret) return true;
        else return false;
    }//end cancellaAlimentomenuAlimento
    
    
    
    
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
     * @param <string> $nome
     * @param <string> $colore_bottone_predef
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
                                                  'nome' => $nome,
                                                  'gestore_id' => $gestore_id),
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
     * @param <string> $nome
     * @param <double> $prezzo
     * @param <double> $iva
     * @param <string> $descrizione
     * @param <int> $gestore_id
     * @return <bool> 
     */
    static function inserisciMenuFisso($id, $nome, $prezzo, $iva, $descrizione, $gestore_id){
        
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
    static function aggiornaMenuFisso($id, $nome, $prezzo, $iva, $descrizione, $gestore_id){
        
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
    static function cancellaMenuFisso($id){
        
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
        $ret = $db->update('cmd_stampante', array('nome' => $nome,
                                                  'posizione' => $posizione,
                                                  'indirizzo' => $indirizzo,
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
                                                  'prezzo' =>  $prezzo,
                                                  'iva' => $iva,
                                                  'gestore_id' => $gestore_id),
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
     * @param <int> $variante_id
     * @param <int> $alimento_id
     * @return <bool> 
     */
    static function inserisciVarianteAlimento($variante_id, $alimento_id){
        
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
    static function aggiornaVarianteAlimento($variante_id, $alimento_id, $new_variante_id, $new_alimento_id){
        
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
    static function cancellaVarianteAlimento($variante_id, $alimento_id){
        
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
     * 
     */
    static function inserisciOrdine($id, $seriale, $n_coperti, $tavolo_id){       
        $sql = "INSERT INTO cmd_ordine VALUES ($id, '$seriale', now(), $n_coperti, $tavolo_id)";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if($res) return true;
        else return false;
        }
        else return false;
    }//end inserisciOrdine
    
    
    
    /**
     *
     * 
     */
    static function aggiornaOrdine($id, $seriale, $timestamp, $n_coperti, $tavolo_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico un ordine
         */
        $ret = $db->update('cmd_ordine', array('seriale' => $seriale,
                                               'timestamp' => $timestamp,
                                               'n_coperti' => $n_coperti,
                                               'tavolo_id' => $tavolo_id),
                                               array('id', $id)
                    );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaOrdine
    
    
    
    /**
     *
     * @param <int> $id
     * @return <bool> 
     */
    static function cancellaOrdine($id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello un ordine
         */
        $ret = $db->delete('cmd_ordine', "id = ".$id);
                
        if ($ret) return true;
        else return false;
    }//end cancellaOrdine
    
    
    
    static function nextIDOrdine(){             
        $sql = "SHOW TABLE STATUS WHERE name='cmd_ordine'";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (nextIDOrdine)");
            }
              $row = mysql_fetch_array($res);
              $next_id = $row['Auto_increment'] ;
        
              return $next_id;
            } else {
              return -1;
        }
    }
    
    
    
    static function nextIDRigaOrdine(){             
        $sql = "SHOW TABLE STATUS WHERE name='cmd_riga_ordine'";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (nextIDRigaOrdine)");
            }
              $row = mysql_fetch_array($res);
              $next_id = $row['Auto_increment'] ;
        
              return $next_id;
            } else {
              return -1;
        }
    }
    
    
    
    
    
    /**
     *
     * 
     */
    static function inserisciOrdineChiuso($id, $ordine_id){
        $sql = "INSERT INTO cmd_ordine_chiuso VALUES ($id, now(), $ordine_id)";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if($res) return true;
        else return false;
        }
        else return false;
    }//end inserisciOrdineChiuso
    
    
    
    /**
     *
     * 
     */
    static function aggiornaOrdineChiuso($id, $timestamp, $ordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico un ordine
         */
        $ret = $db->update('cmd_ordine_chiuso', array('timestamp' => $timestamp,
                                                        'ordine_id' => $ordine_id),
                                                        array('id', $id)
                    );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaOrdineChiuso
    
    
    
    /**
     *
     * @param <int> $id
     * @return <bool> 
     */
    static function cancellaOrdineChiuso($id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello un ordine
         */
        $ret = $db->delete('cmd_ordine_chiuso', "id = ".$id);
                
        if ($ret) return true;
        else return false;
    }//end cancellaOrdineChiuso
    
    
    
    
    /**
     *
     * 
     */
    static function inserisciRigaOrdine($id, $ordine_id, $alimento_id, $menu_fisso_id, $numero, $prezzo, $iva, $cassire_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una oggetto RigaOrdine
         */
        $ret = $db->insert('cmd_riga_ordine', array($id, $ordine_id, $alimento_id, $menu_fisso_id, $numero, $prezzo, $iva, $cassire_id));
                
        if ($ret) return true;
        else return false;
    }//end inserisciRigaOrdine
    
    
    
    /**
     *
     * 
     */
    static function aggiornaRigaOrdine($id, $ordine_id, $alimento_id, $menu_fisso_id, $numero, $prezzo, $iva, $cassire_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una riga ordine
         */
        $ret = $db->update('cmd_riga_ordine', array('ordine_id' => $ordine_id,
                                                    'alimento_id' => $alimento_id,
                                                    'menu_fisso_id' => $menu_fisso_id,
                                                    'numero' => $numero,
                                                    'prezzo' => $prezzo,
                                                    'iva' => $iva,
                                                    'cassire_id' => $cassire_id),
                                                    array('id', $id)
                    );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaRigaOrdine
    
    
    
    /**
     *
     * @param <int> $id
     * @return <bool> 
     */
    static function cancellaRigaOrdine($id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una riga ordine
         */
        $ret = $db->delete('cmd_riga_ordine', "id = ".$id);
                
        if ($ret) return true;
        else return false;
    }//end cancellaRigaOrdine
    
    
    
    
    /**
     *
     * @param <int> $buono_id
     * @param <int> $ordine_id
     * @return <bool> 
     */
    static function inserisciBuonoOrdine($buono_id, $ordine_id, $buono_cred_us){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione buono_ordine
         */
        $ret = $db->insert('rel_buono_ordine', array($buono_id, $ordine_id, $buono_cred_us));
                
        if ($ret) return true;
        else return false;
    }//end inserisciBuonoOrdine
    
    
    
    /**
     *
     * @param <int> $buono_id
     * @param <int> $ordine_id
     * @param <int> $new_buono_id
     * @param <int> $new_ordine_id
     * @return <bool> 
     */
    static function aggiornaBuonoOrdine($buono_id, $ordine_id, $new_buono_id, $new_ordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una relazione buono_ordine
         */
        $ret = $db->update('rel_buono_ordine', array('buono_id' => $new_buono_id,
                                                     'ordine_id' => $new_ordine_id),
                                                     array('buono_id', $buono_id, 'ordine_id', $ordine_id)
                    );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaBuonoOrdine
    
    
    
    /**
     *
     * @param <int> $buono_id
     * @param <int> $ordine_id
     * @return <bool> 
     */
    static function cancellaBuonoOrdine($buono_id, $ordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione buono_ordine
         */
        $ret = $db->delete('rel_buono_ordine', "buono_id = ".$buono_id.
                        " AND "."ordine_id = ".$ordine_id);
                
        if ($ret) return true;
        else return false;
    }//end cancellaBuonoOrdine
    
    
    
    
    /**
     *
     * @param <int> $cassiere_id
     * @param <int> $ordine_id
     * @return <bool> 
     */
    static function inserisciCassiereOrdine($cassiere_id, $ordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione cassiere_ordine
         */
        $ret = $db->insert('rel_cassiere_ordine', array($cassiere_id, $ordine_id));
                
        if ($ret) return true;
        else return false;
    }//end inserisciCassiereOrdine
    
    
    
    /**
     *
     * @param <int> $cassiere_id
     * @param <int> $ordine_id
     * @param <int> $new_cassiere_id
     * @param <int> $new_ordine_id
     * @return <bool> 
     */
    static function aggiornaCassiereOrdine($cassiere_id, $ordine_id, $new_cassiere_id, $new_ordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una relazione cassiere_ordine
         */
        $ret = $db->update('rel_cassiere_ordine', array('cassiere_id' => $new_cassiere_id,
                                                     'ordine_id' => $new_ordine_id),
                                                     array('cassiere_id', $cassiere_id, 'ordine_id', $ordine_id)
                    );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaCassiereOrdine
    
    
    
    /**
     *
     * @param <int> $cassiere_id
     * @param <int> $ordine_id
     * @return <bool> 
     */
    static function cancellaCassiereOrdine($cassiere_id, $ordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione cassiere_ordine
         */
        $ret = $db->delete('rel_cassiere_ordine', "cassiere_id = ".$cassiere_id.
                        " AND "."ordine_id = ".$ordine_id);
                
        if ($ret) return true;
        else return false;
    }//end cancellaCassiereOrdine
    
    
    
    
    /**
     *
     * @param <int> $variante_id
     * @param <int> $rigaordine_id
     * @return <bool> 
     */
    static function inserisciVarianteRigaOrdine($variante_id, $rigaordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione variante_rigaordine
         */
        $ret = $db->insert('rel_variante_rigaordine', array($variante_id, $rigaordine_id));
                
        if ($ret) return true;
        else return false;
    }//end inserisciVarianteRigaOrdine
    
    
    
    /**
     *
     * @param <int> $variante_id
     * @param <int> $rigaordine_id
     * @param <int> $new_variante_id
     * @param <int> $new_rigaordine_id
     * @return <bool> 
     */
    static function aggiornaVarianteRigaOrdine($variante_id, $rigaordine_id, $new_variante_id, $new_rigaordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una relazione variante_rigaordine
         */
        $ret = $db->update('rel_variante_rigaordine', array('variante_id' => $new_variante_id,
                                                     'rigaordine_id' => $new_rigaordine_id),
                                                     array('variante_id', $variante_id, 'rigaordine_id', $rigaordine_id)
                    );
                
        if ($ret) return true;
        else return false;
    }//end aggiornaVarianteRigaOrdine
    
    
    
    /**
     *
     * @param <int> $variante_id
     * @param <int> $rigaordine_id
     * @return <bool> 
     */
    static function cancellaVarianteRigaOrdine($variante_id, $rigaordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * cancello una relazione variante_rigaordine
         */
        $ret = $db->delete('rel_variante_rigaordine', "variante_id = ".$variante_id.
                        " AND "."rigaordine_id = ".$rigaordine_id);
                
        if ($ret) return true;
        else return false;
    }//end cancellaVarianteRigaOrdine
        
    private static function _getConnection() {
      static $hDB;

      if(isset($hDB)) {
         return $hDB;
      }

      $hDB = mysql_connect(AppConfig::instance()->DB_HOST, AppConfig::instance()->DB_USER, AppConfig::instance()->DB_PASS)
         or die("Failure connecting to the database!");

      if (!$hDB){
          die('Could not connect: ' . mysql_error());
      }
      mysql_select_db(AppConfig::instance()->DB_NAME);

      return $hDB;
    }
    
    
    public static function startTransaction() {
        if (DataManager2::_getConnection()){
            mysql_query('SET AUTOCOMMIT=0');
            mysql_query('START TRANSACTION');
            //mysql_query('BEGIN');
        }
    }
    
    
    public static function commitTransaction() {
        if (DataManager2::_getConnection()){
            mysql_query('COMMIT');
        }
    }
    
    
    public static function rollbackTransaction() {
        if (DataManager2::_getConnection()){
            mysql_query('ROLLBACK');
        }
    }
    
    
    public static function getAlimentoData($alimentoID){
        $sql = "SELECT * FROM cmd_alimento WHERE id=$alimentoID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Alimento");
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getAlimentoEsaurito($alimentoID){
        $sql = "SELECT * FROM cmd_alimento_esaurito WHERE alimento_id=$alimentoID AND record_attivo=1";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Alimento");
            return false;
        }
            return true;
        }
    }
    
    
    public static function getBuonoPrepagatoData($seriale){
        $sql = "SELECT * FROM cmd_buoni_prepagati WHERE seriale='$seriale'";
        
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity BuonoPrepagato");
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getCreditoBuonoUsato($ordine_id){
        $sql = "SELECT * FROM rel_buono_ordine WHERE ordine_id='$ordine_id'";
        
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity BuonoPrepagato");
            return 0;
        }
            while($row = mysql_fetch_assoc($res)) {
                return $row['credito_usato'];
            }
        }
    }
    
    
    public static function getCategoriaData($categoriaID){
        $sql = "SELECT * FROM cmd_categoria WHERE id=$categoriaID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Categoria");
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getCatMenuData($catMenuID){
        $sql = "SELECT * FROM cmd_alimento_menu WHERE id=$catMenuID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Categoria");
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getMenuFissoData($menuID){
        $sql = "SELECT * FROM cmd_menu_fisso WHERE id=$menuID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity MenuFisso");
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getOrdineData($ordineID){
        $sql = "SELECT * FROM cmd_ordine WHERE id=$ordineID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Ordine ".$ordineID);
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getRigaOrdineData($rigaOrdineID){
        $sql = "SELECT * FROM cmd_riga_ordine WHERE id=$rigaOrdineID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity RigaOrdine ".$rigaOrdineID);
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getSalaData($salaID){
        $sql = "SELECT * FROM cmd_sala WHERE id=$salaID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Sala ".$salaID);
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getStampanteData($stampanteID){
        $sql = "SELECT * FROM cmd_stampante WHERE id=$stampanteID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Stampante ".$stampanteID);
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    public static function getTavoloData($tavoloID){
        $sql = "SELECT * FROM cmd_tavolo WHERE id=$tavoloID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Stampante ".$stampanteID);
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getVarianteData($varianteID){
        $sql = "SELECT * FROM cmd_variante WHERE id=$varianteID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            //die("Failed getting entity Variante");
            return null;
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getAlimentoAsObject($alimentoID){
        $sql = "SELECT * FROM cmd_alimento WHERE id=$alimentoID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAlimentoAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new Alimento($alimentoID);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getBuonoPrepagatoAsObject($seriale, $gestoreID){
        $sql = "SELECT * FROM cmd_buoni_prepagati WHERE seriale='$seriale' AND gestore_id=$gestoreID AND record_attivo=1";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getBuonoPrepagatoAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new BuonoPrepagato($seriale);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getCategoriaAsObject($categoriaID){
        $sql = "SELECT * FROM cmd_categoria WHERE id=$categoriaID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getCategoriaAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new Categoria($categoriaID);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getMenuFissoAsObject($menuID){
        $sql = "SELECT * FROM cmd_menu_fisso WHERE id=$menuID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getMenuFissoAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new MenuFisso($menuID);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getOrdineAsObject($ordineID){
        $sql = "SELECT * FROM cmd_ordine WHERE id=$ordineID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getOrdineAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new Ordine($ordineID);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getSalaAsObject($salaID){
        $sql = "SELECT * FROM cmd_sala WHERE id=$salaID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getStampanteAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new Sala($salaID);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getStampanteAsObject($stampanteID){
        $sql = "SELECT * FROM cmd_stampante WHERE id=$stampanteID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getStampanteAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new Stampante($stampanteID);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getTavoloAsObject($tavoloID){
        $sql = "SELECT * FROM cmd_tavolo WHERE id=$tavoloID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getTavoloAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new Tavolo($tavoloID);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getVarianteAsObject($varianteID){
        $sql = "SELECT * FROM cmd_variante WHERE id=$varianteID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getVarianteAsObject)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                $objs = new Variante($varianteID);
              }
              return $objs;
            } else {
              return array();
        }
    }
    
    public static function getCatMenuObjectsForEntity($menuID){
        $sql = "SELECT * FROM cmd_alimento_menu WHERE menu_fisso_id=$menuID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return null;
            }
            $objs = array();
            while($row = mysql_fetch_assoc($res)) {
                $id = intval($row['id']);
                $objs[] = new CatMenu($id);
            }
          return $objs;
        } else {
          return array();
        }
    }
    
    public static function getAlimentoObjectsForEntity($categoriaID, $gestoreID){
        $sql = "SELECT * FROM cmd_alimento WHERE categoria_id=$categoriaID AND gestore_id=$gestoreID ORDER BY nome";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAlimentoObjectsForEntity)");
                //$objs[] = null;
                //return $objs;
                return null;
            }
            $objs = array();
            while($row = mysql_fetch_assoc($res)) {
                $id = intval($row['id']);
                $objs[] = new Alimento($id);
            }
          return $objs;
        } else {
          return array();
        }
    }
    
    public static function getAlimentoMenuObjectsForEntity($catMenuID){
        $sql = "SELECT * FROM rel_alimentomenu_alimento WHERE alimento_menu_id=$catMenuID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAlimentoObjectsForEntity)");
                //$objs[] = null;
                //return $objs;
                return null;
            }
            $objs = array();
            while($row = mysql_fetch_assoc($res)) {
                $id = intval($row['alimento_id']);
                $objs[] = new Alimento($id);
            }
          return $objs;
        } else {
          return array();
        }
    }
    
    public static function getRigaOrdineObjectsForEntity($ordineID){
        $sql = "SELECT * FROM cmd_riga_ordine WHERE ordine_id=$ordineID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getRigaOrdineObjectsForEntity)");
                //$objs[] = null;
                //return $objs;
                return null;
            }
            $objs = array();
            while($row = mysql_fetch_assoc($res)) {
                $id = intval($row['id']);
                $objs[] = new RigaOrdine($id);
            }
          return $objs;
        } else {
          return array();
        }
    }
        
    public static function getStampanteObjectsForEntity($alimentoID){
        $sql = "SELECT * FROM rel_alimento_stampante WHERE alimento_id=$alimentoID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getStampanteObjectsForEntity)");
                //$objs[] = null;
                //return $objs;
                return null;
            }
            $objs = array();
            while($row = mysql_fetch_assoc($res)) {
                $id = intval($row['stampante_id']);
                $objs[] = DataManager2::getStampanteAsObject($id);
            }
          return $objs;
        } else {
          return array();
        }
    }
    
    public static function getTavoloObjectsForEntity($salaID){
        $sql = "SELECT * FROM cmd_tavolo WHERE sala_id=$salaID ORDER BY numero";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getTavoloObjectsForEntity)");
                //$objs[] = null;
                //return $objs;
                return null;
            }
            $objs = array();
            while($row = mysql_fetch_assoc($res)) {
                $id = intval($row['id']);
                $objs[] = DataManager2::getTavoloAsObject($id);
            }
          return $objs;
        } else {
          return array();
        }
    }
    
    public static function getVarianteObjectsForEntity($alimentoID){
        $sql = "SELECT * FROM rel_variante_alimento WHERE alimento_id=$alimentoID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getVarianteObjectsForEntity)");
                //$objs[] = null;
                //return $objs;
                return null;
            }
            $objs = array();
            while($row = mysql_fetch_assoc($res)) {
                $id = intval($row['variante_id']);
                $objs[] = DataManager2::getVarianteAsObject($id);
            }
          return $objs;
        } else {
          return array();
        }
    }
    
    public static function getVarianteOrdineObjectsForEntity($rigaOrdineID){
        $sql = "SELECT * FROM rel_variante_rigaordine WHERE rigaordine_id=$rigaOrdineID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getVarianteOrdineObjectsForEntity)");
                //$objs[] = null;
                //return $objs;
                return null;
            }
            $objs = array();
            while($row = mysql_fetch_assoc($res)) {
                $id = intval($row['variante_id']);
                $objs[] = DataManager2::getVarianteAsObject($id);
            }
          return $objs;
        } else {
          return array();
        }
    }
    
    public static function getAllEntitiesAsObjects() {
        return null;
    }
    
    public static function getAllCategoriesAsObjects($cassiere_id) {

        $sql = "SELECT DISTINCT cmd_categoria.id".
               " FROM cmd_categoria".
               " INNER JOIN cmd_livello".
               " ON cmd_livello.nome=cmd_categoria.nome".              
               " INNER JOIN rel_livello_cassiere".
               " ON cmd_livello.id=rel_livello_cassiere.id_livello".
               " WHERE rel_livello_cassiere.id_cassiere=$cassiere_id".
               " ORDER BY cmd_categoria.id";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllEntitiesAsObjects)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  $id = intval($row['id']);
                  $objs[] = new Categoria($id);                
              }
              return $objs;
        } else {
          return array();
        }
    }
    
    public static function getAllMenuAsObjects($cassiere_id) {

        $sql = "SELECT DISTINCT cmd_menu_fisso.id".
               " FROM cmd_menu_fisso".
               " INNER JOIN cmd_livello".
               " ON cmd_livello.nome=cmd_menu_fisso.nome".              
               " INNER JOIN rel_livello_cassiere".
               " ON cmd_livello.id=rel_livello_cassiere.id_livello".
               " WHERE rel_livello_cassiere.id_cassiere=$cassiere_id";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllMenuAsObjects)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  $id = intval($row['id']);
                  $objs[] = new MenuFisso($id);                
              }
              return $objs;
        } else {
          return array();
        }
    }
    
    public static function getAllOrdiniAsObjects() {

        $sql = "SELECT id FROM cmd_ordine";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllOrdiniAsObjects)");
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
    
    public static function getAllOrdiniDateAsObjects($dataQuery, $cassiere_id) {

        $sql = "SELECT DISTINCT cmd_ordine.id, cmd_ordine.seriale, cmd_ordine.timestamp, cmd_ordine.n_coperti, cmd_ordine.tavolo_id".
               " FROM cmd_ordine".
               " INNER JOIN cmd_riga_ordine".
               " ON cmd_ordine.id=cmd_riga_ordine.ordine_id".
               " WHERE cmd_riga_ordine.cassiere_id=$cassiere_id".
               " AND date(cmd_ordine.timestamp)=date('$dataQuery') ORDER BY cmd_ordine.timestamp DESC";
        
        if (DataManager2::_getConnection()){
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
    
    public static function getAllSaleAsObjects() {

        $sql = "SELECT id FROM cmd_sala";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllStampantiAsObjects)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  $id = intval($row['id']);
                  $objs[] = new Sala($id);                
              }
              return $objs;
        } else {
          return array();
        }
    }
    
    public static function getAllStampantiAsObjects() {

        $sql = "SELECT id FROM cmd_stampante";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //die("Errore (getAllStampantiAsObjects)");
                return null;
            }
              $objs = array();
              while($row = mysql_fetch_assoc($res)) {
                  $id = intval($row['id']);
                  $objs[] = new Stampante($id);                
              }
              return $objs;
        } else {
          return array();
        }
    }
    
    
    public static function getAllScontiCassiere($id_cassiere) {

        $sql = "SELECT DISTINCT cmd_sconto.percentuale".
               " FROM cmd_sconto".
               " INNER JOIN rel_livello_sconto".
               " ON cmd_sconto.id=rel_livello_sconto.id_sconto".
               " INNER JOIN cmd_livello".
               " ON cmd_livello.id=rel_livello_sconto.id_livello".
               " INNER JOIN rel_livello_cassiere".
               " ON cmd_livello.id=rel_livello_cassiere.id_livello".
               " WHERE rel_livello_cassiere.id_cassiere=$id_cassiere";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(! ($res && mysql_num_rows($res))) {
                return null;
            }
            if(mysql_num_rows($res)) {
                  $objs = array();
                  while($rec = mysql_fetch_assoc($res)) {
                    $objs[] = $rec['percentuale'];
                    }
                  return $objs;
            } else {
                return array();
            }
        }
    }
    
    
    public static function getMenuAggiornato($cassiere_id) {
        $sql = "SELECT * FROM cmd_menu_aggiornato WHERE cassiere_id=$cassiere_id";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                return false;
            }
            if(mysql_num_rows($res)) {
                  $ret = false;
                  while($rec = mysql_fetch_assoc($res)) {
                    if ($rec['aggiornato'] == 1) $ret = true;
                    else $ret = false;
                    }
                  return $ret;
            } else {
                return array();
            }
        }
    }
    
    
    public static function insertMenuAggiornato($cassiere_id) {
        
        $sql = "SELECT * FROM cmd_menu_aggiornato WHERE cassiere_id=$cassiere_id";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                //Se non esiste, creo la riga
                require_once 'Database.php';
                $db = new Database();
                $db->connect();

                $ret = $db->insert('cmd_menu_aggiornato', array($cassiere_id, 1));

                if ($ret) return true;
                else return false;
            }
            //Se esiste l'id cassiere -> esco
            else {
                return true;
            }
        } else {
          return false;
        }
    }
    
    
    static function aggiornaMenuAggiornato($cassiere_id, $aggiornato){
        
        $new = $aggiornato ? 1 : 0;
        $sql = "UPDATE cmd_menu_aggiornato SET cassiere_id=$cassiere_id, aggiornato=$new WHERE cassiere_id=$cassiere_id";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if($res) {
                return true;
            }
            else {
                return false;
            }
        }
    }//end aggiornaMenuAggiornato
    
    
    static function aggiornaAllMenuAggiornato($aggiornato){
        
        $new = $aggiornato ? 1 : 0;
        $sql = "UPDATE cmd_menu_aggiornato SET aggiornato=$new";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if($res) {
                return true;
            }
            else {
                return false;
            }
        }
    }//end aggiornaAllMenuAggiornato
    
    
   } 
?>
