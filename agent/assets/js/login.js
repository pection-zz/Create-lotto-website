jQuery(document).ready(function(){
    $.backstretch([
        "../common/assets/images/login/login-3.jpg",
        "../common/assets/images/login/login-2.jpg",
        "../common/assets/images/login/login-1.jpg"
	],{duration: 3000, fade: 750});
});

$(document)[0].oncontextmenu = function() { return false; }
$(document).mousedown(function(e) {
    if(e.button == 2){
        return false;
    }else{
        return true;
    }
});
            
function DoLogin(){
    $("#modal-status").html('<div class="text-info text-center"><i class="fa fa-spinner fa-spin"></i> กำลังดำเนินการพิสูจน์ตัวตน...</div>');
    $('#ilotto-status-modal').modal({
        show    : true
    });
	var username = document.forms["login-admin"]["username"].value;
	var password = document.forms["login-admin"]["password"].value;
	if(username == null || username == "" || password == null || password == ""){
		setTimeout(function(){
			$("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</div>');
		},1500);
	}else{
		setTimeout(function(){
			$.ajax({
				type: "POST",
				url: "ajax/ajax.login.php",
				data: "Username="+username+"&Password="+password,
				dataType: "text",
				success: function(data){
                    var result = data.trim();
					var Result = result.split(",");
					if(Result[0] == 'true'){
						$("#modal-status").html('<div class="text-success text-center"><i class="fa fa-check fa-fw"></i> '+Result[1]+'</div>');
						setTimeout(function(){
							location.reload(); 
						},1500);
					}else{
						$("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> '+Result[1]+'</div>');
					}
				},
				error: function(){
					$("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> ไม่สามารถทำรายการได้</div>');
				}
			});
		},1500);
	}
    setTimeout(function(){
        $('#ilotto-status-modal').modal('hide');
    }, 3000);
}