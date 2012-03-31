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
}
?>
