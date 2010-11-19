<?php
class arkSimpleiButton extends sfWidgetFormInputCheckbox{

    public function  configure($options = array(), $attributes = array()) {
        parent::configure($options, $attributes);
        $this->addOption('labelOn', 'YES');
        $this->addOption('labelOff', 'NO');
        $this->addOption('js-after-ibutton-init', '');
    }

    public function   getJavaScripts() {
        return array('/js/jquery.ibutton.min.js');
    }

    public function  getStylesheets() {
        return array('/theme/css/ibutton.min.css');
    }
    
    public function  render($name, $value = null, $attributes = array(), $errors = array()) {
        return parent::render($name, $value, $attributes, $errors).
         sprintf(<<<EOF
<script type="text/javascript">
    $(document).ready(function(){
       $("input[name='%s']").iButton({ labelOn: "%s", labelOff: "%s"});
       %s
    });
</script>
EOF
,$name, $this->getOption('labelOn'), $this->getOption('labelOff'), $this->getOption('js-after-ibutton-init'));
    }
}