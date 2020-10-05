function ExportData(File){
    var PeriodID = document.getElementById('PeriodID').value;
    var AgentID = document.getElementById('AgentID').value;
    switch(File){
        case 'PDF' :
            if(PeriodID == "" || AgentID == ""){
                $("#modal-status").html('<div class="text-center text-danger"><i class="fa fa-times fa-fw"></i> ผิดพลาด ข้อมูลไม่เพียงพอ</div>');
                $('#ilotto-status-modal').modal({show    : true});
                setTimeout(function(){
                    $('#ilotto-status-modal').modal('hide');
                }, 2000);
            }else{
                // export data to pdf file
                window.open("report.sell.2digi.pdf.php");
            }
            break;
        default:
            // Send error to user
            break;
    }
}

function MoneyFormat(num) {
    var p = num.toFixed(2).split(".");
    return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
    }, "") + "." + p[1];
}

function DisplayTable2DigiList(){
    var PeriodID = document.getElementById('PeriodID').value;
    var AgentID = document.getElementById('AgentID').value;
    $.ajax({
        type: "POST",
        url: "view/report.sell.2digi.php",
        data: "CRUD=Read&View=display-table-2digi-list&PeriodID="+PeriodID+"&AgentID="+AgentID,
        success: function(Result){
			var result = Result.split("{}");
			var Totals = MoneyFormat(Number(result[1]) + Number(result[2]));
			$('#TotalsUpper').val(MoneyFormat(Number(result[1])));
			$('#TotalsLower').val(MoneyFormat(Number(result[2])));
			$('#Totals').val(Totals);
            $("#display-table-2digi-list").html(result[0]);
            $('#table-2digi-list').DataTable({
                "dom"           : "Bfrtip",
                "autoWidth"     : false,
                "responsive"    : true,
                "processing"    : true,
                "bInfo"         : false,
                "bSort"         : false,
                "bFilter"       : true,
                "pageLength"    : 30,       
                "bLengthChange" : false,
                buttons: [
                    {
                        text: '<i id="btn-refresh" class="fa fa-refresh"></i>',
                        action: function(e, dt, node, config){
                            $("#btn-refresh").removeClass("fa fa-refresh");
                            $("#btn-refresh").addClass("fa fa-refresh fa-spin");
                            setTimeout(function(){ 
                                DisplayTable2DigiList();
                            }, 500);
                        }
                    },
                    {
                        text: '<i id="btn-export" class="fa fa-file-pdf-o"></i>',
                        action: function(e, dt, node, config){
                            ExportData('PDF');
                        }
                    }
                ]
            });
        }
    });
}

function DetailSend2Digi(PeriodID, AgentID, Numbers){
    $.ajax({
        type: "POST",
        url: "view/report.detail.sell.2digi.php",
        data: "CRUD=Read&Numbers="+Numbers+"&PeriodID="+PeriodID+"&AgentID="+AgentID,
        success: function(Result){
            $("#modal-body").html(Result);
            $('#table-detail-2digi-list').DataTable({
                "autoWidth"     : false,
                "responsive"    : true,
                "processing"    : true,
                "bInfo"         : false,
                "bSort"         : false,
                "bFilter"       : true,
                "pageLength"    : 30,       
                "bLengthChange" : false
            });
            $('#ilotto-modal').modal({show    : true});
        }
    });
}

$("#PeriodID").change(function() {
    DisplayTable2DigiList();
});

$("#AgentID").change(function() {
    DisplayTable2DigiList();
});

$('#TotalsUpper').keypress(function(e) {
    return false;
});

$('#TotalsLower').keypress(function(e) {
    return false;
});

$('#Totals').keypress(function(e) {
    return false;
});

$(document).ready(function(){
    $(".select2").select2({
        minimumResultsForSearch : -1
    });  
    DisplayTable2DigiList();
});