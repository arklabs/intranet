<?php
class arkMultipleWeekDaySelector extends sfWidgetFormChoice{
    public function configure($options = array(), $attributes = array()){
        parent::configure($options, $attributes);
        $today = new sfDate(time());
        $this->setOption('expanded', true);
        $this->setOption('multiple', true);

        $this->setOption('choices', array('1'=>'Mon', '2'=>'Tue','3'=>'Wed','4'=>'Thu', '5'=>'Fri','6'=>'Sat','0'=>'Sun'));
    }
    public function  render($name, $value = null, $attributes = array(), $errors = array()) {
        return sprintf(<<<EOF
<style type="text/css">
    ul.checkbox_list li { list-style: none; margin-left:0px!important;}
</style>
<input type="checkbox" id="check-all-days" name="check-all-days" style="margin-bottom: 5px;"/> <label for="check-all-days">Todos</label>

EOF
).
parent::render($name, $value, $attributes, $errors).
sprintf(<<<EOF
<script type="text/javascript">
   $(document).ready(function(){
      $("#check-all-days").click(function(){
         if ($("#check-all-days").attr("checked"))
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
