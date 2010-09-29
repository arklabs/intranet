<?php // Vars: $assessorPager

echo $assessorPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($assessorPager as $assessor)
{
  echo _open('li.element');

    echo $assessor;

  echo _close('li');
}

echo _close('ul');

echo $assessorPager->renderNavigationBottom();