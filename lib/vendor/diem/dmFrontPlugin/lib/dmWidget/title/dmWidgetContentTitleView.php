<?php

class dmWidgetContentTitleView extends dmWidgetPluginView
{

  public function configure()
  {
    parent::configure();

    $this->addRequiredVar(array('text', 'tag'));
  }
  
  protected function filterViewVars(array $vars = array())
  {
    $vars = parent::filterViewVars($vars);
    
    $vars['text'] = nl2br($vars['text']);
    
    return $vars;
  }

  protected function doRender()
  {
    $vars = $this->getViewVars();

    if(empty($vars['href']))
    {
      $content = $vars['text'];
    }
    else
    {
      $content = $this->getHelper()->link($vars['href'])->text($vars['text']);
    }

    return $this->getHelper()->tag($vars['tag'], array('class' => $vars['cssClass']), $content);
  }
  
  protected function doRenderForIndex()
  {
    return $this->compiledVars['text'];
  }
}