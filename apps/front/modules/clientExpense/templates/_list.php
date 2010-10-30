<?php // Vars: $clientExpensePager

echo $clientExpensePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($clientExpensePager as $clientExpense)
{
  echo _open('li.element');

    echo $clientExpense;

  echo _close('li');
}

echo _close('ul');

echo $clientExpensePager->renderNavigationBottom();