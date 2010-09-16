<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom.css','', array('media'=>'print'));
//use_stylesheet('widgetAndZones','', array('media'=>'screen'));
//use_stylesheet('widgetAndZones','', array('media'=>'print'));
use_stylesheet('report-print', '',array('media'=>'print'));
echo _open('div.min-border-space');
echo get_partial('main/ajaxLoader');

echo dm_get_widget('main', 'reportDashBoard', array(
  'css_class' => ''
));
echo _tag('div style="clear:both; width: 100%; height: 20px;"');

echo dm_get_widget('main', 'dateRangeSelector', array(
  'css_class' => '',
));
echo _tag('div style="clear:both; width: 100%; height: 20px;"');
echo _tag('h3#report-title style="display:none"','');
echo _tag('div style="clear:both; width: 100%; height: 5px;"');
echo get_partial('main/reportGraphViewer');
echo _tag('div style="clear:both; width: 100%; height: 20px;"');
echo get_partial('main/reportListViewer');
echo _tag('div style="clear:both; width: 100%; height: 40px;"');
echo _close('div');
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#report-title').html(getReportTitle());
    });
</script>
