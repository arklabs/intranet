<?php
require_once sfConfig::get('dm_core_dir').'/plugins/dmUserPlugin/modules/dmUser/lib/BasedmUserActions.class.php';

class dmUserActions extends BasedmUserActions
{
      protected function redirectSignedInUser(dmWebRequest $request)
	  {
	    $redirectUrl = '@homepage';

	    $this->redirect('' != $redirectUrl ? $redirectUrl : '@homepage');
	  }
}