<?php // Vars: $dmTagPager



//normalizando las cantidades para calcular el tamanno correcto
$max = 0;
foreach ($dmTags as $dmTag)
{
    if ($dmTag->total_num > $max)
            $max = $dmTag->total_num;
}

echo _open('ul.elements.tag-list'); // primera variante
foreach ($dmTags as $dmTag)
{
  echo _open('li');

    $tagText = $dmTag->name;

    if($dmTag->hasDmPage())
    {
      echo _open('a', array('href'=> _link($dmTag)->getHref(),'style'=>sprintf('font-size: %s%s', 110+(($dmTag->total_num  / $max)*100),'%' )));
        echo $tagText;
      echo _close('a');
    }
    else
    {
      echo _open('span', array('href'=> _link($dmTag)->getHref(),'style'=>sprintf('font-size: %s%s', 110+(($dmTag->total_num  / $max)*100),'%' )));
        echo $tagText;
      echo _close('span');
    }

  echo _close('li');
}

echo _close('ul');

/*echo _open('p.tags'); // segunda variante
foreach ($dmTags as $dmTag)
{
    $tagText = $dmTag->name;

    if($dmTag->hasDmPage())
    {
      echo _open('a', array('href'=> _link($dmTag)->getHref(),'style'=>sprintf('font-size: %s%s', 100+(($dmTag->total_num  / $max)*100),'%' )));
        echo $tagText;
      echo _close('a');
    }
    else
    {
      echo $tagText;
    }
}

echo _close('p');
*/ 