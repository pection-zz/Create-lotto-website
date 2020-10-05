function DisplayTableAgentList(){
    $.ajax({
        type: "POST",
        url: "view/manage.agent.php",
        data: "CRUD=Read&View=display-table-agent-list",
        success: function(Result){
            $("#display-table-agent-list").html(Result);
            $('#table-agent-list').DataTable({
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
                                DisplayTableAgentList();
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

function CRUDModal(CRUD, AgentID){
    var CRUD = CRUD.trim();
    var AgentID = AgentID.trim();
    if(CRUD === 'Create' || CRUD === 'Read' || CRUD === 'Update' || CRUD === 'Delete'){
        $.ajax({
            type: "POST",
            url: "modals/manage.agent.php",
            data: "CRUD="+CRUD+"&AgentID="+AgentID,
            success: function(Result){
                $("#modal-body").html(Result);
                if(CRUD === 'Create' || CRUD === 'Update'){
                    $(".select2").select2({
                        minimumResultsForSearch : -1
                    });   
                }
                $('#ilotto-modal').modal({
                    show    : true
                });
            }
        });
    }
}

function DoSubmit(){
    $("#modal-status").html('<div class="text-center text-info"><i class="fa fa-spinner fa-spin"></i> กำลังบันทึกข้อมูล...</div>');
    $('#ilotto-status-modal').modal({show    : true});
    var options = {
        url     : 'ajax/ajax.manage.agent.php',
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
    if(result[0] == 'true'){
        $("#modal-status").html('<div class="text-center text-success"><i class="fa fa-check fa-fw"></i> '+result[1]+'</div>');
        setTimeout(function(){
            $('#ilotto-status-modal').modal('hide');
            $('#ilotto-modal').modal('hide');
            $('#ilotto-form').formValidation('resetForm', true);
        }, 2000);
        DisplayTableAgentList();
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

// Call jquery before document already loaded
$(document).ready(function(){
    DisplayTableAgentList();

    $('#ilotto-modal').on('shown.bs.modal', function(){
        var CRUD = document.forms["ilotto-form"]["CRUD"].value;
        if(CRUD == "Create"){
            $('#ilotto-form')
            .formValidation({
                framework: 'bootstrap',
                excluded: 'disabled',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'fa fa-spinner fa-spin'
                },
                fields: {
                   Fullname: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            }
                        }
                    },
                    Username: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            },
                            remote: {
                                type: 'POST',
                                url: 'validator/validator.manage.agent.php',
                                data: function(validator, $field, value) {
                                    return {
                                        CRUD: validator.getFieldElements('CRUD').val(),
                                    };
                                },
                                message: ' ',
                                delay: 1000
                            }
                        }
                    },
                    Password1: {
                        validators:{
                            notEmpty: {
                                message: ' '
                            },
                            identical: {
                                field: 'Password2',
                                message: ' '
                            }
                        }
                    },
                    Password2: {
                        validators:{
                            notEmpty: {
                                message: ' '
                            },
                            identical: {
                                field: 'Password1',
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
        if(CRUD == "Update"){
            $('#ilotto-form')
            .formValidation({
                framework: 'bootstrap',
                excluded: 'disabled',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'fa fa-spinner fa-spin'
                },
                fields: {
                   Username: {
                        validators: {
                            notEmpty: {
                                message: ' '
                            }
                        }
                    },
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