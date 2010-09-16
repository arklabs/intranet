<?php

/**
 * sfValidatorRichDate validates a date. It also converts the input value to a valid date.
 * Using this dedicated validator is not required if you set "single_input" to false.
 *
 * @see        lib/vendor/symfony/lib/validator/sfValidatorDate
 * @package    nahoWidgets
 * @subpackage validator
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: sfValidatorRichDate.php 16368 2009-03-17 15:03:49Z naholyr $
 */
class sfValidatorRichDate extends sfValidatorDate
{
  
  /**
   * Available options :
   * - all the ones from sfValidatorDate.
   * - sf_date_format : the date format used for sfWidgetFormRichDate.
   * 
   * @see lib/vendor/symfony/lib/validator/sfValidatorDate#configure()
   * @param array $options
   * @param array $messages
   */
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    
    $this->addOption('sf_date_format', sfConfig::get('app_jscalendar_sf_date_format', 'dd/MM/yyyy'));
  }
  
  /**
   * Tries to compute date using sf_date_format
   * 
   * @see lib/vendor/symfony/lib/validator/sfValidatorDate#doClean()
   * @param string $value
   * @return $value
   */
  protected function doClean($value)
  {
       $date = new sfDate($value);
/*    if (is_string($value)) {
      // Parse date using date_format and generate a standard date string, understandable by sfValidatorDate
        return $value;
      $culture = sfContext::getInstance()->getUser()->getCulture();
      $charset = sfConfig::get('sf_charset', 'utf-8');
      $dateFormat = new sfDateFormat($culture);
      $date = new sfDate($value);
      $date->dump(); die;
      $date = $dateFormat->getDate($value, $this->getOption('sf_date_format'));
      $value = strtr('year-mon-mday' . ($this->getOption('with_time') ? ' hours:minutes:seconds' : ''), $date); 
    }*/
    
    return parent::doClean($date->dump());
  }
  
}
