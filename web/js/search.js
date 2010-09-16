function prepareSearchForm(){
    var collapsedLeftValue = $('.dm_page_content').width() + $('.search').width() - 50;
    var non_collapsedLeftValue = $('.dm_page_content').width() - $('.search').width()+210;
    $('.search').css('left', collapsedLeftValue);
    $('.search').fadeIn("slow");
    $('.search input.submit').val('');
    if ($('body.page_main_signin').length == 0)
        $('.search').css('visibility', 'visible');
    var isCollapsed = true;
    $('.search input.submit').click(function(event){
        if (isCollapsed){ //collapse
            event.preventDefault();
            $('.search').stop().animate({left:  non_collapsedLeftValue}, 500);
            isCollapsed = false;
        }
        else{
            if ($('.search input#query').val() == ""){
                event.preventDefault();
            }
        }
    });
    $('.sidebar').hover(function(){
        if (!isCollapsed){
            $('.search').stop().animate({left:  collapsedLeftValue}, 500);
            isCollapsed = true;
        }
    });
}
$(document).ready(function(){
    prepareSearchForm();
});
