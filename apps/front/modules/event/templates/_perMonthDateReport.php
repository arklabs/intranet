<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_helper('Date');

$table = _table('.data_table style="background-color: red; border: 1px"')->head(
 
  __('Asignado a'),
  __('Cantidad')
);

foreach ($dates as $event)
{
  $table->body(

  $event->DmUser[0],
  $event->num_events
  );
}

echo $table;
