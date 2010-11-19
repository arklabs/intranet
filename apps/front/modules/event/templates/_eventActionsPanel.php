<?php use_stylesheet('ark-icons-1-16');?>
<!-- Params $event -->
<ul role="listbox">
  <?php if ($sf_user->hasPermission('viewDateSummary_front') || ($sf_user->isSuperAdmin())):?>
  <li><a role="menuitem" class="ark-clear-left" href="<?php echo _link($event)->getHref();?>"><span class="ark-icon-1-16 ark-icon-view-date-summary icon-fl"></span>Resumen de cita</a></li>
 <?php endif; ?>
 
</ul>
