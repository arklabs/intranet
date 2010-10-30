<?php
class arkAddressAutoFillHelper extends sfWidgetFormInputHidden{
    // this method depend of a db field named address_id
     public function configure($options = array(), $attributes = array()){
         parent::configure($options, $attributes);
         $this->addRequiredOption('zip_code_auto_complete_id', '');
         $this->addRequiredOption('city_input_id', '');
         $this->addRequiredOption('pais_abr_input_id', '');
         $this->addRequiredOption('estate_abr_input_id', '');
         $this->addRequiredOption('estate_name_input_id', '');
     }

     public function getStylesheets(){
          return array('/theme/css/ajaxLoader.css'=>'loader');
     }

     public function render($name, $value = null, $attributes = array(), $errors = array()){
         $zip_code_auto_complete_id = $this->getOption('zip_code_auto_complete_id');
            echo sprintf(<<<EOF
<script type="text/javascript">
    $(document).ready(function(){
      zcid = '#%s';
      $(zcid).blur(function(){
        if ($(zcid).val() !=''){
        // starting with autocomplete data request
            $.getJSON('/admin.php/+/address/getZipCodeData?zipcode='+$(zcid).val(), function(response){
                $('#%s').val(response.place_name); // place_name = city
                $('#%s').val(response.country_code); // Country abr
                $('#%s').val(response.state_code); // Estate Abr
                $('#%s').val(response.state_name); // Estate Abr
              });
        }
      });
    });
</script>
EOF
, $zip_code_auto_complete_id, $this->getOption('city_input_id'), $this->getOption('pais_abr_input_id'), $this->getOption('estate_abr_input_id'), $this->getOption('estate_name_input_id'));
     }
}