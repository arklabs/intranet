<?php // Vars: $clientAssetPager

echo $clientAssetPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($clientAssetPager as $clientAsset)
{
  echo _open('li.element');

    echo $clientAsset;

  echo _close('li');
}

echo _close('ul');

echo $clientAssetPager->renderNavigationBottom();