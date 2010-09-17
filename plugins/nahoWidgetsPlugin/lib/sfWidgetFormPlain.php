<?php

/**
 * sfWidgetFormPlain displays plain text values.
 *
 * @package    nahoWidgets
 * @subpackage widget
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: sfWidgetFormPlain.php 16889 2009-04-01 21:15:03Z naholyr $
 */
class sfWidgetFormPlain extends sfWidgetFormInput
{
  
  /**
   * Available options :
   * - callbacks : array of "callback" => (arguments), will apply all the callbacks with value + arguments, and
   * transform the value with the result. If you want to call a static method use "class::method" => (arguments).
   * - format : format of the output. %s will be replaced with the transformed value.
   * - with_hidden : set to false not to include a input hidden with the real value. You need to set to false if
   * the value is from a non-real field in PropelForms.
   *  
   * @see lib/vendor/symfony/lib/widget/sfWidgetFormInput#configure()
   * @param array $options
   * @param array $attributes
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('callbacks', array());
    $this->addOption('format', '%s');
    $this->addOption('with_hidden', true);
  }

  /**
   * Renders the widget : 
   * - transforms $value using "callbacks" option.
   * - generates output using "format" option.
   * - adds a hidden input if "with_hidden" is not false.
   * 
   * @see sfWidgetFormRichDate#render()
   * @param string $name
   * @param string $value
   * @param array $attributes
   * @param array $errors
   * @return string
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $callbacks = $this->getOption('callbacks');
    if (is_string($callbacks) && !strpos($callbacks, '=')) {
      $callbacks .= '=~';
    }
    if (!is_array($callbacks)) {
      $callbacks = sfToolkit::stringToArray($callbacks);
    }
    
    $html = $value;
    
    foreach ($this->getOption('callbacks') as $callback => $args) {
      if (strpos($callback, '::') !== false) {
        $callback = explode('::', $callback);
      }
      if (is_null($args) || $args == '~') {
        $args = array();
      } elseif (!is_array($args)) {
        $args = explode(',', $args);
      }
      array_unshift($args, $html);
      $html = call_user_func_array($callback, $args);
    }
    
    $html = sprintf($this->getOption('format'), $html);
    
    if ($this->getOption('with_hidden')) {
      $html = parent::render($name, $value, array_merge($attributes, array('type' => 'hidden')), $errors) . $html;
    }
    
    return $html;
  }
  
}
