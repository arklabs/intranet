<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of arkEventFormDateRangeValidator
 *
 * @author alberto
 */
class arkEventFormDateRangeValidator extends sfValidatorBase{
   public function doClean($value)
    {
        $startingDate = new sfDate($value['date_start']);
        if ($value['date_end'] == '') return $value;
        $endDate = new sfDate($value['date_end']);
        if ($startingDate->get() > $endDate->get()){
            $this->addMessage('invalid_date_range', 'La fecha inicial debe ser menor que la fecha final');
            throw new sfValidatorError($this, 'invalid_date_range');
        }
        return $value;
    }
}
?>
