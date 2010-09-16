<?php // Vars: $clientLiabilitiesPager

echo $clientLiabilitiesPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($clientLiabilitiesPager as $clientLiabilities)
{
  echo _open('li.element');

    echo $clientLiabilities;

  echo _close('li');
}

echo _close('ul');

echo $clientLiabilitiesPager->renderNavigationBottom();