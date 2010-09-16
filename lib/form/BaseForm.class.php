<?php

/**
 * Base project form.
 */
class BaseForm extends dmForm
{
	public function setup(){
	    parent::setup();
            $request = dmContext::getInstance()->getRequest();
            $embed_mode = ($request->hasParameter('dm_embed') && $request->getParameter('dm_embed') == 1);
            $this->setWidget('colorbox_close_enable', new arkWidgetEnableColorBox(array('embed_mode'=>$embed_mode), array()));
            $this->getWidget('colorbox_close_enable')->setLabel(" ");
	}
}
?>