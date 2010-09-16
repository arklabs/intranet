<?php

/**
 * sfWidgetFormRichDatePlainEditable is a widget using DynArch's JSCalendar to render a date field
 * into a plain text format, with a calendar to change its value.
 *
 * @package    nahoWidgets
 * @subpackage widget
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: sfWidgetFormRichDatePlainEditable.php 16368 2009-03-17 15:03:49Z naholyr $
 */
class sfWidgetFormRichDatePlainEditable extends sfWidgetFormRichDate
{
  
  /**
   * Override constructor to force ignoring parent options "input_hidden" and "single_input"
   * 
   * @param array $options
   * @param array $attributes
   * @return sfWidgetFormRichDatePlainEditable
   */
  public function __construct($options = array(), $attributes = array())
  {
    // Ignore options "input_hidden" and "single_input" whose values are forced
    $options['input_hidden'] = true;
    $options['single_input'] = true;
    
    parent::__construct($options, $attributes);
  }
  
  /**
   * Options :
   * - display_id : ID of the layer where date is displayed (defaults to a random uniq ID).
   * - display_format : Format for the displayed date (defaults to "D").
   * - all options of sfWidgetFormRichDate, excepting 
   *    - "input_hidden" and "single_input" which are ignored
   *    - "jscal_format" has two other markers "%display_id%" and "%date_value%", and a changed
   *    default value "%date%<span id="%display_area_id%">%date_value%</span> %calendar%"
   * 
   * @see plugins/nahoWidgetsPlugin/lib/sfWidgetFormRichDate#configure()
   * @param array $options
   * @param array $attributes
   */
  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    // New options
    $this->addOption('display_id', uniqid(''));
    $this->addOption('display_format', 'D');
    
    // Change jscal_format default value
    $this->addOption('jscal_format', '%date%<span id="%display_id%">%date_value%</span> %calendar%');
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/nahoWidgetsPlugin/lib/sfWidgetFormRichDate#renderCalendar()
   */
  public function renderCalendar($name, $value = null, $setup = array())
  {
    return parent::renderCalendar($name, $value, array_merge($setup, array(
      'displayArea' => $this->getOption('display_id'),
      'daFormat' => $this->getJSDateFormat($this->getOption('display_format')),
    )));
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/nahoWidgetsPlugin/lib/sfWidgetFormRichDate#render()
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return strtr(parent::render($name, $value, $attributes, $errors), array(
      '%date_value%' => $this->getDateValue($value, $this->getOption('display_format')),
    ));
  }
  
}