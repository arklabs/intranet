<?php
use_stylesheet('another-ui/jquery-ui-1.8.2.custom');
use_stylesheet('dataTable');
use_stylesheet('fg-menu');
use_stylesheet('fb-buttons-menu');
use_javascript('lib.dataTable');
use_javascript('fg-menu');
use_javascript('fg-menu-handling'); 

echo _open('div#report-list-container');
echo _close('div');
?>
<script type="text/javascript">
    function loadReportList(){
        $('#report-list-container').hide();
        $('#report-title').hide();
        $('#loader').show();
        $.ajax({
           type: "GET",
           url: '/index.php/+/main/renderComponent',
           data: {mod: getReportModuleName(), name: getReportComponentName(), dateStart: getRangeDateStart(), dateEnd: getRangeDateEnd()},
           success: function(response){
             $('#report-list-container').html(response);
             $('#report-list-container').fadeIn('slow');
           },
           complete: function(){
               $('#report-title').fadeIn('slow');
               $('#loader').hide();
           }
         });
    }
</script>
