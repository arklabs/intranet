<?php
/**
 * Disponibilidad components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class userAvailabilityComponents extends myFrontModuleComponents
{

  public function executeAvailabilityCalendar()
  {
    // Your code here
  }

  public function executeChangeAvailabilities()
  {
     $this->monthsWidget = new arkMultipleMonthSelector();
     $this->daysWidget = new arkMultipleWeekDaySelector();
     $this->iButtonWidget = new arkSimpleiButton(array('js-after-ibutton-init'=>'$("#ibutton-area-to-hide").css("display","none" );'));

  }


}
