<?php  use_stylesheet('dashboard');?>
<ul class="shortcut-buttons-set">
    <?php foreach (array_keys($dashDescription) as $itemKey):?>
        <?php $item = $dashDescription[$itemKey]; ?>
        <?php if ($sf_user->hasCredential(array_keys($item['credentials']))):?>
        <li>
            <a class="shortcut-button <?php foreach (array_keys($item['extra-classes']) as $extra) echo $extra.' ';?>" href="<?php echo _link($item['url'])->params((count($item['params']))?$item['params']:array())->getHref();?>">
                <span>
                    <!--<img src="<?php //echo $item['icon']?>" alt=""> -->
					<span class="sprite-holder <?php foreach (array_keys($item['holder-classes']) as $extra) echo $extra.' ';?>"> </span>
                    <?php echo $item['title'];?>
                </span>
            </a>
        </li>
        <?php endif; ?>
   <?php endforeach ?>
</ul>
<div class="clear"></div>
