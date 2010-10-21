<?php
class arkMonthlyMoneyCalculator extends sfWidgetForm{
    // this method depend of a db field named address_id
     public function configure($options = array(), $attributes = array()){
         parent::configure($options, $attributes);
         $this->addOption('MonthlyHours', 30*24);
         $this->addOption('MonthlyDays', 30);
         $this->addOption('MonthlyWeeks', 4);
         $this->addRequiredOption('type');
        // to maintain BC with symfony 1.2
        $this->setOption('type', 'text');
     }
     public function render($name, $value = null, $attributes = array(), $errors = array()){
         return '<label style="color: green;">$</label>'
.sprintf(<<<EOF
<input type="text" id="value"  style="width: %s"> cada
<select id="recurrence" style=" width: %s">
<option value="HORA">HORA</option>
<option value="SEMANA">SEMANA</option>
<option value="MES">MES</option>
<option value="ANNO">A&Ntilde;O</option>
</select>
EOF
,'15%','25%')
.'<label style="color: green;"> = $</label>'
.$this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), array('style'=>'color: red; width: 15%')))
.sprintf(<<<EOF
<script type="text/javascript">
  function makeCalculation(){
      $('input[name="%s"]').val($('#recurrence').val());
  }
  $(document).ready(function(){
    $('#value').blur(makeCalculation);
    $('#recurrence').change(makeCalculation);
  });
</script>
EOF
,$name);
     }
}