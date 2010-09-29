<?php if (isset($sf_user) && !is_null($message = $sf_user->getFlash('error')) && $message !=''):?>
<div class="notification error png_bg">
        <a class="close" href="#"><img alt="close" title="Cerrar esta notificaci&oacute;n" src="/theme/images/icons/cross_grey_small.png"></a>
        <div>
             <?php echo $message;?>
        </div>
</div>
<?php endif ?>

<?php if (isset($sf_user) && !is_null($message = $sf_user->getFlash('notice')) && $message !=''):?>
<div class="notification information png_bg">
        <a class="close" href="#"><img alt="close" title="Cerrar esta notificaci&oacute;n" src="/theme/images/icons/cross_grey_small.png"></a>
        <div>
            <?php echo $message;?>
        </div>
</div>
<?php endif ?>

<?php if (isset($sf_user) && !is_null($message = $sf_user->getFlash('attention')) && $message !=''):?>
<div class="notification attention png_bg">
        <a class="close" href="#"><img alt="close" title="Cerrar esta notificaci&oacute;n" src="/theme/images/icons/cross_grey_small.png"></a>
        <div>
            <?php echo $message;?>
        </div>
</div>
<?php endif ?>

<?php if (isset($sf_user) && !is_null($message = $sf_user->getFlash('success')) && $message !=''):?>
<div class="notification success png_bg">
        <a class="close" href="#"><img alt="close" title="Cerrar esta notificaci&oacute;n" src="/theme/images/icons/cross_grey_small.png"></a>
        <div>
            <?php echo $message;?>
        </div>
</div>
<?php endif ?>