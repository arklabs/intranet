<?php if ($user):?>
<div class="profile-links">
    <p>
    Bienvenido <a><em><?php  echo $user ?></em></a>, <br/>
    <?php if ($unreadEvents > 0):?>
    tienes <?php echo $unreadEvents; ?> nuevo(s) evento(s)<br/>
    <?php else: ?>        
    No tienes nuevos eventos.
    <?php endif ?>
    </p>
    <a target="parent" rel="tipsy-r" original-title="Suscriba la url de este enlace en el lector RSS de su celular para acceder a sus eventos en todo momento." href="<?php echo url_for(sprintf('@user_rss?username=%s&token=%s',$user->getUsername(), substr($user->getDmUser()->get('salt'), 0, 7)));?>"> Eventos en mi celular </a> | <?php echo link_to('Salir','@signout');?>
</div>
<?php endif ?>