<?php
class arkMonthlyMoneyCalculator extends sfWidgetForm{
    // this method depend of a db field named address_id
     public function configure($options = array(), $attributes = array()){
         parent::configure($options, $attributes);
         $this->addRequiredOption('choices', array('MES'=>'1'));
         $this->addRequiredOption('default-key-choice', 'MES');
         //$this->addOption('MonthlyHours', 30*24);
         //$this->addOption('MonthlyDays', 30);
         //$this->addOption('MonthlyWeeks', 4);
         $this->addRequiredOption('type');
        // to maintain BC with symfony 1.2
        $this->setOption('type', 'text');
     }
     public function render($name, $value = null, $attributes = array(), $errors = array()){
$calculations = 'result = 0;';
$choices = $this->getOption('choices');
$options = sprintf('<select id="recurrence" style=" width: %s">','15%');
foreach (array_keys($choices) as $choice){
    $calculations.= sprintf(" if ($('#recurrence').val() == '%s') result = Math.round($('#value').val() * %s);", $choice, $choices[$choice]);
    $options.= sprintf('<option value="%s">%s</option>', $choice, $choice);
}
$options.='</select>';
return '<label style="color: green;">$</label>'
.sprintf('<input type="text" id="value"  style="width: %s" value="%s"> cada', '15%',($value)?$value:'')
.$options
.'<label style="color: green;"> = $</label>'
.$this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), array('style'=>'color: red; width: 15%')))
.'<label style=""> al mes</label>'
.sprintf(<<<EOF
<script type="text/javascript">
  function makeCalculation(){
      %s
      $('input[name="%s"]').val(result);
  }
  $(document).ready(function(){
    $('#value').blur(makeCalculation);
    $('#recurrence').change(makeCalculation);
    $('#value').val($('input[name="%s"]').val());
    $('#recurrence > option[value="%s"]').attr('selected', 'selected');
  });
</script>
EOF
,$calculations,$name, $name, $this->getOption('default-key-choice'));
     }
}