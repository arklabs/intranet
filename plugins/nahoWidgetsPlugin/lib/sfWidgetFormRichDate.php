<?php

/**
 * sfWidgetFormRichDate is a widget using DynArch's JSCalendar to render a date field.
 *
 * @package    nahoWidgets
 * @subpackage widget
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: sfWidgetFormRichDate.php 16372 2009-03-17 15:49:00Z naholyr $
 */
class sfWidgetFormRichDate extends sfWidgetFormDate
{
  
  /**
   * Options :
   * - single_input : set to false to fall back to the three-selectbox display.
   * - input_hidden : used only if single_input is not false : if set to true, generates a hidden input.
   * - jscal_format : defines the way the default widget field and the calender trigger will be displayed.
   * - jscal_setup : options passed to the JSCalendar setup.
   * - sf_date_format : format of the date in the input field if using "format = input".
   * - jscal_path : public path to JSCalendar base directory.
   * - jscal_skins : public path to JSCalendar skins directory.
   * - jscal_theme : name of the theme (default = minium). Look in the "skins" folder to add/list themes.
   * - jscal_style : name of the style to be included (default = system). Look for the *.css in base directory to add/list styles.
   * - jscal_lang : JSCalendar localization to be included. Look for lang/calendar-*.js files in base directory.
   * - jscal_include_js : set to false to disable automatic JS includes. You'll then have to include it manually.
   * - jscal_include_css : set to false to disable automatic CSS includes. You'll then have to include it manually.
   * - jscal_image : public path to the image used as trigger.
   * - jscal_trigger_label : label attached to the trigger.
   * 
   * @see lib/vendor/symfony/lib/widget/sfWidgetFormDate#configure()
   * @param array $options
   * @param array $attributes
   */
  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('jscal_format', sfConfig::get('app_jscalendar_format_date', '%date% %calendar%'));
    
    $this->addOption('jscal_setup', sfConfig::get('app_jscalendar_setup', array()));
    
    $this->addOption('sf_date_format', sfConfig::get('app_jscalendar_sf_date_format', 'dd/MM/yyyy'));
    
    $this->addOption('plain', false);
    
    $this->addOption('single_input', true);
    $this->addOption('input_hidden', false);
    $this->addOption('diem-admin-mode', false);
    
    /* TODO
    $this->addOption('allow_future', true);
    $this->addOption('allow_past', true);
    
    // JS generated :
    cal.setDisabledHandler(function(date, year, month, day) {
      // verify date and return true if it has to be disabled
      // ``date'' is a JS Date object, but if you only need the
      // year, month and/or day you can get them separately as
      // next 3 parameters, as you can see in the declaration
      if (year == 2004) {
        // disable all dates from 2004
        return true;
      }
      return false;
    });
    */
    
    $this->addOption('jscal_path', $path = sfConfig::get('app_jscalendar_path', '/nahoWidgetsPlugin/jscalendar'));
    $this->addOption('jscal_skins', sfConfig::get('app_jscalendar_skins', '/nahoWidgetsPlugin/jscalendar/skins'));
    $this->addOption('jscal_theme', sfConfig::get('app_jscalendar_theme', 'minium'));
    $this->addOption('jscal_style', sfConfig::get('app_jscalendar_style', 'system'));
    $this->addOption('jscal_lang', sfConfig::get('app_jscalendar_lang', sfContext::getInstance()->getUser()->getCulture()));
    $this->addOption('jscal_include_js', sfConfig::get('app_jscalendar_include_js', true));
    $this->addOption('jscal_include_css', sfConfig::get('app_jscalendar_include_css', true));
    $this->addOption('jscal_image', sfConfig::get('app_jscalendar_image', $path . '/img.gif'));
    $this->addOption('jscal_trigger_label', sfConfig::get('app_jscalendar_trigger_label', '...'));
  }
  
  protected $sf_to_js = array(
    'G'  => '...',
    'yyyy' => '%Y',
    'yy'  => '%y',
    'MMMM' => '%B',
    'MMM' => '%b',
    'MM' => '%m',
    'M'  => '%m',
    'EEEE' => '%A',
    'EEE' => '%a',
    'EE' => '%w',
    'E' => '%w',
    'dddd' => '%A',
    'dd' => '%d',
    'd' => '%e',
    'HH' => '%H',
    'H' => '%k',
    'a' => '%p',
    'hh' => '%I',
    'h' => '%l',
    'mm' => '%M',
    'm' => '%M', // FIXME getMinutes "m" for non-padding
    'ss' => '%S',
    's' => '%S', // FIXME getSeconds "s" for non-padding
    'z' => '', // FIXME getTimeZone
    'D' => '%j',
    'FF' => '%d',
    'F'  => '%e',
    'w' => '', // FIXME getWeekInYear
    'W' => '', // FIXME getWeekInMonth
    'k' => '%k',
    'K' => '%l',
  );
  
  /**
   * Transforms a Symfony date format to a JSCalendar-compatible date format.
   * 
   * @param string $sfPattern
   * @return string
   */
  protected function getJSDateFormat($sfPattern)
  {
    $culture = sfContext::getInstance()->getUser()->getCulture();
    $dateFormat = new sfDateFormat($culture);
    $pattern = $dateFormat->getPattern($sfPattern);
    
    return strtr($pattern, $this->sf_to_js);
  }
  
  /**
   * Gets the date reformatted, for display
   * 
   * @param string $value
   * @param string $format
   * @return string
   */
  public function getDateValue($value = null, $format = null)
  {
    if ($value) {
      if (is_null($format)) {
        $format = $this->getOption('sf_date_format');
      }
      $user = sfContext::getInstance()->getUser();
      $culture = $user ? $user->getCulture() : null;
      $charset = sfConfig::get('sf_charset', 'utf-8');
      $dateFormat = new sfDateFormat($culture);
      
      $value = $dateFormat->format($value, $format, null, $charset);
    }

    return $value;
  }
  
  /**
   * Renders the main field of the date widget.
   * Rendering depends on the "format" option :
   * - if set to "input", a simple input field will be used.
   * - otherwise, a three-select-box field will be used.
   * 
   * @param string $name
   * @param string $value
   * @param array $attributes
   * @param array $errors
   * @return string
   */
  public function renderField($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($this->getOption('single_input')) {
      //$value = $this->getDateValue($value);
      
      $attributes = array_merge($attributes, array(
        'type'  => $this->getOption('input_hidden') ? 'hidden' : 'text', 
        'name'  => $name, 
        'id'    => $this->generateId($name, $value),
        'value' => $value,
      ));
      $this->addOption('input_id');
      $this->setOption('input_id', $attributes['id']);
      return $this->renderTag('input', $attributes);
    } else {
      return parent::render($name, $value, $attributes, $errors);
    }
  }
  
  /**
   * Renders the JSCalendar field of the date widget.
   * Mainly displays the trigger and initializes the calendar.
   * 
   * @param string $name
   * @param string $value
   * @param array $setup
   * @return string
   */
  public function renderCalendar($name, $value = null, $setup = array())
  {
    $response = sfContext::getInstance()->getResponse();
    // Javascripts : we don't use getJavascripts as files can be included more than once with this method
    $response->addJavascript($this->getOption('jscal_path') . '/calendar.js');
    $response->addJavascript($this->getOption('jscal_path') . '/lang/calendar-' . $this->getOption('jscal_lang') . '.js');
    $response->addJavascript($this->getOption('jscal_path') . '/calendar-setup.js');
    // CSS : same
    $response->addStylesheet($this->getOption('jscal_skins') . '/' . $this->getOption('jscal_theme') . '/theme.css');
    $response->addStylesheet($this->getOption('jscal_path') . '/' . $this->getOption('jscal_style') . '.css');

    if ($this->hasOption('diem-admin-mode') && $this->getOption('diem-admin-mode')){
        $trigger_id = $this->getOption('input_id');
        $html = '';
    }
    else{
       $trigger_id = 'jscal_'.uniqid('');
        if ($image = sfToolkit::replaceConstants($this->getOption('jscal_image'))) 
          $html = image_tag($image, array('id' => $trigger_id, 'alt' => $this->getOption('jscal_trigger_label')));
        else 
            $html = $this->renderTag('input', array('type' => 'button', 'id' => $trigger_id, 'value' => $this->getOption('jscal_trigger_label')));
    }
    
    $setup['button'] = $trigger_id;
    
    // Format = "input" : Bind calendar and the input field using the original onSelect handler and inputField option
    if ($this->getOption('single_input')) {
      $setup['inputField'] = $this->generateId($name, $value);
      $setup['ifFormat'] = $this->getJSDateFormat($this->getOption('sf_date_format'));
    }
    // Otherwise, we set the onSelect handler to change the values
    else {
      // get date from inputs when opening calendar
      $setup['date'] = '~'
        .'(document.getElementById("'.$this->generateId($name.'[month]').'").value)+" "'
        .'+(document.getElementById("'.$this->generateId($name.'[day]').'").value)+", "'
        .'+(document.getElementById("'.$this->generateId($name.'[year]').'").value)';
      // track change to the inputs
      $setup['onSelect'] = 'function(cal){'
        .'document.getElementById("'.$this->generateId($name.'[day]').'").value = cal.date.getDate();'
        .'document.getElementById("'.$this->generateId($name.'[month]').'").value = cal.date.getMonth()+1;'
        .'document.getElementById("'.$this->generateId($name.'[year]').'").value = cal.date.getFullYear();'
        // Add original onSelect function
        .'var p = cal.params;
          var update = (cal.dateClicked || p.electric);
          if (update && p.inputField) {
            p.inputField.value = cal.date.print(p.ifFormat);
            if (typeof p.inputField.onchange == "function")
              p.inputField.onchange();
          }
          if (update && p.displayArea)
            p.displayArea.innerHTML = cal.date.print(p.daFormat);
          if (update && typeof p.onUpdate == "function")
            p.onUpdate(cal);
          if (update && p.flat) {
            if (typeof p.flatCallback == "function")
              p.flatCallback(cal);
          }
          if (update && p.singleClick && cal.dateClicked)
            cal.callCloseHandler();'
        .'}';
    }
    $setup_str = $this->buildJSCalendarSetup($setup);
    
    $script = 'Calendar.setup('.$setup_str.');';
    $html .= $this->renderContentTag('script', $script, array('type' => 'text/javascript'));
    
    return $html;
  }
  
  protected function replaceOptionCallback(array $matches)
  {
    $value = $this->getOption($matches[1]);
    if (is_null($value)) {
      return $matches[0];
    } else {
      return $value;
    }
  }
  
  protected function replaceOptions($str)
  {
    return preg_replace_callback('/%(.*?)%/', array($this, 'replaceOptionCallback'), $str);
  }
  
  protected function buildJSCalendarSetup(array $setup)
  {
    $setup_str = '';
    foreach ($setup as $jscal_option => $jscal_value) {
      $jscal_value = $this->replaceOptions($jscal_value);
      if ($setup_str != '') {
        $setup_str .= ',';
      } else {
        $setup_str .= '{';
      }
      if (substr($jscal_option, 0, 2) != 'on' && substr($jscal_option, -4) != 'Func' && $jscal_value{0} != '~') {
        $jscal_value = json_encode($jscal_value);
      } elseif ($jscal_value{0} == '~') {
        $jscal_value = substr($jscal_value, 1);
      }
      $setup_str .= $jscal_option.':'.$jscal_value;
    }
    $setup_str .= '}';
    
    return $setup_str;
  }
  
  /**
   * Renders the widget using renderCalendar() and renderField(), depending on "jscal_format" option.
   * 
   * @see lib/vendor/symfony/lib/widget/sfWidgetFormDate#render()
   * @see renderField()
   * @see renderCalendar()
   * @param string $name
   * @param string $value
   * @param array $attributes
   * @param array $errors
   * @return string
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return strtr($this->replaceOptions($this->getOption('jscal_format')), array(
      '%date%'     => $this->renderField($name, $value, $attributes, $errors),
      '%calendar%' => $this->renderCalendar($name, $value, $this->getOption('jscal_setup')),
    ));
  }
  
}
