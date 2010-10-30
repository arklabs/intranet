<?php


class EventGettedDocumentTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventGettedDocument');
    }
}