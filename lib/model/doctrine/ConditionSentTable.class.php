<?php


class ConditionSentTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ConditionSent');
    }
}