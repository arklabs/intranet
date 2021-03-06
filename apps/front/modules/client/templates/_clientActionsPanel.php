<?php use_stylesheet('ark-icons-1-16');?>
<ul role="listbox">
 <?php if ($sf_user->hasPermission('viewClientSummary_front') || ($sf_user->isSuperAdmin())):?>
  <li><a role="menuitem" class="ark-clear-left" href="<?php echo _link($client)->getHref();?>"><span class="ark-icon-1-16 ark-icon-view-client-summary icon-fl"></span>Ver Resumen</a></li>
 <?php endif; if ($sf_user->hasPermission('insertClientFile_front') || ($sf_user->isSuperAdmin())):?> 
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientFile/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-client-file icon-fl"></span>Insertar Trámite</a></li>
  <?php endif; if ($sf_user->hasPermission('insertClientJob_front') || ($sf_user->isSuperAdmin())):?>
  <li><a role="menuitem"class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/employment/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-employment icon-fl"></span>Insertar Trabajo</a></li>
  <?php endif; if ($sf_user->hasPermission('insertExpense_front') || ($sf_user->isSuperAdmin())):?>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientExpense/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-expense icon-fl"></span>Insertar Gasto</a></li>
  <?php endif; if ($sf_user->hasPermission('insertIncome_front') || ($sf_user->isSuperAdmin())):?>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientIncome/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-income icon-fl"></span>Insertar Ingreso</a></li>
  <?php endif; if ($sf_user->hasPermission('insertActive_front') || ($sf_user->isSuperAdmin())):?>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/clientAsset/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-active icon-fl"></span>Insertar Activo</a></li>
  <?php endif;?> <?php if ($sf_user->hasPermission('insertEvent_front') || ($sf_user->isSuperAdmin())):?>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/event/new')->params(array('dm_embed'=>1, 'defaults[client_id]'=>$client->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-insert-date icon-fl"></span>Insertar Cita</a></li>
  <?php endif;?>
</ul>
