


function loadNotifications(){
    $(".main_notifications").load(
                '/index.php/+/main/renderComponent?mod=main&name=notifications',
                {},
                function(){
                    $('.notification').fadeIn(1500, function(){});

                    $(".close").click(
			function () {
				$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$(this).slideUp(400);
				});
				return false;
			}
                    );
                });
}
function initializeContentJavaScriptEvents(){
                loadNotifications();
               //Minimize Content Box

    //Close button:
    // Alternating table rows:
		$('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows
    // Check all checkboxes when the one in a table head is checked:
		
    // Applies modal window to any link with class face-box-trigger"
    // Initialise jQuery WYSIWYG:

		//$(".wysiwyg").wysiwyg(); // Applies WYSIWYG editor to any textarea with the class "wysiwyg"
                
}
function fixHeight(){
    height = $(window).height() - 5;
    //$(".sidebar").height(height);
    $(".sidebar").height(height);
    width = $(window).width() - $(".sidebar").width();
    $('.dm_page_content').width(width);
    $('.dm_page_content').height(height);
    $('.content-min-height').height(height -20);
    if ($.browser.msie) {
       
    }
    $('.dm_page_content').css('visibility','visible');
}

$(document).ready(function(){
       fixHeight();
       $(window).resize(function(){
           fixHeight();
       });
       loadNotifications();
       //Sidebar Accordion Menu:
        $('.nav-trackable .dm_parent').removeClass('dm_parent');
        $(".nav-top-item ul").hide(); // Hide all sub menus
        if (!$("span.link.dm_current").parent().hasClass('no-childs'))
            $("span.link.dm_current").parent().parent().slideToggle("slow"); // Slide down the current menu item's sub menu
        $("span.link.dm_current").parent().parent().parent().find('span:first').addClass('dm_current'); // Slide down the current menu item's sub menu
        $(".nav-top-item.no-childs > a").each(function(){
           if (!$(this).hasClass('dm_parent'))
               $(this).addClass('dm_parent');
        });
        $(".nav-top-item span").click( // When a top menu item is clicked...
            function () {
                    $(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
                    $(this).next().slideToggle("normal"); // Slide down the clicked sub menu
                    return false;
            }
        );

        $(".nav-top-item .dm_parent .no-childs a, .last .nav-top-item .dm_parent .no-childs span").click( // When a menu item with no sub menu is clicked...
                function () {
                        window.location.href=(this.href); // Just open the link instead of a sub menu
                        return false;
                }
        );
        
        // Sidebar Accordion Menu Hover Effect:
        $(".nav-top-item span, .nav-top-item a").hover(
                function () {
                        $(this).stop().animate({paddingRight: "25px"}, 200);
                },
                function () {
                        $(this).stop().animate({paddingRight: "15px"});
                }
        );
       //initializeContentJavaScriptEvents();
        //prepareSearchForm();
        $('a[rel=tipsy]').tipsy({fade: true, gravity: 'n'});
		$('.color-box-trigger').click(function(){
			parent.$.fn.colorbox({href: $(this).attr('href'), width:"70%", height:"80%", iframe:true, "css": ["/dmCorePlugin/lib/colorbox/theme3/colorbox.css"],"js":["/dmCorePlugin/lib/colorbox/jquery.colorbox.min.js"]});
			return false;
		}); 
});
  
 
  

