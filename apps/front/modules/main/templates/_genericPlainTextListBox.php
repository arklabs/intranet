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
echo _tag('h4', $boxTitle);
echo _open('ul');
foreach ($listArray as $row)
{
	$value = $row['value'];
	if ($row['type'] == 'Date')
		$value = format_date($value, 'f','es_ES');
	echo _tag('li style="line-height: 18px;"', sprintf('<b>%s</b>: %s',$row['label'], $row['value']));
}
if (count($listArray) == 0){
	echo _tag('li', "No hay contenido disponible");
}
echo _close('ul');


?>

