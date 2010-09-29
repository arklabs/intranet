<?php

/**
 * rss actions.
 *
 * @package    reserver
 * @subpackage rss
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class rssActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
      $token = $request->getParameter('token');
      $username = $request->getParameter('username');
      $this->ownerUser = Doctrine::getTable('DmUser')->createQuery('u')->where('u.username = ?', $username)->andWhere('u.salt LIKE ?',$token.'%')->fetchOne();
      if (!$request->hasParameter('date_start')){
        $firstMonthDay = new sfDate(time());
        $firstMonthDay->clearTime()->firstDayOfMonth();
      }
      else{
          $firstMonthDay = new sfDate($request->getParameter('date_start'));
          $firstMonthDay->clearTime();
      }
      if (!$request->hasParameter('date_end')){
          $inTwoMonthDay = $firstMonthDay->copy()->addDay(32)->finalDayOfMonth();
      }
      else {
          $inTwoMonthDay = new sfDate($request->getParameter('date_end'));
          $inTwoMonthDay->clearTime();
      }
      
      $q = null;
Doctrine::getTable('Event')->starting($this->date_start = $firstMonthDay->dump(), $q)->ending($this->date_end = $inTwoMonthDay->dump(), $q)->forUser($this->ownerUser->getId(), $q);

      $this->entries = $q->execute();
 }


}
