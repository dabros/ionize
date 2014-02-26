<?php
/**
 * DUMMY FILE - to use when wanting ChromePHP::xxx() to simply do nothing
 */

/**
 * Server Side Chrome PHP debugger class
 *
 * @package ChromePhp
 * @author Craig Campbell <iamcraigcampbell@gmail.com>
 */
class ChromePhp
{
  private function __construct()
  {
  }

  /**
   * gets instance of this class
   *
   * @return ChromePhp
   */
  public static function getInstance()
  {
  }

  /**
   * logs a variable to the console
   *
   * @param string label
   * @param mixed value
   * @param string severity ChromePhp::LOG || ChromePhp::WARN || ChromePhp::ERROR
   * @return void
   */
  public static function log()
  {
  }

  /**
   * logs a warning to the console
   *
   * @param string label
   * @param mixed value
   * @return void
   */
  public static function warn()
  {
  }

  /**
   * logs an error to the console
   *
   * @param string label
   * @param mixed value
   * @return void
   */
  public static function error()
  {
  }

  /**
   * sends a group log
   *
   * @param string value
   */
  public static function group()
  {
  }

  /**
   * sends an info log
   *
   * @param string value
   */
  public static function info()
  {
  }

  /**
   * sends a collapsed group log
   *
   * @param string value
   */
  public static function groupCollapsed()
  {
  }

  /**
   * ends a group log
   *
   * @param string value
   */
  public static function groupEnd()
  {
  }

  /**
   * internal logging call
   *
   * @param string $type
   * @return void
   */
  protected static function _log(array $args)
  {
  }

  /**
   * converts an object to a better format for logging
   *
   * @param Object
   * @return array
   */
  protected function _convert($object)
  {
  }

  /**
   * takes a reflection property and returns a nicely formatted key of the property name
   *
   * @param ReflectionProperty
   * @return string
   */
  protected function _getPropertyKey(ReflectionProperty $property)
  {
  }

  /**
   * adds a value to the data array
   *
   * @var mixed
   * @return void
   */
  protected function _addRow($label, $log, $backtrace, $type)
  {
  }

  protected function _writeHeader($data)
  {
  }

  /**
  * Convert an object into an associative array
  *
  * This function converts an object into an associative array by iterating
  * over its public properties. Because this function uses the foreach
  * construct, Iterators are respected. It also works on arrays of objects.
  *
  * @return array
  */
  protected function object_to_array($var) {
  }

  /**
  * Convert a value to JSON
  *
  * This function returns a JSON representation of $param. It uses json_encode
  * to accomplish this, but converts objects and arrays containing objects to
  * associative arrays first. This way, objects that do not expose (all) their
  * properties directly but only through an Iterator interface are also encoded
  * correctly.
  */
  public function json_encode2($param) {
  }
  /**
   * encodes the data to be sent along with the request
   *
   * @param array $data
   * @return string
   */
  protected function _encode($data)
  {
  }

  /**
   * adds a setting
   *
   * @param string key
   * @param mixed value
   * @return void
   */
  public function addSetting($key, $value)
  {
  }

  /**
   * add ability to set multiple settings in one call
   *
   * @param array $settings
   * @return void
   */
  public function addSettings(array $settings)
  {
  }

  /**
   * gets a setting
   *
   * @param string key
   * @return mixed
   */
  public function getSetting($key)
  {
  }
}
