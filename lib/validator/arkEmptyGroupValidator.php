<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of arkEmptyGroupValidator
 *
 * @author alberto
 */
class arkEmptyGroupValidator extends sfValidatorBase{
    public function doClean($value)
    {
        if ($value < 0)
        {
            $groupId = $value * -1;
            $group = Doctrine::getTable('DmGroup')->findById($groupId);
            if (count($group[0]->getUsers()) == 0){
                $this->addMessage('empty_group', 'Este grupo no tiene usuarios');
                throw new sfValidatorError($this, 'empty_group');
            }
        }
        return $value;
    }
}
?>
