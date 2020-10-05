function DoChangePassword(){
    $("#modal-status").html('<div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> กำลังเปลี่ยนรหัสผ่าน...</div>');
    $('#ilotto-status-modal').modal({show    : true});
    var CurrentPassword = document.getElementById('CurrentPassword').value;
    var NewPassword1 = document.getElementById('NewPassword1').value;
    var NewPassword2 = document.getElementById('NewPassword2').value;
    $.ajax({
        type: "POST",
        url: "ajax/ajax.password.php",
        data: "CurrentPassword="+CurrentPassword+"&NewPassword1="+NewPassword1+"&NewPassword2="+NewPassword2,
        dataType: "text",
        success: function(data){
            var result = data.trim(); 
            var Result = result.split(",");
            if(Result[0] == "true"){
                $("#modal-status").html('<div class="text-center text-success"><i class="fa fa-check fa-fw"></i> '+Result[1]+'</div>');
                setTimeout(function(){
                    $('#ilotto-status-modal').modal('hide');
                }, 1000);
                DoLogout();
            }else{
                $("#modal-status").html('<div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> '+Result[1]+'</div>');
                setTimeout(function(){
                    $('#ilotto-status-modal').modal('hide');
                }, 1000);
            }
        }
    });
}