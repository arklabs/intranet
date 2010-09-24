<?php use_stylesheet('ark-icons-1-16');?>
<ul role="listbox">
  <?php if ($sf_user->hasPermission('viewClientSummary_front') || ($sf_user->isSuperAdmin())):?>
  <li><a role="menuitem" class="color-box-trigger ark-clear-left" href="<?php echo _link('app:admin/+/incommingCall/new')->params(array('dm_embed'=>1, 'defaults[dm_user_id]'=>$agent->getId()))->getHref()?>"><span class="ark-icon-1-16 ark-icon-view-client-summary icon-fl"></span>Registrar LLamada</a></li>
 <?php endif; ?>
</ul>
