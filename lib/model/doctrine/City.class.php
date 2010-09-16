<?php

/**
 * City
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    intranet
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class City extends BaseCity
{
    public function  __toString() {
        return $this->getName() . ', ' . $this->getState();
    }

     public function getActiveEventsQuery($dstart, $dend) {
       $q = Doctrine_Query::create()
        ->from('Event e')
        ->where('e.city_id = ?', $this->getId());

       return Doctrine::getTable('Event')->addActiveEventsQuery($q, $dstart, $dend, false);

   }

   public function getActiveEventsByCategoryQuery($catId, $dstart, $dend) {
       $q = $this->getActiveEventsQuery($dstart, $dend);
       return Doctrine::getTable('Event')->addActiveEventsByCategoryQuery($catId, $q);
   }

   public function getActiveEventsByCategory($catId, $dstart, $dend) {
       return $this->getActiveEventsByCategoryQuery($catId, $dstart, $dend)->execute();
   }

   public function countActiveEventsByCategory($catId, $dstart, $dend) {
       return $this->getActiveEventsByCategoryQuery($catId, $dstart, $dend)->count();
   }

   public function getActiveEventsByStatusQuery($statusId, $dstart, $dend) {
       $q = $this->getActiveEventsQuery($dstart, $dend);
       return Doctrine::getTable('Event')->addActiveEventsByStatusQuery($statusId, $q);
   }

   public function getActiveEventsByStatus($statusId, $dstart, $dend) {
       return $this->getActiveEventsByStatusQuery($statusId, $dstart, $dend)->execute();
   }

   public function countActiveEventsByStatus($statusId, $dstart, $dend) {
       return $this->getActiveEventsByStatusQuery($statusId, $dstart, $dend)->count();
   }


}
