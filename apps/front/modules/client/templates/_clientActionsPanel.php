<?php use_stylesheet('ark-icons-1-16');?>
<ul role="listbox">

  <li><a role="menuitem" class="ark-clear-left" href="<?php echo _link('client/resumenDelCliente')->params(array('pk'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-view-client-summary icon-fl"></span>Ver Resumen</a></li>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientFile/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>Insertar Trámite</a></li>
  <li><a role="menuitem"class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/employment/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-employment icon-fl"></span>Insertar Trabajo</a></li>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientExpenses/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-expense icon-fl"></span>Insertar Gasto</a></li>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientIncomes/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-income icon-fl"></span>Insertar Ingreso</a></li>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientAssets/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-active icon-fl"></span>Insertar Activo</a></li>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientLiabilities/new')->params(array('dm_embed'=>1))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-liability icon-fl"></span>Insertar Liability</a></li>
</ul>
