<?php


class TelemarkerTable extends DmUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Telemarker');
    }
}