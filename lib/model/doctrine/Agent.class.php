<?php

/**
 * Agent
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    intranet
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Agent extends BaseAgent
{
    public function getClientsQuery() {
        $q = Doctrine_Query::create()
        ->from('Client c')
        ->where('c.assigned_to = ?', $this->getId());

       return $q;
    }

    public function getClients() {
        return $this->getClientsQuery()->execute();
    }

    public function countClients() {
        return $this->getClientsQuery()->count();
    }

    public function  save(Doctrine_Connection $conn = null) {
        parent::save($conn);
        // adding user to telemarketer default group by default
        if (!$this->hasGroup('agente')){
            $group = Doctrine::getTable('DmGroup')->findByName('agente');
            if (count($group) <= 0)
            {
                $g = Doctrine::getTable('DmGroup')->create(array('name'=>'agente', 'description'=>'Todos los agentes'));
                $g->save();
            }

            $this->addGroupByName('agente');
        }
    }
}