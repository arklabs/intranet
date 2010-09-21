<?php


class AssessorTable extends DmUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Assessor');
    }
}