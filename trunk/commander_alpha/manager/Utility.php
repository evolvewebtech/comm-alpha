<?php
/**
 * Description of Utility
 * This class is rich of utlity function
 *
 * @author francesco
 */
class Utility{

    /**
     *
     * print html line
     *
     * @param <string> $data
     */
    function println($data) {
        print $data . "<br>\n";
    }


    /**
     * metto in ordine un array di array in base ad una
     * chiave del sottoarray, es: subval_sort($data,'data_fattura');
     *
     * @param <array> $a
     * @param <type> $subkey
     * @return <array>
     */
    public static function subval_sort($a, $subkey){
        foreach($a as $k=>$v) {
        		$b[$k] = strtolower($v[$subkey]);
        	}
        	asort($b);
        	foreach($b as $key=>$val) {
        		$c[] = $a[$key];
        	}
        	return $c;
    }


    /**
     *
     * Ordino in ordine alfabetico (in base
     * alle chiavi) un array multidimensionale
     *
     * in ingresso l'array e il nome della key per cui
     * ordinare l'arry es: alphaSort($data, 'ragione_sociale');
     *
     * @param array $array
     * @param string $value
     * @return array
     *
     */
    public static function alphaSort($array, $value){

            # inizializziamo un ciclo for che abbia come condizione di terminazione
            # il numero degli array interni meno "1"
            for ($i=0;$i<count($array)-1;$i++) {
                # inizializziamo un ciclo for che abbia come condizione di terminazione
                # il numero degli array
                for ($j=$i+1;$j<count($array);$j++) {
                    # utilizziamo come indici i valori derivanti dall'iterazione dei cicli e utilizziamoli
                    # per effettuare un controllo tra valori
                    $ordina = strcmp($array[$i]["$value"], $array[$j]["$value"]);

                    # ordiniamo i valori sulla base dei confronti ponendo per primi
                    # i valori alfabeticamente "maggiori"
                    if ($ordina > 0) {
                      $ordinato = $array[$i];
                      $array[$i] = $array[$j];
                      $array[$j] = $ordinato;
                    }
                }
            }
        return $array;
      }


    public static function array_unique_deep($array) {
        $values=array();
        //ideally there would be some is_array() testing for $array here...
        foreach ($array as $part) {
            if (is_array($part)) $values=array_merge($values,array_unique_deep($part));
            else $values[]=$part;
        }
        return array_unique($values);
    }


    public static function specified_array_unique($array, $value)
    {
        $count = 0;

        foreach($array as $array_key => $array_value)
        {
            if ( ($count > 0) && ($array_value == $value) )
            {
                unset($array[$array_key]);
            }

            if ($array_value == $value) $count++;
        }

        return array_filter($array);
    }

    /**
     * formatto la data ricevuta così: 2012-30-01
     * e ritorno 30 Gennaio 2012.
     *
     * @param string $data
     * @param string $lang
     * @return string
     */
    public static function displayDate($data, $lang){
        if ($lang=='ita'){
            $yyyy=strtok($data, "-");
            $mesi = array('Gennaio','Febbraio','Marzo','Aprile','Maggio',
                          'Giugno','Lulgio','Agosto','Settembre',
                          'Ottobre','Novembre','Dicembre');
            $mm=strtok("-");
            switch ($mm) {
                case 1:
                    $mm = $mesi[0];
                    break;
                case 2:
                    $mm = $mesi[1];
                    break;
                case 3:
                    $mm = $mesi[2];
                    break;
                case 4:
                    $mm = $mesi[3];
                    break;
                case 5:
                    $mm = $mesi[4];
                    break;
                case 6:
                    $mm = $mesi[5];
                    break;
                case 7:
                    $mm = $mesi[6];
                    break;
                case 8:
                    $mm = $mesi[7];
                    break;
                case 9:
                    $mm = $mesi[8];
                    break;
                case 10:
                    $mm = $mesi[9];
                    break;
                case 11:
                    $mm = $mesi[10];
                    break;
                case 12:
                    $mm = $mesi[11];
                    break;
            }

        }
        $dd=strtok("-");
        $day_new =$dd." ".$mm." ".$yyyy;
        return $day_new;
    }


}
?>
