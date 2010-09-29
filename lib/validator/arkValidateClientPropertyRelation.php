<?php
class arkValidateClientPropertyRelation extends sfValidatorBase{
   public function doClean($value)
    {
        if ($value['client_id'] == '') return $value;
        $this->addMessage('invalid_client_property', 'La propiedad seleccionada no pertenece al cliente seleccionado');
        $property = Doctrine::getTable('Property')->findById($value['property_id']);
        if (!count($property))
             throw new sfValidatorError($this, 'invalid_client_property');
        if ($property[0]->getClientId()!=$value['client_id'])
             throw new sfValidatorError($this, 'invalid_client_property');
        return $value;
    }
}