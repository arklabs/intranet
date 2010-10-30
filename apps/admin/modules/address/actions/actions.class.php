<?php

require_once dirname(__FILE__).'/../lib/addressGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/addressGeneratorHelper.class.php';

/**
 * address actions.
 *
 * @package    intranet
 * @subpackage address
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class addressActions extends autoAddressActions
{
    public function executeGetZipCodeJsonList(sfWebRequest $request){
     	$q = $request->getParameter("q", "");
        $query = null;
        Doctrine::getTable( 'ZipCode' )->like($q, $query);//->limit(20, $query);
        try{
            
            $this->items = $query->execute();
            $request->setParameter('sf_format','json');
            $result = array();
            foreach ($this->items as $item){
                $result[$item->getZipCode()] = $item->getZipCode();
        }
          return $this->renderText(json_encode($result));
         }
         catch(Exception $e){
                    echo $e->getMessage(); die;
         }
     }
     public function executeGetZipCodeData(sfWebRequest $request){
         $zip = $request->getParameter('zipcode');
         if (!$zip) return false;

         $data = Doctrine::getTable('ZipCode')->findByZipCode($zip);
         if (!count($data)) return false;

         $request->setParameter('sf_format','json');
         return $this->renderText(json_encode($data[0]->toarray()));
     }
}
