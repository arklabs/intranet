<?php // Vars: $propertyPager

echo $propertyPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($propertyPager as $property)
{
  echo _open('li.element');

    echo $property;

  echo _close('li');
}

echo _close('ul');

echo $propertyPager->renderNavigationBottom();