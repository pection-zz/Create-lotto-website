// เริ่มต้นการประกาศฟังก์ชั่น
function CRUDModal(CRUD, Position){
    var CRUD = CRUD.trim(); 
	var Position = Position.trim();
    $.ajax({
        type: "POST",
        url: "modals/digi.sell.php",
        data: "CRUD="+CRUD+"&Position="+Position,
        success: function(Result){
            $("#modal-body").html(Result);
            $('#ilotto-modal').modal({ show    : true });
        }
    });
}

function ClearForm(){
    document.forms["session-form"]["Number"].value = "";
    document.forms["session-form"]["MoneyUpper"].value = "";
    document.forms["session-form"]["MoneyLower"].value = "";
    $('#Number').focus();
}

function PrintSlip(){
    window.open("slip.sell.digi.pdf.php");
}

function CRUDData(CRUD, Traget){
    var CRUD = CRUD.trim(); 
	var Traget = Traget.trim();
    if(CRUD == "Create"){
        $("#modal-status").html('<div class="text-info text-center"><i class="fa fa-spinner fa-spin"></i> กำลังบันทึกข้อมูล...</div>');
        $('#ilotto-status-modal').modal({ show : true });
        if(Traget == "Database"){
            $.ajax({
                type: "POST",
                url: "ajax/ajax.digi.sell.php",
                data: "Traget=Database&CRUD=Create",
                dataType: "text",
                success: function(data){
                    var result = data.trim(); 
                    var Result = result.split(",");
                    if(Result[0] == "true"){
                        // พิมพ์สลิป
                        PrintSlip();
                        $("#modal-status").html('<div class="text-success text-center"><i class="fa fa-check fa-fw"></i> '+Result[1]+'</div>');
                        ClearForm();
                        DisplaySellList("Session");
                    }else{
                        $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> '+Result[1]+'</div>');
                    }
                },
                error: function(){
                    $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> ไม่สามารถทำรายการได้</div>');
                }
            });
            setTimeout(function(){
                $('#ilotto-status-modal').modal('hide');
            },500);
        }
        if(Traget == "Session"){
            var PeriodID     = document.forms["session-form"]["PeriodID"].value;
            var Number       = document.forms["session-form"]["Number"].value;
            var MoneyUpper   = document.forms["session-form"]["MoneyUpper"].value;
            var MoneyLower   = document.forms["session-form"]["MoneyLower"].value;
            $.ajax({
                type: "POST",
                url: "ajax/ajax.digi.sell.php",
                data: "Traget=Session&CRUD=Create&PeriodID="+PeriodID+"&Number="+Number+"&MoneyUpper="+MoneyUpper+"&MoneyLower="+MoneyLower,
                dataType: "text",
                success: function(data){
                    var result = data.trim(); 
                    var Result = result.split(",");
                    if(Result[0] == "true"){
                        $("#modal-status").html('<div class="text-success text-center"><i class="fa fa-check fa-fw"></i> '+Result[1]+'</div>');
                        ClearForm();
                        DisplaySellList("Session");
                    }else{
                        $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> '+Result[1]+'</div>');
                    }
                },
                error: function(){
                    $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> ไม่สามารถทำรายการได้</div>');
                }
            });
            setTimeout(function(){
                $('#ilotto-status-modal').modal('hide');
            },500);
        }
    }
    if(CRUD == "Read"){}
    if(CRUD == "Update"){
        $("#modal-status").html('<div class="text-info text-center"><i class="fa fa-spinner fa-spin"></i> กำลังบันทึกข้อมูล...</div>');
        $('#ilotto-status-modal').modal({ show : true });
        if(Traget == "Session"){
            var Position     = document.forms["ilotto-form"]["Position"].value;
            var MoneyUpper   = document.forms["ilotto-form"]["UpdateCreditUp"].value;
            var MoneyLower   = document.forms["ilotto-form"]["UpdateCreditDown"].value;
            $.ajax({
                type: "POST",
                url: "ajax/ajax.digi.sell.php",
                data: "Traget=Session&CRUD=Update&Position="+Position+"&MoneyUpper="+MoneyUpper+"&MoneyLower="+MoneyLower,
                dataType: "text",
                success: function(data){
                    var result = data.trim(); 
                    var Result = result.split(",");
                    if(Result[0] == "true"){
                        $("#modal-status").html('<div class="text-success text-center"><i class="fa fa-check fa-fw"></i> '+Result[1]+'</div>');
                        ClearForm();
                        DisplaySellList("Session");
                    }else{
                        $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> '+Result[1]+'</div>');
                    }
                },
                error: function(){
                    $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> ไม่สามารถทำรายการได้</div>');
                }
            });
            setTimeout(function(){
                $('#ilotto-status-modal').modal('hide');
                $('#ilotto-modal').modal('hide');
            },500);
        }
    }
    if(CRUD == "Delete"){
        $("#modal-status").html('<div class="text-info text-center"><i class="fa fa-spinner fa-spin"></i> กำลังลบข้อมูล...</div>');
        $('#ilotto-status-modal').modal({ show : true });
        if(Traget == "Session"){
            var Position     = document.forms["ilotto-form"]["Position"].value;
            var MoneyUpper   = document.forms["ilotto-form"]["UpdateCreditUp"].value;
            var MoneyLower   = document.forms["ilotto-form"]["UpdateCreditDown"].value;
            $.ajax({
                type: "POST",
                url: "ajax/ajax.digi.sell.php",
                data: "Traget=Session&CRUD=Delete&Position="+Position+"&MoneyUpper="+MoneyUpper+"&MoneyLower="+MoneyLower,
                dataType: "text",
                success: function(data){
                    var result = data.trim(); 
                    var Result = result.split(",");
                    if(Result[0] == "true"){
                        $("#modal-status").html('<div class="text-success text-center"><i class="fa fa-check fa-fw"></i> '+Result[1]+'</div>');
                        ClearForm();
                        DisplaySellList("Session");
                    }else{
                        $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> '+Result[1]+'</div>');
                    }
                },
                error: function(){
                    $("#modal-status").html('<div class="text-danger text-center"><i class="fa fa-warning fa-fw"></i> ไม่สามารถทำรายการได้</div>');
                }
            });
            setTimeout(function(){
                $('#ilotto-status-modal').modal('hide');
                $('#ilotto-modal').modal('hide');
            },500);
        }
    }
}

function DisplaySellList(View){
    $.ajax({
        type: "POST",
        url: "view/digi.sell.php",
        data: "CRUD=Read&View="+View,
        success: function(Result){
            $("#display-sell-list").html(Result);
            $('#table-sell-list').DataTable({
                "order"         : [[ 0, 'asc' ], [ 1, 'asc' ]],
                "dom"           : "Bfrtip",
                "bPaginate"     : false,
                "autoWidth"     : false,
                "responsive"    : true,
                "processing"    : true,
                "bInfo"         : false,
                "bSort"         : true,
                "bFilter"       : true,
                "pageLength"    : 10,       
                "bLengthChange" : false,
                buttons: [
                    {
                        text: '<i id="btn-refresh" class="fa fa-refresh"></i>',
                        action: function(e, dt, node, config){
                            $("#btn-refresh").removeClass("fa fa-refresh");
                            $("#btn-refresh").addClass("fa fa-refresh fa-spin");
                            setTimeout(function(){ 
                                DisplaySellList("Session");
                            }, 500);
                        }
                    }
                ]
            });
        }
    });
}
// สิ้นสุดการประกาศฟังก์ชั่น



// เริ่มต้นทำงานเมื่อเรียกข้อมูลเสร็จสมบูรณ์
$(document).ready(function(){    
    $('#PeriodID').select2({
        minimumResultsForSearch : -1
    });
    
    DisplaySellList("Session");
    $('#Number').focus();
    
    $('#btn-save-session').click(function(){
        CRUDData("Create", "Session");
        $('#Number').focus();
    });
    
    $('#Number').keypress(function(e){
        if(e.keyCode == 13){
            $('#MoneyUpper').focus();
        }
    });
    
    $('#MoneyUpper').keypress(function(e){
        if(e.keyCode == 13){
            $('#MoneyLower').focus();
        }
    });
    
    $('#MoneyLower').keypress(function(e){
        if(e.keyCode == 13){
            $('#btn-save-session').focus();
        }
    });
    
    $('#ilotto-modal').on('shown.bs.modal', function(){
        $('#btn-update').click(function(){
            CRUDData("Update", "Session");
        });
        
        $('#btn-delete').click(function(){
            CRUDData("Delete", "Session");
        });
    });
});