<?php

/**
 * sfWidgetFormRichDateTime is a widget using DynArch's JSCalendar to render a datetime field.
 *
 * @package    nahoWidgets
 * @subpackage widget
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: sfWidgetFormRichDateTime.php 16372 2009-03-17 15:49:00Z naholyr $
 */
class sfWidgetFormRichDateTime extends sfWidgetFormDateTime
{
  
  /**
   * Embedded RichDate widget
   * 
   * @var sfWidgetFormRichDate
   */
  protected $date_widget = null;
  
  /**
   * Options :
   * - jscal_format : defines the way the default widget field and the calender trigger will be displayed.
   * - sf_date_format : format of the date in the input field if using "format = input".
   * - date : options passed to sfWidgetFormRichDate widget
   * @see sfWidgetFormRichDate#configure()
   * 
   * @see lib/vendor/symfony/lib/widget/sfWidgetFormDateTime#configure()
   * @param array $options
   * @param array $attributes
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('date_widget_class', 'sfWidgetFormRichDate');
    
    $this->addOption('single_input', true);
    $this->addOption('input_hidden', false);
    
    $this->addOption('jscal_format', sfConfig::get('app_jscalendar_format_datetime', '%date% %time% %calendar%'));
    $this->addOption('sf_date_format', sfConfig::get('app_jscalendar_sf_datetime_format', 'dd/MM/yyyy HH:mm:ss'));
  }
  
  /**
   * Returns the completed date options
   * 
   * @param array $date_options
   * @return array
   */
  protected function getDateWidgetOptions(array $date_options = array())
  {
    if ($this->getOption('with_time')) {
      if (!isset($date_options['jscal_setup'])) {
        $date_options['jscal_setup'] = array();
      }
      $date_options['jscal_setup']['showsTime'] = true;
    }
    
    if (!isset($date_options['sf_date_format'])) {
      $date_options['sf_date_format'] = $this->getOption('sf_date_format');
    }
    
    $date_options['jscal_format'] = $this->getOption('jscal_format');
    $date_options['single_input'] = $this->getOption('single_input');
    $date_options['input_hidden'] = $this->getOption('input_hidden');
    
    return $date_options;
  }
  
  /**
   * Returns the date widget.
   *
   * @param  array $attributes  An array of attributes
   * @return sfWidgetFormRichDate A Widget representing the date
   */
  protected function getDateWidget($attributes = array())
  {
    if (is_null($this->date_widget)) {
      $this->setOption('date', $this->getDateWidgetOptions($this->getOption('date')));
      $class = $this->getOption('date_widget_class');
      $this->date_widget = new $class($this->getOptionsFor('date'), $this->getAttributesFor('date', $attributes));
    }
    
    return $this->date_widget;
  }
  
  /**
   * @see sfWidgetFormRichDate#getJavascripts()
   * @see lib/vendor/symfony/lib/widget/sfWidget#getJavascripts()
   * @return array
   */
  public function getJavascripts()
  {
    return $this->getDateWidget()->getJavascripts();
  }
  
  /**
   * @see sfWidgetFormRichDate#getStylesheets()
   * @see lib/vendor/symfony/lib/widget/sfWidget#getStylesheets()
   * @return array
   */
  public function getStylesheets()
  {
    return $this->getDateWidget()->getStylesheets();
  }
  
  /**
   * Renders the widget using the embedded date widget, plus the time-specific fields
   * 
   * @see sfWidgetFormRichDate#render()
   * @param string $name
   * @param string $value
   * @param array $attributes
   * @param array $errors
   * @return string
   */
  function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // Date
    $date_widget = $this->getDateWidget($attributes);
    // Returns the date rendering, including marker %time% not modified
    $date_render = $date_widget->render($name, $value, $attributes, $errors);
    
    // Time
    if (!$this->getOption('with_time') || $this->getOption('single_input')) {
      // Single input or no time included : no need for time rendering
      $time_render = '';
    } else {
      // Returns the time rendering, which will replace %time%
      $time_render = $this->getTimeWidget($attributes)->render($name, $value);
    }
    
    // Final rendering
    return strtr($date_render, array('%time%' => $time_render));
  }
  
}
