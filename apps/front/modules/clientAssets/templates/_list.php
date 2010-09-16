<?php // Vars: $clientAssetsPager

echo $clientAssetsPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($clientAssetsPager as $clientAssets)
{
  echo _open('li.element');

    echo $clientAssets;

  echo _close('li');
}

echo _close('ul');

echo $clientAssetsPager->renderNavigationBottom();