<?php
class arkMultipleMonthSelector extends sfWidgetFormChoice{
    public function configure($options = array(), $attributes = array()){
        parent::configure($options, $attributes);
        $today = new sfDate(time());
        $this->addOption('start_month_number', $today->getMonth());
        $this->addOption('forward_months', 3);

        $this->setOption('expanded', true);
        $this->setOption('multiple', true);

        $context = sfContext::getInstance();
        $context->getHelper('date');
        $choices = array();
        $cursor = new sfDate(sprintf('%s-%s-%s', $today->getYear(), $this->getOption('start_month_number'), '1'));
        for($i=0;$i<$this->getOption('forward_months'); $i++){
            $choices[$cursor->get()]= date('M', $cursor->get()).'/'.$cursor->getYear();;
            $cursor->addMonth(1);
        }
        $this->setOption('choices', $choices);
    }
    public function  render($name, $value = null, $attributes = array(), $errors = array()) {
        return sprintf(<<<EOF
<style type="text/css">
    ul.checkbox_list {margin-right: 10px;}
    ul.checkbox_list li { list-style: none; margin-left:0px !important;}
</style>
<input type="checkbox" id="check-all-months" name="check-all-months" style="margin-bottom: 5px;"/> <label for="check-all-months">Todos</label>

EOF
).
parent::render($name, $value, $attributes, $errors).
sprintf(<<<EOF
<script type="text/javascript">
   $(document).ready(function(){
      $("#check-all-months").click(function(){
         if ($("#check-all-months").attr("checked"))
            $("input[name='%s']").attr('checked', 'checked');
          else
            $("input[name='%s']").removeAttr('checked');
      });
   });
</script>
EOF
,$name.'[]', $name.'[]');

    }
}
?>
