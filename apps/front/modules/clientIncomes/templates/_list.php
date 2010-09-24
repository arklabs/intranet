<?php // Vars: $clientIncomesPager

echo $clientIncomesPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($clientIncomesPager as $clientIncomes)
{
  echo _open('li.element');

    echo $clientIncomes;

  echo _close('li');
}

echo _close('ul');

echo $clientIncomesPager->renderNavigationBottom();