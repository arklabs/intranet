<?php 
// requires to include jquery lib on  page header 
class arkAlternativeEventChoiceWidget extends sfWidgetFormDoctrineChoice {
  
  public function configure($options = array(), $attributes = array())
  {
	$this->addRequiredOption('WatchedWidgetHtmlId');
	$this->addRequiredOption('WatchLabel');
	parent::configure($options, $attributes);
  }
  
  public function getJavaScripts(){
  	return array('/js/category-statuses-corrector.js');
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array()){
	return parent::render($name, $value, $attributes, $errors).sprintf(<<<EOF
<script type="text/javascript">
	$(document).ready(function(){
		if ($(".sf_admin_form_field_category_id").find("select").find('> option:selected').html() != '%s')
			$(".%s").hide();
	});
	$(".sf_admin_form_field_category_id").find("select").change(function(){
		if ($(this).find('> option:selected').html() =='%s')
			$(".%s").show();
		else{
			$(".%s").hide();
			$(".%s").find('select > option:selected').attr('selected', "");
			$(".%s").find("select > option[value='']").attr('selected', "selected");
		}
	});
</script>
EOF
,
$this->getOption('WatchLabel'),
$this->getOption('WatchedWidgetHtmlId'),
$this->getOption('WatchLabel'),
$this->getOption('WatchedWidgetHtmlId'),
$this->getOption('WatchedWidgetHtmlId'),
$this->getOption('WatchedWidgetHtmlId'),
$this->getOption('WatchedWidgetHtmlId')
);
  }
  
}