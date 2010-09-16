<?php

/**
 * sfValidatorRichDate validates a date+time. It also converts the input value to a valid date+time.
 * Using this dedicated validator is not required if you set "single_input" to false.
 *
 * @see        lib/vendor/symfony/lib/validator/sfValidatorDate
 * @package    nahoWidgets
 * @subpackage validator
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: sfValidatorRichDateTime.php 16368 2009-03-17 15:03:49Z naholyr $
 */
class sfValidatorRichDateTime extends sfValidatorRichDate
{
  
  /**
   * Available options :
   * - all the ones from sfValidatorRichDate.
   * 
   * @see plugins/nahoWidgetsPlugin/lib/sfValidatorRichDate#configure()
   * @param array $options
   * @param array $messages
   */
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    
    $this->addOption('sf_date_format', sfConfig::get('app_jscalendar_sf_datetime_format', 'dd/MM/yyyy HH:mm:ss'));
    $this->addOption('with_time', true);
  }
  
}
