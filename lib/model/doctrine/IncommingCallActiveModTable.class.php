<?php


class IncommingCallActiveModTable extends IncommingCallTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('IncommingCallActiveMod');
    }
}