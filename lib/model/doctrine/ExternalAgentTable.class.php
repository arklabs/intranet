<?php


class ExternalAgentTable extends DmUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('ExternalAgent');
    }
}