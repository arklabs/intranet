<?php
class arkForceDateClientRelation extends sfValidatorBase{
	
   public function doClean($value){
   	 $dateCategory = Doctrine::getTable('EventCategory')->createQuery('ec')->where('ec.id = ?', $value['category_id'])->fetchOne();
   	 if ($dateCategory->getName()!= 'Cita')
   	 	return $value;
   	 if ($value['client_id'] == ''){
   	    $this->addMessage('no_client', 'No ha asignado un cliente a la cita, si no se encuentra en la lista, agregue el cliente al sistema antes de crear la cita.');
      	throw new sfValidatorError($this, 'no_client');
   	 }
   	return $value;
   }
}
