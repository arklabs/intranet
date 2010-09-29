<div id="sidebar" class="side-content">
    <div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->

        <h1 id="sidebar-title"><a href="#">Simpla Admin</a></h1>

        <!-- Logo (221px wide) -->
        <a href="#"><img alt="Simpla Admin logo" src="/theme/images/logo.png" id="logo"></a>

        <!-- Sidebar Profile links -->
        <div id="profile-links">
                Hello, <a title="Edit your profile" href="#">guiii</a>, you have <a title="3 Messages" rel="modal" href="#messages">3 Messages</a><br>
                <br>
                <a title="View the Site" href="#">View the Site</a> | <a title="Sign Out" href="#">Sign Out</a>
        </div>

        <ul id="main-nav">  <!-- Accordion Menu -->
                <?php foreach ($sideDescription as $firstLevelItem): ?>
                <li>
                    <a
                        class="nav-top-item
                              <?php echo (!is_array($firstLevelItem['child_items']))?' no-submenu':'';
                                    echo (array_key_exists('default',$firstLevelItem) && $firstLevelItem['default'] == 1)?' current':'';?>"
                        href="<?php echo ($firstLevelItem['action']!='' && $firstLevelItem['module']!='')?url_for1(sprintf('%s/%s?%s', $firstLevelItem['module'], $firstLevelItem['action'], $firstLevelItem['params'])):'#';?>"
                        style="padding-right: 15px;"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
                          <?php echo $firstLevelItem['title'];?>
                    </a>
                    <?php if (is_array($firstLevelItem['child_items'])):?>
                    <ul style="display: <?php echo (array_key_exists('default',$firstLevelItem) && $firstLevelItem['default'] == 1)?'none':'block';?>;">
                          <?php foreach ($firstLevelItem['child_items'] as $secondLevelItem):?>
                            <li><a <?php echo ($secondLevelItem['ajax_callable']==1)?'class="nav-trackable ajax-callable"':''; ?> href="<?php echo ($secondLevelItem['action']!='' && $secondLevelItem['module']!='')?url_for1(sprintf('%s/%s?%s', $secondLevelItem['module'], $secondLevelItem['action'], $secondLevelItem['params'])):'#';?>"><?php echo $secondLevelItem['title'];?></a></li> <!-- Add class "current" to sub menu items also -->
                          <?php endforeach ?>
                    </ul>
                    <?php endif ?>
                </li>
                <?php endforeach ?>
        </ul> <!-- End #main-nav -->
    </div>
</div>
 