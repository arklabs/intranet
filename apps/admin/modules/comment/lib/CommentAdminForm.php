<?php

/**
 * comment admin form
 *
 * @package    intranet
 * @subpackage comment
 * @author     Your name here
 */
class CommentAdminForm extends BaseCommentForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('dm_user_id', new sfWidgetFormInputHidden(array(), array('value'=>sfContext::getInstance()->getUser()->getDmUser()->getId())));
    $this->useFields(array('client_file_id','dm_user_id', 'text', 'colorbox_close_enable'));
  }
}