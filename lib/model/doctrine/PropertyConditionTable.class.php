<?php


class PropertyConditionTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PropertyCondition');
    }
}