<?php


class EventSignedDocumentTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EventSignedDocument');
    }
}