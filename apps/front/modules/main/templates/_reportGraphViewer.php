<?php
echo get_partial('main/graphNotAvailable');
echo _open('div#graph-container', array('style'=>"text-align: left;"));
echo _tag('img#graph src="" style="display: none;"');
echo _close('div');
?>
<script type="text/javascript">
    function loadGraph(){
        $('#graph').hide();
        $('#report-title').hide();
        $('#graph-not-available').hide();
        $('#loader').show();
        $.ajax({
           type: "GET",
           url: '/index.php/+/main/renderComponent',
           data: {mod: getGraphModuleName(), name: getGraphComponentName(), dateStart: getRangeDateStart(), dateEnd: getRangeDateEnd()},
           success: function(response){
              if (response == ''){
                  $('#graph-not-available').fadeIn('slow');
              }
              else {
                 $('#graph').attr('src', response);
                 $('#graph').fadeIn('slow');
              }
           },
           complete: function(){
               $('#report-title').fadeIn('slow');
               $('#loader').hide();
           },
           error: function(){
               $('#graph-not-available').fadeIn('slow');
           }
         });
    }
</script>
