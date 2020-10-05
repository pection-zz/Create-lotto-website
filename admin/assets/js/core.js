$(document).ready(function(){
    $("#page-loading").fadeOut();
    $(".page-contain").removeClass("page-contain");
});

$(function() {
    $('#side-menu').metisMenu();
});

$(function(){
    $(window).bind("load resize", function(){
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if(width < 768){
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        }else{
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if(height < 1) height = 1;
        if(height > topOffset){
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });
    var url = window.location;
    var element = $('ul.nav a').filter(function(){
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});

$(document)[0].oncontextmenu = function() { return false; }
$(document).mousedown(function(e) {
    if(e.button == 2){
        return false;
    }else{
        return true;
    }
});

function AuthorizeStatus(){
    $.ajax({
        type: "POST",
        url: "ajax/ajax.authorize.php",
        data: "",
        success: function(Result){
            if(Result == 'unauthorized'){
                location.reload();
            }
        }
    });
}

function DoSearch(){
    alert('Searching...');
}

function DoLogout(){
    $("#modal-status").html('<div class="text-info text-center"><i class="fa fa-spinner fa-spin"></i> กำลังออกจากระบบ โปรดรอ...</div>');
    $('#ilotto-status-modal').modal({
        show    : true
    });
    $.ajax({
        type: "POST",
        url: "ajax/ajax.logout.php",
        data: "",
        success: function(result){
            var Result = result.split(",");
            if(Result[0] == 'true'){
                setTimeout(function(){
                    $("#modal-status").html('<div class="text-success text-center"><i class="fa fa-check fa-fw"></i> ดำเนินการเรียบร้อยแล้ว โปรดรอ...</div>');
                    location.reload(); 
                },1500);
            }else{
                $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> '+Result[1]+'</div>');
            }
        }
    });
}
//Get Authorize Status every 1 minute.
setInterval(function(){ AuthorizeStatus(); }, 60000);