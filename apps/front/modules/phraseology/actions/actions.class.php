<?php
/**
 * Fraseolog&iacute;a actions
 */
class phraseologyActions extends myFrontModuleActions
{

	public function executeVote(){
		$value = $this->getRequestParameter('value');
		$phId = $this->getRequestParameter('ph-id');
		if ($phId!='' && !is_null($phId)){
			$ph = Doctrine::getTable('Phraseology')->findById($phId);
			if ($value > 0)
				$ph[0]->setPosRank($ph[0]->getPosRank() + $value);
			else 
				$ph[0]->setNegRank($ph[0]->getNegRank() + -1*$value);
			$ph[0]->save();
			return true;
		}
		else 
			return false;
	}
}
