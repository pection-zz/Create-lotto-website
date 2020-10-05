function GetSellData(){
    var PeriodID = document.getElementById('PeriodID').value;
    $.ajax({
        type: "POST",
        url: "view/display.sell.data.php",
        data: "PeriodID="+PeriodID,
        success: function(Result){
            var result = Result.split("{}");
            $("#display-sell-data").html(result[0]);
            $("#display-sell-detail").html(result[1]);
            $('#table-digi-list').DataTable({
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
                                GetSellData();
                            }, 500);
                        }
                    }
                ]
            });
        }
    });
}

function GetAccounting(){
    var ReportPeriodID = document.getElementById('ReportPeriodID').value;
    var ReportAgentID = document.getElementById('ReportAgentID').value;
    $.ajax({
        type: "POST",
        url: "view/display.accounting.data.php",
        data: "PeriodID="+ReportPeriodID+"&AgentID="+ReportAgentID,
        success: function(Result){
            var result = Result.split("{}");
            $("#display-accounting").html(result[0]);
            $("#2DigiUp").val(result[1].toString());
            $("#2DigiDown").val(result[2].toString());
            $("#3Digi").val(result[3].toString());
        }
    });
}

$("#PeriodID").change(function() {
    GetSellData();
});

$("#ReportPeriodID").change(function() {
    GetAccounting();
});

$("#ReportAgentID").change(function() {
    GetAccounting();
});

$('#2DigiUp').keypress(function(e) {
    return false;
});

$('#2DigiDown').keypress(function(e) {
    return false;
});

$('#3Digi').keypress(function(e) {
    return false;
});

// Call jquery before document already loaded
$(document).ready(function(){
    GetSellData();
    GetAccounting();
    $(".select2").select2({
        minimumResultsForSearch : -1
    });
    //Get Accounting data every 5 minutes.
    setInterval(function(){ GetAccounting(); }, 300000);
});