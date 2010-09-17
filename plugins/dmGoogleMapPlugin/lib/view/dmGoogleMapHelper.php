<?php

class dmGoogleMapHelper extends dmConfigurable
{
  protected
  $context;
  
  public function __construct(dmContext $context, array $options = array())
  {
    $this->context = $context;
    
    $this->initialize($options);
  }

  public function initialize(array $options)
  {
    $this->configure($options);
  }

  /*
   * Get a dmGoogleMapTag instance
   */
  public function map()
  {
    $map = $this->context->get('google_map_tag');

    $response = $this->context->getResponse();

    foreach($map->getStylesheets() as $stylesheet)
    {
      $response->addStylesheet($stylesheet);
    }

    foreach($map->getJavascripts() as $javascript)
    {
      $response->addJavascript($javascript);
    }

    /*
     * Async loading won't work
     * as the api itself uses async loading
     */
    if(!$this->context->getRequest()->isXmlHttpRequest())
    {
      $response->addJavascript('dmGoogleMapPlugin.api');
    }

    return $map;
  }

  public function £map()
  {
    return $this->map();
  }
}