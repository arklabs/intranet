<?php
use_stylesheet('/theme/css/login.css');
//use_stylesheet('/theme/css/frontAuth.css');

if($sf_user->isAuthenticated())
{
  echo _tag('p', __('You are authenticated as %username%', array('%username%' => $sf_user->getUsername())));
  return;
}

echo $form->open('.dm_signin_form.clearfix action=@signin');

echo _tag('ul.dm_form_elements',

  _tag('li.dm_form_element.clearfix', $form['username']->label()->field()->error()).

  _tag('li.dm_form_element.clearfix', $form['password']->label()->field()->error()).

  _tag('li.dm_form_element.clearfix', $form['remember']->label()->field()->error())

);

echo $form->renderHiddenFields();

echo $form->submit(__('Signin'));

echo $form->close();
