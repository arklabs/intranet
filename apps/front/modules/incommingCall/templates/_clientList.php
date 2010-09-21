<?php

echo _tag('h3', 'Registrar llamadas de clientes');
echo get_component('client', 'list');
?>
<script type="text/javascript">
 $(document).ready(function(){$('a.button').attr('href', $('a.button').attr('href')+'&incomming_call=1')});
</script>
