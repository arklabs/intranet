$(document).ready(function(){
    $(".nav-top-item.no-childs > a").each(function(){
        if ($(this).html() == 'Reportes'){
            $(this).parent().html('<span href="/dev.php/reportes/citas-por-agente" class="link dm_parent dm_current">Reportes</span');
        }
    });
});