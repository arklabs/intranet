<?php
/**
 * Reuni&oacute;n &oacute; Recordatorio components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 */
class eventRootComponents extends myFrontModuleComponents
{

  public function executePrivateCalendar()
  {
    // Your code here
  }

  public function executePrivateCalendarLegend()
  {
      $this->legends = array(
          'Tipos de Eventos' => array(
              '<span class="ark-icon-2-16 ark-icon-private ark-icon-left"></span>'=>'Privado',
              '<span class="ark-icon-2-16 ark-icon-reminder ark-icon-left"></span>'=>'Recordatorio',
          )
      );
  }


}
