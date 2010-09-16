<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of arkPCache
 *
 * @author marco
 */


require_once(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/vendor/pChart/pChart/pCache.class.php'));


class arkPCache extends pCache {
    
    /* Create the arkPCache object */
    function arkPCache($CacheFolder="Cache/")
    {
     $this->CacheFolder = $CacheFolder;
    }

    /* This function is building the graph unique hash key */
   function GetHash($ID,$Data)
    {
     $mKey = "$ID";
     foreach($Data as $key => $Values)
      {
       $tKey = "";
       foreach($Values as $Serie => $Value)
        $tKey = $tKey.$Serie.$Value;
       $mKey = $mKey.md5($tKey);
      }
     return($ID."_".md5($mKey).".png");
    }

}
?>
