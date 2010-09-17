<?php
// Contact : Form
// Vars : $form

if($sf_user->hasFlash('comment_form_valid'))
{
  echo _tag('p.form_valid', __('Thank you, your comment has been sent.'));
}

// open the form tag with a dm_comment_form css class
echo $form->open();

// write name label, field and error message
echo $form['author_name']->label()->field()->error();

// same with website
echo $form['author_website']->label()->field()->error();

// same with email, plus a help message
echo $form['author_email']->label()->field()->help()->error();

echo $form['body']->label('Your message')->field()->error();

// render captcha if enabled
if($form->isCaptchaEnabled())
{
  echo $form['captcha']->label('Captcha', 'for=false')->field()->error();
}

echo $form->renderHiddenFields();

// change the submit button text
echo _tag('div.submit_wrap', $form->submit('Send'));

// close the form tag
echo $form->close();  