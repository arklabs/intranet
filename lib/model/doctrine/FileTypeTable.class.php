<?php


class FileTypeTable extends myDoctrineTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('FileType');
    }
}