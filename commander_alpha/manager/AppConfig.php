<?php
/**
 * phpversion: PHP5
 *
 * @author Francesco Falanga, Alessandro Sarzina
 * @description Configuration Class, SINGLETON PATTERN
 * @todo You can define a constructor which loads configuration from file or you can add methods that manipulate configuration settings.
 *
 * usage: echo AppConfig::instance()->DB_HOST;
 */
class AppConfig {

  private static $instance;
  private $settings = array(
      'DB_HOST' => 'localhost',
      'DB_USER' => 'root',
      'DB_PASS' => '',
      'DB_NAME' => 'commander',
      'SESSION_TIMEOUT' => 10800,
      'SESSION_LIFESPAN' => 18000
  );

  /**
   * @return AppConfig
   */
  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function __get($name) {
    if (!isset($this->settings[$name]))
       throw new Exception('Unknown setting '.$name);
    return $this->settings[$name];
  }

  public function __set($name, $value) {
    $this->settings[$name] = $value;
  }
}
?>
