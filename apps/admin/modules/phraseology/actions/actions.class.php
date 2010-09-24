<?php

require_once dirname(__FILE__).'/../lib/phraseologyGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/phraseologyGeneratorHelper.class.php';

/**
 * phraseology actions.
 *
 * @package    intranet
 * @subpackage phraseology
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class phraseologyActions extends autoPhraseologyActions
{
	public function executeGetPhraseologyJsonList(sfWebRequest $request){
		try{
     	$q = $request->getParameter("q", "");
        $query = Doctrine::getTable('phraseology')->like($q);
        
            $this->items = $query->execute();
            $request->setParameter('sf_format','json');
            $result = array();
            foreach ($this->items as $item){
                $result[$item->get('id')] = $item->__toString();
        	}
          	return $this->renderText(json_encode($result));
         }
         catch(Exception $e){
                    echo $e->getMessage(); die;
         }
     }
}
