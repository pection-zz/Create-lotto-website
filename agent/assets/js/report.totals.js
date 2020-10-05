// เริ่มต้นการประกาศฟังก์ชั่น
function GetAccounting(){
    var PeriodID = document.getElementById('PeriodID').value;
    $.ajax({
        type: "POST",
        url: "view/display.accounting.data.php",
        data: "PeriodID="+PeriodID,
        success: function(data){
            var result = data.trim(); 
            var Result = result.split("{}");
            $("#display-accounting").html(Result[0]);
            $("#2DigiUp").val(Result[1].toString());
            $("#2DigiDown").val(Result[2].toString());
            $("#3DigiUp").val(Result[3].toString());
            $("#3DigiDown").val(Result[4].toString());
        }
    });
}
// สิ้นสุดการประกาศฟังก์ชั่น

$("#PeriodID").change(function() {
    GetAccounting();
});

// เริ่มต้นทำงานเมื่อเรียกข้อมูลเสร็จสมบูรณ์
$(document).ready(function(){
    GetAccounting();
    
    $('#PeriodID').select2({
        minimumResultsForSearch : -1
    });
});