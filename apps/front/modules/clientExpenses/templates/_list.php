<?php // Vars: $clientExpensesPager

echo $clientExpensesPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($clientExpensesPager as $clientExpenses)
{
  echo _open('li.element');

    echo $clientExpenses;

  echo _close('li');
}

echo _close('ul');

echo $clientExpensesPager->renderNavigationBottom();