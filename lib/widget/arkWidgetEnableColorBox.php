<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfWidgetLinkTextWithToolTipForDiemBackend
 *
 * @author alberto
 */
class arkWidgetEnableColorBox extends sfWidgetFormInputHidden{
        public function configure($options = array(), $attributes = array()){
            $this->addRequiredOption('embed_mode', false);
        }
        public function getStylesheets(){
            return array('/theme/css/ajaxLoader.css'=>'loader');
        }
	public function render($name, $value = null, $attributes = array(), $errors = array()){
                echo '<div id="loader" style="display: none;"> </div>'.
		sprintf(
                <<<EOF
<script type="text/javascript">
%s
$(document).ready(function(){
		%s
		$("input[value=\'Cerrar\']").click(function(){ parent.$.fn.colorbox.close(); });
        %s
});
</script>
EOF
			,($this->getOption('embed_mode'))?'$("#loader").css("left", "50%");$("#loader").show();':''
			,($this->getOption('embed_mode'))?'$("a").each(function(){if ($(this).attr("href").search("[?]") > 0) $(this).attr("href",$(this).attr("href")+"&dm_embed=1" ); else $(this).attr("href",$(this).attr("href")+"?dm_embed=1" );});':''
            ,($this->getOption('embed_mode'))?'$("#loader").hide();':''
            );
	}
}