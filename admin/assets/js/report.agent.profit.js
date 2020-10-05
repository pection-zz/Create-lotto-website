function DisplayProfit(){
    var PeriodID = document.getElementById('PeriodID').value;
    var AgentID = document.getElementById('AgentID').value;
    $.ajax({
        type: "POST",
        url: "view/report.agent.profit.php",
        data: "CRUD=Read&PeriodID="+PeriodID+"&AgentID="+AgentID,
        success: function(Result){
            $("#display-agent-profit").html(Result);
        }
    });
}

$("#PeriodID").change(function() {
    DisplayProfit();
});

$("#AgentID").change(function() {
    DisplayProfit();
});

$(document).ready(function(){
    $(".select2").select2({
        minimumResultsForSearch : -1
    });  
    DisplayProfit();
});