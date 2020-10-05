function ReadAllConfig(){
   $.ajax({
        type: "POST",
        url: "view/config.php",
        data: "",
        success: function(Result){
            $("#display-config").html(Result);
            $("#AcceptNumber").select2({
                minimumResultsForSearch : -1
            });
        }
    }); 
}

function DoSubmit(){
    $("#modal-status").html('<div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> กำลังบันทึกข้อมูล...</div>');
    $('#ilotto-status-modal').modal({show    : true});
    var options = {
        url     : 'ajax/ajax.config.php',
        success : ShowSuccess,
        error   : ShowError
    };
    setTimeout(function(){
        $("#ilotto-form").ajaxSubmit(options);
        return false;
    }, 500);
}

// Display success message
function ShowSuccess(Result, Status){
    var result = Result.split(",");
    if(result[0] == 'true'){
        $("#modal-status").html('<div class="text-center text-success"><i class="fa fa-check fa-fw"></i> '+result[1]+'</div>');
        setTimeout(function(){
            $('#ilotto-status-modal').modal('hide');
        }, 1000);
        ReadAllConfig();
    }else{
        $("#modal-status").html('<div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> '+result[1]+'</div>');
        setTimeout(function(){
            $('#ilotto-status-modal').modal('hide');
        }, 2000);
    }
}

// Display error message
function ShowError(Result, Status){
    var result = Result.split(",");
    $("#modal-status").html('<div class="text-center text-danger"><i class="glyphicon glyphicon-remove"></i> '+result[1]+'</div>');
    setTimeout(function(){
        $('#ilotto-status-modal').modal('hide');
    }, 2000);
}

$(document).ready(function(){
    ReadAllConfig();
});