<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfWidgetLinkTextWithToolTipForDiemBackend
 *
 * @author alberto
 */
class sfWidgetLinkTextWithToolTipForDiemBackend extends sfWidgetForm
{
    /* supported options
     * url, pass null for no url rendering
     * title, text for tooltip
     * value to display
     */
    public function configure($options = array(), $attributes = array())
    {
          $this->addOption('url', '');
          $this->addOption('title', '');
          $this->addOption('value', '');
    }


    public function render($name, $value = null, $attributes = array(), $errors = array()){
    		$attrList = '';
    	    foreach (array_keys($attributes) as $attr)
    	    	$attrList.= sprintf('%s="%s"', $attr, $attributes[$attr]);
            return sprintf('<a "%s" %s %s> %s</a>',
                            (($this->getOption('url')!='')?'href="'.$this->getOption('url').'"':''),
                            (($this->getOption('title')!='')?'class="s16" style="background-position: 1000px 1000px; margin-left: -16px;" title="'.$this->getOption('title').'"':''),
                            $attrList,
                            $this->getOption('value')
                          );
    }
}
