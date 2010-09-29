<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_helper('Date');

/* Formato del arreglo que genera la lista
 *  array(
 *    array('label=>'label del valor', value=>'valor', type=>'string o date'),
 *    array('label=>'label del valor', value=>'valor', type=>'string o date'),
 *  )
 */

// Plugin : List
// Vars : $pluginPager
$boxTitle = isset($boxTitle)?$boxTitle:'';
$head = array();
$tableHead = '';
$tableHead.= _open('th.fg-toolbar.ui-widget-header.ui-corner-tl.ui-corner-tr', array('colspan'=>2)).$boxTitle.'<br/>'._close('th');
$tableHead = _tag('tr', $tableHead);
$tableBody = '';
$i=0;
foreach ($data as $row)
{
	$value = $row['value'];
	if ($row['type'] == 'Date')
		$value = format_date($value, 'EEEE d MMMM y','es_ES');
	$rowContent = '';
	$rowContent.=_tag('td.ui-state-default',$row['label']);	
	$rowContent.=_tag('td.',$row['value']);
    $tableBody.= _tag('tr.'.((++$i % 2)?'even':'odd'), $rowContent);
}
if (count($data) == 0){
	$tableBody.= _tag('tr.even', _open('td', array('colspan'=>2))."No hay contenido disponible"._close('td'));
}
$tableFoot = _open('th.fg-toolbar.ui-widget-header.ui-corner-bl.ui-corner-br', array('colspan'=>2))._close('th');
$tableFoot = _tag('tr', $tableFoot);
echo _open('div.dataTables.wrapper');
    echo _tag('table.data_table.ui-corner-tl.ui-corner-tr', _tag('thead',$tableHead)._tag('tbody',$tableBody)._tag('tfoot', $tableFoot));
echo _close('div');
?>

