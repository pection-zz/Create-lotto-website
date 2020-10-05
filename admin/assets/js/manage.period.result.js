function DisplayTablePeriodResultList(){
    $.ajax({
        type: "POST",
        url: "view/manage.period.result.php",
        data: "CRUD=Read&View=display-table-period-list",
        success: function(Result){
            $("#display-table-period-list").html(Result);
            $('#table-period-list').DataTable({
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
                                DisplayTablePeriodResultList();
                            }, 500);
                        }
                    },
                    {
                        text: '<i id="btn-create" class="glyphicon glyphicon-plus"></i>',
                        action: function(e, dt, node, config){
                            CRUDModal('Create', '');
                        }
                    }
                ]
            });
        }
    });
}

function CRUDModal(CRUD, PeriodID){
    var CRUD = CRUD.trim();
    var PeriodID = PeriodID.trim();
    $.ajax({
        type: "POST",
        url: "modals/manage.period.result.php",
        data: "CRUD="+CRUD+"&PeriodID="+PeriodID,
        success: function(Result){
            $("#modal-body").html(Result);
            $(".select2").select2({
                minimumResultsForSearch : -1
            });
            $('#ilotto-modal').modal({
                show    : true
            });
        }
    });
}

function DoSubmit(){
    $("#modal-status").html('<div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> กำลังบันทึกข้อมูล...</div>');
    $('#ilotto-status-modal').modal({show    : true});
    var options = {
        url     : 'ajax/ajax.manage.period.result.php',
        success : ShowSuccess,
        error   : ShowError
    };
    setTimeout(function(){
        $("#ilotto-form").ajaxSubmit(options);
        return false;
    }, 1000);
}

// Display success message
function ShowSuccess(Result, Status){
    var result = Result.split(",");
    if(result[0] == "true"){
        $("#modal-status").html('<div class="text-center text-success"><i class="fa fa-check fa-fw"></i> '+result[1]+'</div>');
        setTimeout(function(){
            $('#ilotto-status-modal').modal('hide');
            $('#ilotto-modal').modal('hide');
            $('#ilotto-form').formValidation('resetForm', true);
        }, 2000);
        DisplayTablePeriodResultList();
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
    $("#modal-status").html('<div class="text-center text-danger"><i class="glyphicon glyphicon-remove"></i> 2'+result[1]+'</div>');
    setTimeout(function(){
        $('#ilotto-status-modal').modal('hide');
    }, 2000);
}

// Call jquery before document already loaded
$(document).ready(function(){
    DisplayTablePeriodResultList();
    $('#ilotto-modal').on('shown.bs.modal', function(){
        var CRUD = document.forms["ilotto-form"]["CRUD"].value;
        if(CRUD == "Create" || CRUD == "Update"){
            $('#ilotto-form')
            .formValidation({
                framework: 'bootstrap',
                excluded: 'disabled',
                icon: {
                    valid: ' ',
                    invalid: ' ',
                    validating: 'fa fa-spinner fa-spin'
                },
                fields: {
                    PeriodID: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            }
                        }
                    },
                    Digi2Up: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            },
                            numeric: {
                                message: ' '
                            },
                            stringLength: {
                                min: 2,
                                max: 2,
                                message: ' '
                            }
                        }
                    },
                    Digi2Down: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            },
                            numeric: {
                                message: ' '
                            },
                            stringLength: {
                                min: 2,
                                max: 2,
                                message: ' '
                            }
                        }
                    },
                    Digi3: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            },
                            numeric: {
                                message: ' '
                            },
                            stringLength: {
                                min: 3,
                                max: 3,
                                message: ' '
                            }
                        }
                    }
                }
            })
            .on('success.form.fv', function(e, data) {
                e.preventDefault();
                $("#modal-status-text").html('');
                DoSubmit();
            })
            .on('err.form.fv', function(e, data) {
                document.getElementById("btn-submit").disabled = true;
                $("#modal-status-text").html('<div class="text-danger"><i class="glyphicon glyphicon-remove"></i> เกิดความผิดพลาด ข้อมูลไม่ถูกต้อง</div>');
            });
        }
        if(CRUD == "Delete"){
            $('#ilotto-form')
            .formValidation({
                framework: 'bootstrap',
                excluded: 'disabled',
                icon: {
                    valid: ' ',
                    invalid: ' ',
                    validating: 'fa fa-spinner fa-spin'
                },
                fields: {
                    PeriodID: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            }
                        }
                    },
                    AcceptExpireTime: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            }
                        }
                    }
                }
            })
            .on('success.form.fv', function(e, data) {
                e.preventDefault();
                $("#modal-status-text").html('');
                DoSubmit();
            })
            .on('err.form.fv', function(e, data) {
                document.getElementById("btn-submit").disabled = true;
                $("#modal-status-text").html('<div class="text-danger"><i class="glyphicon glyphicon-remove"></i> เกิดความผิดพลาด ข้อมูลไม่ถูกต้อง</div>');
            });
        }
    });
    $('#ilotto-modal').on('hiden.bs.modal', function(){
        $('#ilotto-form').formValidation('resetForm', true);
    });
});