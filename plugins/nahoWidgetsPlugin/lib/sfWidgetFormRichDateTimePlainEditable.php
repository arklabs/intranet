<?php

/**
 * sfWidgetFormRichDateTimePlainEditable is a widget using DynArch's JSCalendar to render a date+time
 * field into a plain text format, with a calendar to change its value.
 *
 * @package    nahoWidgets
 * @subpackage widget
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: sfWidgetFormRichDateTimePlainEditable.php 16372 2009-03-17 15:49:00Z naholyr $
 */
class sfWidgetFormRichDateTimePlainEditable extends sfWidgetFormRichDateTime
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
    
    // Default class is plain editable
    $this->addOption('date_widget_class', 'sfWidgetFormRichDatePlainEditable');
    
    // New options
    $this->addOption('display_id', uniqid(''));
    $this->addOption('display_format', 'F');
    
    // Change jscal_format default value
    $this->addOption('jscal_format', '%date% %time%<span id="%display_id%">%date_value%</span> %calendar%');
  }
  
  /**
   * Returns the completed date options
   * 
   * @param array $date_options
   * @return array
   */
  protected function getDateWidgetOptions(array $date_options = array())
  {
    $date_options['display_id'] = $this->getOption('display_id');
    $date_options['display_format'] = $this->getOption('display_format');
    
    return parent::getDateWidgetOptions($date_options);
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/nahoWidgetsPlugin/lib/sfWidgetFormRichDate#render()
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return strtr(parent::render($name, $value, $attributes, $errors), array(
      '%date_value%' => $this->getDateWidget()->getDateValue($value, $this->getOption('display_format')),
    ));
  }
  
}
