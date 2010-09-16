<?php

/**
 * DmMailTemplate filter form base class.
 *
 * @package    retest
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseDmMailTemplateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'            => new sfWidgetFormDmFilterInput(),
      'vars'            => new sfWidgetFormDmFilterInput(),
      'created_at'      => new sfWidgetFormChoice(array('choices' => array(
        ''      => '',
        'today' => $this->getI18n()->__('Today'),
        'week'  => $this->getI18n()->__('Past %number% days', array('%number%' => 7)),
        'month' => $this->getI18n()->__('This month'),
        'year'  => $this->getI18n()->__('This year')
      ))),
      'updated_at'      => new sfWidgetFormChoice(array('choices' => array(
        ''      => '',
        'today' => $this->getI18n()->__('Today'),
        'week'  => $this->getI18n()->__('Past %number% days', array('%number%' => 7)),
        'month' => $this->getI18n()->__('This month'),
        'year'  => $this->getI18n()->__('This year')
      ))),
    ));

    $this->setValidators(array(
      'name'            => new sfValidatorPass(array('required' => false)),
      'vars'            => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->widgetSchema['created_at']->getOption('choices')))),
      'updated_at'      => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->widgetSchema['updated_at']->getOption('choices')))),
    ));
    
    $this->mergeI18nForm();


    $this->widgetSchema->setNameFormat('dm_mail_template_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DmMailTemplate';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'name'            => 'Text',
      'vars'            => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'id'              => 'Number',
      'description'     => 'Text',
      'subject'         => 'Text',
      'body'            => 'Text',
      'from_email'      => 'Text',
      'to_email'        => 'Text',
      'cc_email'        => 'Text',
      'bcc_email'       => 'Text',
      'reply_to_email'  => 'Text',
      'sender_email'    => 'Text',
      'list_unsuscribe' => 'Text',
      'is_html'         => 'Boolean',
      'is_active'       => 'Boolean',
      'lang'            => 'Text',
    );
  }
}
