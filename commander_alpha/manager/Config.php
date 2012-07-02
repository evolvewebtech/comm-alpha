<?php
/**
 * Description of Config
 *
 * @author Francesco Falanga, Alessandro Sarzina
 */
class Config {
    
    public function getConfig(){

        $LOGGER_LEVEL = 100;
        $LOGGER_FILE = '../logs/commander.log';

        $config = array(
                    'LOGGER_LEVEL' => $LOGGER_LEVEL,
                    'LOGGER_FILE' => $LOGGER_FILE);

        return $config;
    }

    public static function getInstance() {
        static $objLog;
        if(!isset($objLog)) {
            $objLog = new Config();
        }
        return $objLog;
    }
}
?>
