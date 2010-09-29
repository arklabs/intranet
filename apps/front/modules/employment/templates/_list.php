<?php // Vars: $employmentPager

echo $employmentPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($employmentPager as $employment)
{
  echo _open('li.element');

    echo $employment;

  echo _close('li');
}

echo _close('ul');

echo $employmentPager->renderNavigationBottom();