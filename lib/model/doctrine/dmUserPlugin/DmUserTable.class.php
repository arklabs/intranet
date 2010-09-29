<?php


class DmUserTable extends PluginDmUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('DmUser');
    }
    public static function usernameExist($username){
        return (Doctrine::getTable('DmUser')->findByUsername($username)->count() > 0);
    }
}