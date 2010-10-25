<?php // Vars: $clientIncomePager

echo $clientIncomePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($clientIncomePager as $clientIncome)
{
  echo _open('li.element');

    echo $clientIncome;

  echo _close('li');
}

echo _close('ul');

echo $clientIncomePager->renderNavigationBottom();