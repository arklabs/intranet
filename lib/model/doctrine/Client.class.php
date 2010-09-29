<?php

/**
 * Client
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    intranet
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Client extends BaseClient
{
  public function __toString()
  {
    return $this->getFirstName().' '.$this->getLastName();
  }
   public function  save(Doctrine_Connection $conn = null) {
        parent::save($conn);
        // adding user to telemarketer default group by default
        if (!$this->hasGroup('cliente')){
            $group = Doctrine::getTable('DmGroup')->findByName('cliente');
            if (count($group) <= 0)
            {
                $g = Doctrine::getTable('DmGroup')->create(array('name'=>'cliente', 'description'=>'Todos los clientes'));
                $g->save();
            }

            $this->addGroupByName('cliente');
        }
    }
}
