/******************************
* Additional function for this site
* Written by JimmyBryant
*******************************/

$(function(){             //dom ready
    
    //show hide userinfodrop
    var timerId=null;       
    $('.topheader .right').hover(function(){
        clearTimeout(timerId);
       	timerId=setTimeout(function(){$('.userinfodrop').show();},300);
    },function(){
		clearTimeout(timerId);
		timerId=setTimeout(function(){$('.userinfodrop').hide();},500);
    }); 

    //show hide vertical nav
    $('.vernav .menu').click(function(){
        var $this=$(this),url=$this.attr('href');
        var submenu=$(url);
        if(submenu.length>0){
        	if(submenu.is(':visible')){
        		submenu.slideUp();
        	}else{
        		submenu.slideDown();
        	}
        }
        return  false;
 
    });

    //close notice
    $('.noticebar .close').click(function(){
        $(this).parent().fadeOut();
    });

    //switch menu
    $('#headermenu li a').click(function(){
        $('#headermenu li').removeClass('current');
        $(this).parent().addClass('current');
        $('.mainwrapper .vernav').addClass('hide');
        $('#headersubmenu_'+$(this).attr('indexId')).show();
        return false;
    });

    $("#dialog").dialog();

});

function handler(name)
{
    var ifrHtml = '<iframe border="0" src=""></iframe>';
    switch (name)
    {
        case '':
            alert('?');
        break;

        case 'profile':
            $.get('http://admin.e10v.com/index/profile/', function(data){
                $("body").append(data);
                $("#dialog-profile").dialog({
                    resizable: true,
                    height: 360,
                    modal: true,
                    buttons: {
                        "OK": function() {
                            $(this).dialog("close");
                        },
                        Cancel: function() {
                            $(this).dialog("close");
                        }
                    }
                });
                $("input,select").uniform();
            });
        break;

        case 'modifypwd':
            
        break;

        case 'help':
            
        break;

        case 'logout':
            
        break;

        case '':
            
        break;

        default:
            alert('未知操作！');
        break;
    }
    return true;
}