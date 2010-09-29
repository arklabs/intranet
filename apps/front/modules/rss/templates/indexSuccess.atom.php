<?php echo use_helper('Date');?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>Intranet | Mis eventos. </title>
    <subtitle><?php echo $ownerUser; ?> (<?php echo sprintf('%s - %s', format_date($date_start,'D'), format_date($date_end,'D'));?>)</subtitle>
    <link href="<?php echo url_for('@homepage', true) ?>"/>
    <updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', time()); ?></updated>
    <author>
          <name>Intranet | Mis Eventos</name>
    </author>
    <id><?php echo sha1(time()) ?></id>
    <?php use_helper('Text');?>
    <?php foreach ($entries as $e): ?>
    <entry>
        <title><?php echo $e->get('title')?></title>
        <link href="<?php echo url_for('@homepage');?>"/>
        <id><?php echo sha1($e->getId());?></id>
        <summary type="xhtml">
            <div xmlns="http://www.w3.org/1999/xhtml">
                  <label>Inicio: <em><?php echo format_date($e->getDateStart(),'D');?></em> </label><br/>
                  <label>Fin:<em> <?php echo format_date($e->getDateEnd(), 'D');?></em></label><br/>
                  <label>Categoria:  <em><?php echo $e->getEventCategory();?></em></label><br/>
                  <label>Estado: <em><?php echo $e->getEventStatus();?></em></label><br/>
                  <label>Asignado por:<em> <?php echo $e->getCreatedBy();?></em></label><br/>
                  <p>
                    <?php echo html_entity_decode(htmlspecialchars_decode($e->get('description'))); ?>
                  </p>
             </div>
        </summary>
        <author>
            <name>
                Intranet | Mis eventos
            </name>
        </author>
    </entry>
    <?php endforeach ?>
</feed>



