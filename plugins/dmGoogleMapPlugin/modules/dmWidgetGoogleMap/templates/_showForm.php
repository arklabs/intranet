<?php

echo

$form->renderGlobalErrors(),

_open('div.dm_tabbed_form'),

_tag('ul.tabs',
  _tag('li', _link('#'.$baseTabId.'_map')->text(__('Map'))).
  _tag('li', _link('#'.$baseTabId.'_controls')->text(__('Controls'))).
  _tag('li', _link('#'.$baseTabId.'_presentation')->text(__('Presentation')))
),

_tag('div#'.$baseTabId.'_map.drop_zone',
  _tag('ul.dm_form_elements',
    $form['address']->renderRow().
    $form['mapTypeId']->renderRow().
    $form['zoom']->renderRow()
  )
),

_tag('div#'.$baseTabId.'_controls.drop_zone',
  _tag('ul.dm_form_elements',
    $form['navigationControl']->renderRow().
    $form['mapTypeControl']->renderRow().
    $form['scaleControl']->renderRow()
  )
),

_tag('div#'.$baseTabId.'_presentation',
  _tag('ul.dm_form_elements',
    _tag('li.dm_form_element.multi_inputs.clearfix',
      $form['width']->renderError().
      $form['height']->renderError().
      _tag('label', __('Dimensions')).
      $form['width']->render().
      'x'.
      $form['height']->render()
    ).
    $form['splash']->renderRow().
    $form['cssClass']->renderRow()
  )
),

_close('div'); //div.dm_tabbed_form