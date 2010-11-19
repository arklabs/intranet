<?php
/**
 * Evento P&uacute;blico components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 * 
 */
class eventPublicComponents extends myFrontModuleComponents
{

  public function executePublicCalendar()
  {
    // Your code here
  }

  public function executePublicCalendarLeyend()
  {
    $this->legends = array(
          'Tipos de Eventos' => array(
              '<span class="ark-icon-2-16 ark-icon-reminder ark-icon-left"></span>'=>'Recordatorio',
              '<span class="ark-icon-2-16 ark-icon-bussiness-meeting ark-icon-left"></span>'=>'Reuni&oacute;n'
          )
      );
  }


}
