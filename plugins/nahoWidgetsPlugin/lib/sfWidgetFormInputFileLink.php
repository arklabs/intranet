<?php

class sfWidgetFormInputFileLink extends sfWidgetFormInputFileEditable
{

  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->setOption('file_src', null);
    
    $this->addOption('file_path', sfConfig::get('sf_upload_dir'));
    $this->addOption('web_path', '/uploads');
  }
  
  protected function getFileAsTag($attributes)
  {
    if (!$this->getOption('is_image') && is_file(sfConfig::get('sf_web_dir').$this->getOption('file_src'))) {
      return link_to(basename($this->getOption('file_src')), public_path($this->getOption('file_src')));
    } else {
      return parent::getFileAsTag($attributes);
    }
  }
  
}