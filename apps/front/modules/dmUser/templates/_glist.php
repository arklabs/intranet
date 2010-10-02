<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_helper('Date');

// Plugin : List
// Vars : $pluginPager
$sfModule = 'event';
$head = array();
$tableHead = '';
$tableHead.= _open('th.fg-toolbar.ui-widget-header.ui-corner-tl.ui-corner-tr', array('colspan'=>count($showColumns))).(isset($boxTitle)?$boxTitle:'<br/>')._close('th');
$tableHead = _tag('tr', $tableHead);
$tableHead.= _open('tr');
foreach (array_keys($showColumns) as $column){
    $tableHead.= _tag('th.ui-state-default',$showColumns[$column]['label']);
}
$tableHead.= _close('tr');
$tableBody = '';
$i=0;
foreach ($listArray as $row)
{
  $rowContent = '';
  foreach (array_keys($showColumns) as $column){
      try{
      if ($showColumns[$column]['is_relation'] == 1){
          $value =  $row->$column;
      }
      else
          $value = $row[$column];
      }catch(Exception $e){die($e->getMessage());}
      if ($showColumns[$column]['type'] == 'date')
          $value = format_date($value, 'EEEE d MMMM y','es_ES');
      if (array_key_exists('link_to_object', $showColumns[$column]) && $showColumns[$column]['link_to_object'] == true)
          $value = _link($row)->text($value);
      $rowContent.= _tag('td', $value);
  }
  $tableBody.= _tag('tr.'.((++$i % 2)?'even':'odd'), $rowContent);
}
if (count($listArray) == 0){
	$tableBody.= _tag('tr.even', _open('td', array('colspan'=>count($showColumns)))."No hay contenido disponible"._close('td'));
}
$tableFoot = _open('th.fg-toolbar.ui-widget-header.ui-corner-bl.ui-corner-br', array('colspan'=>count($showColumns)))._close('th');
$tableFoot = _tag('tr', $tableFoot);
echo _open('span.dataTables.wrapper');
    echo _tag('table.data_table.ui-corner-tl.ui-corner-tr', _tag('thead',$tableHead)._tag('tbody',$tableBody)._tag('tfoot', $tableFoot));
echo _close('span');

