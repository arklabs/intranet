<?php

/**
 * Diem form base class.
 *
 * @package    diem
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormBaseTemplate.php 9304 2008-05-27 03:49:32Z dwhittle $
 */
abstract class dmFormDoctrine extends sfFormDoctrine
{
  /**
   * Unset automatic fields like 'created_at', 'updated_at', 'position'
   */
  protected function unsetAutoFields($autoFields = null)
  {
    $autoFields = null === $autoFields ? $this->getAutoFieldsToUnset() : (array) $autoFields;
    
    foreach($autoFields as $autoFieldName)
    {
      if (isset($this->widgetSchema[$autoFieldName]))
      {
        unset($this[$autoFieldName]);
      }
    }
  }
  
  protected function getAutoFieldsToUnset()
  {
    $fields = array('created_at', 'updated_at');

    if ($this->getObject()->getTable()->isSortable())
    {
      $fields[] = 'position';
    }

    if ($this->getObject()->getTable()->isVersionable())
    {
      $fields[] = 'version';
    }

    return $fields;
  }

  protected function filterValuesByEmbeddedMediaForm(array $values, $local)
  {
    $formName = $local.'_form';
     
    if (!isset($this->embeddedForms[$formName]))
    {
      return $values;
    }
    
    $isFileProvided = isset($values[$formName]['file']) && !empty($values[$formName]['file']['size']);

    // media id provided with drag&drop
    if(!empty($values[$formName]['id']) && !$isFileProvided)
    {
      if($this->embeddedForms[$formName]->getObject()->isNew() || $this->embeddedForms[$formName]->getObject()->id != $values[$formName]['id'])
      {
        if($media = dmDb::table('DmMedia')->findOneByIdWithFolder($values[$formName]['id']))
        {
          $this->embeddedForms[$formName]->setObject($media);
          $values[$formName]['dm_media_folder_id'] = $media->dm_media_folder_id;
        }
      }
    }
    // no existing media, no file, and it is not required : skip all
    elseif ($this->embeddedForms[$formName]->getObject()->isNew() && !$isFileProvided && !$this->embeddedForms[$formName]->getValidator('file')->getOption('required'))
    {
      // remove the embedded media form if the file field was not provided
      unset($this->embeddedForms[$formName], $values[$formName]);
      // pass the media form validations
      $this->validatorSchema[$formName] = new sfValidatorPass;
    }

    return $values;
  }

  protected function processValuesForEmbeddedMediaForm(array $values, $local)
  {
    $formName = $local.'_form';

    if (!isset($this->embeddedForms[$formName]))
    {
      return $values;
    }

    /*
     * We have a new file for an existing media.
     * Let's create a new media
     */
    if($values[$formName]['file'] && $values[$formName]['id'])
    {
      $values[$formName]['id'] = null;
        
      $media = new DmMedia;
      $media->Folder = $this->object->getDmMediaFolder();

      $this->embeddedForms[$formName]->setObject($media);
    }
    
    return $values;
  }

  protected function doUpdateObjectForEmbeddedMediaForm(array $values, $local, $alias)
  {
    $formName = $local.'_form';

    if (!isset($this->embeddedForms[$formName]))
    {
      return;
    }

    if (!empty($values[$formName]['remove']))
    {
      $this->object->set($alias, null);
    }
    else
    {
      $this->object->set($alias, $this->embeddedForms[$formName]->getObject());
    }
  }

  protected function mergeI18nForm($culture = null)
  {
    $this->mergeForm($this->createI18nForm());
  }
  
  /**
   * Create current i18n form
   */
  protected function createI18nForm($culture = null)
  {
    if (!$this->isI18n())
    {
      throw new dmException(sprintf('The model "%s" is not internationalized.', $this->getModelName()));
    }

    $i18nFormClass = $this->getI18nFormClass();

    $culture = null === $culture ? dmDoctrineRecord::getDefaultCulture() : $culture;
    
    // translation already set, use it
    if ($this->object->get('Translation')->contains($culture))
    {
      $translation = $this->object->get('Translation')->get($culture);
    }
    else
    {
      $translation = $this->object->get('Translation')->get($culture);
      
      // populate new translation with fallback values
      if (!$translation->exists())
      {
        if($fallback = $this->object->getI18nFallBack())
        {
          $fallBackData = $fallback->toArray();
          unset($fallBackData['id'], $fallBackData['lang']);
          $translation->fromArray($fallBackData);
        }
      }
    }

    $i18nForm = new $i18nFormClass($translation);
    
    unset($i18nForm['id'], $i18nForm['lang']);

    return $i18nForm;
  }

  /**
   * Sets the current object for this form.
   *
   * @return dmDoctrineRecord The current object setted.
   */
  public function setObject(dmDoctrineRecord $record)
  {
    return $this->object = $record;
  }
}