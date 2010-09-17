<?php

/**
 * sfWidgetFormPlainDate displays plain text date.
 *
 * @package    nahoWidgets
 * @subpackage widget
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id: sfWidgetFormPlainDateTime.php 16978 2009-04-04 13:26:25Z naholyr $
 */
class sfWidgetFormPlainDateTime extends sfWidgetFormPlain
{
  
  /**
   * Adds format_datetime() to the callbacks.
   * 
   * @see sfWidgetFormPlain#configure()
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    sfProjectConfiguration::getActive()->loadHelpers('Date');
    
    $this->addOption('callbacks', array('format_datetime' => null));
  }
  
}
