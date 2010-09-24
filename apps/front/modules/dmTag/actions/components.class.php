<?php

require_once realpath(dirname(__FILE__).'/../../../../../').'/plugins/dmTagPlugin/modules/dmTag/lib/BasedmTagComponents.class.php';

/**
 * Dm tag components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class dmTagComponents extends BasedmTagComponents
{
     public function executePopular()
      {
        
            $this->dmTags = dmDb::table('DmTag')-> getPopularTagsQuery(array(), 15);
            $this->dmTags->addSelect('e.dm_user_id');
            $this->dmTags->orderBy('name DESC');
            $this->dmTags = $this->dmTags->execute();
      }
}
