<?php

/**
 * BaseDmZone
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $dm_area_id
 * @property string $css_class
 * @property string $width
 * @property DmArea $Area
 * @property Doctrine_Collection $Widgets
 * 
 * @method integer             getDmAreaId()   Returns the current record's "dm_area_id" value
 * @method string              getCssClass()   Returns the current record's "css_class" value
 * @method string              getWidth()      Returns the current record's "width" value
 * @method DmArea              getArea()       Returns the current record's "Area" value
 * @method Doctrine_Collection getWidgets()    Returns the current record's "Widgets" collection
 * @method DmZone              setDmAreaId()   Sets the current record's "dm_area_id" value
 * @method DmZone              setCssClass()   Sets the current record's "css_class" value
 * @method DmZone              setWidth()      Sets the current record's "width" value
 * @method DmZone              setArea()       Sets the current record's "Area" value
 * @method DmZone              setWidgets()    Sets the current record's "Widgets" collection
 * 
 * @package    retest
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDmZone extends myDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('dm_zone');
        $this->hasColumn('dm_area_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('css_class', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('width', 'string', 15, array(
             'type' => 'string',
             'length' => 15,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('DmArea as Area', array(
             'local' => 'dm_area_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('DmWidget as Widgets', array(
             'local' => 'id',
             'foreign' => 'dm_zone_id'));

        $sortable0 = new Doctrine_Template_Sortable(array(
             ));
        $this->actAs($sortable0);
    }
}