// เริ่มต้นการประกาศฟังก์ชั่น
function ClearForm(){
    for(i=1;i<=10;i++){
        document.getElementById('Number'+i).value = "";
        document.getElementById('Upper'+i).value = "";
        document.getElementById('Lower'+i).value = "";
    }
    $('#Number1').focus();
}
// สิ้นสุดการประกาศฟังก์ชั่น

function CRUDData(CRUD, Traget){
    var CRUD = CRUD.trim(); 
	var Traget = Traget.trim();
    if(CRUD == "Create"){
        if(Traget == "Database"){
            $("#modal-status").html('<div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> กำลังบันทึกข้อมูล...</div>');
            $('#ilotto-status-modal').modal({show    : true});
            var options = {
                url     : 'ajax/ajax.digi.send.php',
                success : function(data){
                    var result = data.trim(); 
                    var Result = result.split(",");
                    if(Result[0] == "true"){
                        $("#modal-status").html('<div class="text-center text-success"><i class="fa fa-check fa-fw"></i> '+Result[1]+'</div>');
                    }else{
                        $("#modal-status").html('<div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> '+Result[1]+'</div>');
                    }
                    setTimeout(function(){
                        $('#ilotto-status-modal').modal('hide');
                    }, 1000);
                    ClearForm();
                    GetTotals();
                },
                error   : function(){
                    $("#modal-status").html('<div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> ผิดพลาด การบันทึกข้อมูลถูกยกเลิก</div>');
                    setTimeout(function(){
                        $('#ilotto-status-modal').modal('hide');
                    }, 1000);
                }
            };
            setTimeout(function(){
                $("#ilotto-form").ajaxSubmit(options);
                return false;
            }, 500);
        }
    }
    if(CRUD == "Read"){}
    if(CRUD == "Update"){}
    if(CRUD == "Delete"){}
}

function GetTotals(){
    var PeriodID = document.getElementById('PeriodID').value;
    $.ajax({
        type: "POST",
        url: "ajax/ajax.digi.send.php",
        data: "Traget=Totals&CRUD=Read&PeriodID="+PeriodID,
        dataType: "text",
        success: function(data){
            var result = data.trim(); 
            var Result = result.split("{}");
            document.getElementById('MoneyUpper').value = Result[0];
            document.getElementById('MoneyLower').value = Result[1];
            document.getElementById('Totals').value = Result[2];
        }
    });
}

// เริ่มต้นทำงานเมื่อเรียกข้อมูลเสร็จสมบูรณ์
$(document).ready(function(){    
    $('#PeriodID').select2({
        minimumResultsForSearch : -1
    });
    
    GetTotals();
    
    $('#Number1').focus();
    
    $('#Number1').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper1').focus();
        }
    });
    $('#Number2').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper2').focus();
        }
    });
    $('#Number3').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper3').focus();
        }
    });
    $('#Number4').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper4').focus();
        }
    });
    $('#Number5').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper5').focus();
        }
    });
    $('#Number6').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper6').focus();
        }
    });
    $('#Number7').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper7').focus();
        }
    });
    $('#Number8').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper8').focus();
        }
    });
    $('#Number8').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper8').focus();
        }
    });
    $('#Number9').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper9').focus();
        }
    });
    $('#Number10').keypress(function(e){
        if(e.keyCode == 13){
            $('#Upper10').focus();
        }
    });
    
    $('#Upper1').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower1').focus();
        }
    });
    $('#Upper2').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower2').focus();
        }
    });
    $('#Upper3').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower3').focus();
        }
    });
    $('#Upper4').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower4').focus();
        }
    });
    $('#Upper5').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower5').focus();
        }
    });
    $('#Upper6').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower6').focus();
        }
    });
    $('#Upper7').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower7').focus();
        }
    });
    $('#Upper8').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower8').focus();
        }
    });
    $('#Upper9').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower9').focus();
        }
    });
    $('#Upper10').keypress(function(e){
        if(e.keyCode == 13){
            $('#Lower10').focus();
        }
    });
    
    $('#Lower1').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number2').focus();
        }
    });
    $('#Lower2').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number3').focus();
        }
    });
    $('#Lower3').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number4').focus();
        }
    });
    $('#Lower4').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number5').focus();
        }
    });
    $('#Lower5').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number6').focus();
        }
    });
    $('#Lower6').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number7').focus();
        }
    });
    $('#Lower7').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number8').focus();
        }
    });
    $('#Lower8').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number9').focus();
        }
    });
    $('#Lower9').keypress(function(e){
        if(e.keyCode == 13){
            $('#Number10').focus();
        }
    });
    $('#Lower10').keypress(function(e){
        if(e.keyCode == 13){
            $('#btn-send-all1').focus();
        }
    });
    
    $('#btn-send-all1').click(function(e){
        CRUDData("Create", "Database");
    });
    
     $('#btn-send-all10').click(function(e){
        CRUDData("Create", "Database");
    });
});