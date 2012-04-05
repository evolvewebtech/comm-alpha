<?php
/**
 * Description of DataManager2
 *
 * @author alessandro
 */
require_once dirname(__FILE__).'/../food/Alimento.php';
require_once dirname(__FILE__).'/../food/BuonoPrepagato.php';
require_once dirname(__FILE__).'/../food/Categoria.php';
require_once dirname(__FILE__).'/../food/Ordine.php';
require_once dirname(__FILE__).'/../food/RigaOrdine.php';
require_once dirname(__FILE__).'/../food/Stampante.php';
require_once dirname(__FILE__).'/../food/Variante.php';
require_once dirname(__FILE__).'/../food/MenuFisso.php';
require_once dirname(__FILE__).'/../food/CatMenu.php';


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
    static function inserisciRigaOrdine($id, $ordine_id, $alimento_id, $alimento_menu_id, $numero, $prezzo, $iva, $cassire_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una oggetto RigaOrdine
         */
        $ret = $db->insert('cmd_riga_ordine', array($id, $ordine_id, $alimento_id, $alimento_menu_id, $numero, $prezzo, $iva, $cassire_id));
                
        if ($ret) return true;
        else return false;
    }//end inserisciRigaOrdine
    
    
    
    /**
     *
     * 
     */
    static function aggiornaRigaOrdine($id, $ordine_id, $alimento_id, $alimento_menu_id, $numero, $prezzo, $iva, $cassire_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * modifico una riga ordine
         */
        $ret = $db->update('cmd_riga_ordine', array('ordine_id' => $ordine_id,
                                                    'alimento_id' => $alimento_id,
                                                    'alimento_menu_id' => $alimento_menu_id,
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
    static function inserisciBuonoOrdine($buono_id, $ordine_id){
        
        require_once 'Database.php';
        $db = new Database();
        $db->connect();

        /*
         * inserisco una relazione buono_ordine
         */
        $ret = $db->insert('rel_buono_ordine', array($buono_id, $ordine_id));
                
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
    
    
    public static function getAlimentoData($alimentoID){
        $sql = "SELECT * FROM cmd_alimento WHERE id=$alimentoID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Alimento");
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getBuonoPrepagatoData($seriale){
        $sql = "SELECT * FROM cmd_buoni_prepagati WHERE seriale=$seriale";
        
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity BuonoPrepagato");
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getCategoriaData($categoriaID){
        $sql = "SELECT * FROM cmd_categoria WHERE id=$categoriaID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Categoria");
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getCatMenuData($catMenuID){
        $sql = "SELECT * FROM cmd_alimento_menu WHERE id=$catMenuID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Categoria");
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getMenuFissoData($menuID){
        $sql = "SELECT * FROM cmd_menu_fisso WHERE id=$menuID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity MenuFisso");
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getOrdineData($ordineID){
        $sql = "SELECT * FROM cmd_ordine WHERE id=$ordineID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Ordine ".$ordineID);
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getRigaOrdineData($rigaOrdineID){
        $sql = "SELECT * FROM cmd_riga_ordine WHERE id=$rigaOrdineID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity RigaOrdine ".$rigaOrdineID);
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getStampanteData($stampanteID){
        $sql = "SELECT * FROM cmd_stampante WHERE id=$stampanteID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Stampante ".$stampanteID);
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getVarianteData($varianteID){
        $sql = "SELECT * FROM cmd_variante WHERE id=$varianteID";
        if (DataManager2::_getConnection()){
        $res = mysql_query($sql);
        if(($res && mysql_num_rows($res))==false) {
            die("Failed getting entity Variante");
        }
            return mysql_fetch_assoc($res);
        }
    }
    
    
    public static function getAlimentoAsObject($alimentoID){
        $sql = "SELECT * FROM cmd_alimento WHERE id=$alimentoID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getAlimentoAsObject)");
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
    
    public static function getBuonoPrepagatoAsObject($seriale){
        $sql = "SELECT * FROM cmd_buoni_prepagati WHERE seriale=$seriale";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getBuonoPrepagatoAsObject)");
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
                die("Errore (getCategoriaAsObject)");
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
                die("Errore (getMenuFissoAsObject)");
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
                die("Errore (getOrdineAsObject)");
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
    
    public static function getStampanteAsObject($stampanteID){
        $sql = "SELECT * FROM cmd_stampante WHERE id=$stampanteID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getStampanteAsObject)");
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
    
    public static function getVarianteAsObject($varianteID){
        $sql = "SELECT * FROM cmd_variante WHERE id=$varianteID";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getVarianteAsObject)");
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
    
    public static function getAlimentoObjectsForEntity($categoriaID){
        $sql = "SELECT * FROM cmd_alimento WHERE categoria_id=$categoriaID";
        
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
    
    public static function getAllCategoriesAsObjects() {

        $sql = "SELECT id FROM cmd_categoria";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getAllEntitiesAsObjects)");
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
    
    public static function getAllMenuAsObjects() {

        $sql = "SELECT id FROM cmd_menu_fisso";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getAllMenuAsObjects)");
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
                die("Errore (getAllOrdiniAsObjects)");
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
    
    public static function getAllOrdiniDateAsObjects($dataQuery) {

        $sql = "SELECT * FROM cmd_ordine WHERE date(timestamp)=$dataQuery ORDER BY timestamp DESC";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getAllOrdiniDateAsObjects)");
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
    
    public static function getAllStampantiAsObjects() {

        $sql = "SELECT id FROM cmd_stampante";
        
        if (DataManager2::_getConnection()){
            $res = mysql_query($sql);
            if(($res && mysql_num_rows($res))==false) {
                die("Errore (getAllStampantiAsObjects)");
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
    
   } 
?>
