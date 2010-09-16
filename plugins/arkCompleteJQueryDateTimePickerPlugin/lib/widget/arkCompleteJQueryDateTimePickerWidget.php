<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of arkWidgetEventDateTime
 *
 * @author alberto
 */
class arkCompleteJQueryDateTimePickerWidget extends sfWidgetForm{

  public function configure($options = array(), $attributes = array()){
      $this->addRequiredOption('lock-dates', false);
      $this->addRequiredOption('DateStartInputId', false);
      $this->addRequiredOption('DateEndInputId', false);
      $this->addOption('embedded-mode', false);

  }
  
  public function getStylesheets()
  {
    return array(
            '/arkCompleteJQueryDateTimePickerPlugin/css/datepicker.css' => 'all',
            '/arkCompleteJQueryDateTimePickerPlugin/css/timepicker.css' => 'all',
            '/arkCompleteJQueryDateTimePickerPlugin/css/date-picker-widget.css' => 'all',
            '/arkCompleteJQueryDateTimePickerPlugin/css/time-picker-ui-custom.css' => 'all',
            '/theme/css/event-box.css' => 'ark.evetbox',
        );
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array(
            '/arkCompleteJQueryDateTimePickerPlugin/js/datepicker.js',
            
            '/arkCompleteJQueryDateTimePickerPlugin/js/jquery-timepicker.js'
        );

  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {

        $prefix = $this->generateId($name);

        return
        sprintf(<<<EOF
<script type="text/javascript" >
    function getLockDates(){
        return lockDates = %s;
    }
    function getRangeDateEndID(){
        return dateStart = '%s';
    }
    function getRangeDateStartID(){
        return dateEnd = '%s';
    }
</script>
<script type="text/javascript" src="/js/admin-event-box.js">
</script>
EOF
,($this->getOption('lock-dates')?1:0),  $this->getOption('DateEndInputId'), $this->getOption('DateStartInputId')).
sprintf(<<<EOF
<div class="even sf_admin_text sf_admin_form_field_fancy_date sf_widget_form_input_text required" data-field-name="fancy_date">
      <div class="sf_admin_form_row_inner clearfix">
          <div class="content">
            <div id="widgetField">
            <span></span>
            <a href="#">Select date range</a>
            </div>
            <div id="calendar-container" ></div>
          </div>
          <div class="help" style="margin-left:0px">Haga clic en el calendario para seleccionar un d&iacute;a o rango de d&iacute;as para el evento.</div>    
      </div>
</div>
<div class="clearfix" style="margin-top:15px"> </div>
<div class="even sf_admin_text sf_admin_form_field_fancy_date sf_widget_form_input_text required" data-field-name="fancy_date">
      <div class="sf_admin_form_row_inner clearfix">
          <div class="content">
            <div style="width: %s; float: left;">
                <div class="label_wrap" style="min-width: 75px;><label for="event_admin_form_time_start">Hora inicio</label></div>
                <input type="text" class="hour-trigger" size="30" readonly="true" id="date_start_time" value="">
                <div class="help" style="margin-left: 75px">Hora de inicio del evento</div>
            </div>
            <div style="width: %s; float: left;">
                <div class="label_wrap"><label for="event_admin_form_time_end">Hora fin</label></div>
                <input type="text" class="hour-trigger text-input small-input" size="30" readonly="true" id="date_end_time" value="">
                <div class="help">Hora de culminaci&oacute;n del evento</div>
            </div>
            <div class="clearfix" > </div>
          <div class="help" style="margin-left:0px;margin-top: 10px;line-height:2;">Deje ambas horas en blanco en caso de ser un evento de todo el día.</div>    </div>
         </div>
     </div>
</div>

EOF
, '45%', '50%');
    }
}
?>
