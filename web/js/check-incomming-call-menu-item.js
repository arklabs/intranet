$(document).ready(function(){
    $(".nav-top-item.no-childs > a").each(function(){
        if ($(this).html() == 'Registro de llamadas'){
            $(this).parent().html('<span href="/index.php/main/registro-de-llamadas" class="link dm_parent dm_current">Registro de llamadas</span');
        }
    });
});