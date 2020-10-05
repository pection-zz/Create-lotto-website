// เริ่มต้นการประกาศฟังก์ชั่น
function ExportData(File){
    switch(File){
        case 'PDF' :
            window.open("report.history.pdf.php");
            break;
        default:
            // Send error to user
            break;
    }
}

function GetHistory(){
    var PeriodID = document.getElementById('PeriodID').value;
    $.ajax({
        type: "POST",
        url: "view/display.report.history.php",
        data: "PeriodID="+PeriodID,
        success: function(data){
            var Result = data.trim(); 
            $("#display-history").html(Result);
            $('#table-report-history').DataTable({
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
                                GetHistory();
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
// สิ้นสุดการประกาศฟังก์ชั่น

$("#PeriodID").change(function() {
    GetHistory();
});

// เริ่มต้นทำงานเมื่อเรียกข้อมูลเสร็จสมบูรณ์
$(document).ready(function(){
    GetHistory();
    
    $('#PeriodID').select2({
        minimumResultsForSearch : -1
    });
});