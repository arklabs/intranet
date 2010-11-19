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

    public static function getPublicUser(){
        return Doctrine::getTable('DmUser')->createQuery('u')->where('u.username = ?','public')->fetchOne();
    }
}