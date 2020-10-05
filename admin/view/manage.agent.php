<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        if(isset($_POST['CRUD']) && $_POST['CRUD'] != ''){
            if($_POST['CRUD'] == 'Create'){}
            if($_POST['CRUD'] == 'Read'){
                if($_POST['View'] == "display-table-agent-list"){
                    echo '
                    <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-agent-list">
                        <thead>
                            <tr>
                                <th class="text-center">รหัสผู้ใช้</th>
                                <th class="text-center">ชื่อผู้ใช้</th>
                                <th class="text-center">ยอดเครดิต (บาท)</th>
                                <th class="text-center">สถานะ</th>
                                <th class="text-center">เครื่องมือ</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $sql = "SELECT * FROM ".TABLE_AGENT;
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        if($result->rowCount() > 0){
                            while($data = $result->fetchObject()){
                                echo '<tr>';
                                echo "<td class=\"text-center\" data-th=\"รหัสผู้ใช้\">$data->AgentID</td>";
                                echo "<td class=\"text-center\" data-th=\"ชื่อผู้ใช้\">$data->Username</td>";
                                echo "<td class=\"text-center\" data-th=\"ยอดเครดิต (บาท)\">".number_format($data->Credit, 2, '.', ',')."</td>";
                                if($data->IsActive == "Yes"){
                                    $IsActive = '<div class="text-success">ปกติ</div>';
                                }else{
                                    $IsActive = '<div class="text-danger">ระงับการใช้งาน</div>';
                                }
                                echo "<td class=\"text-center\" data-th=\"สถานะ\">$IsActive</td>";
                                echo "<td class=\"text-center\" data-th=\"เครื่องมือ\">
                                    <button type=\"button\" class=\"btn btn-outline btn-warning\" onclick=\"CRUDModal('Update', '".$data->AgentID."');\">
                                        <i class=\"fa fa-pencil\"></i>
                                    </button>
                                    <button type=\"button\" class=\"btn btn-outline btn-info\">
                                        <i class=\"fa fa-info-circle\"></i>
                                    </button>
                                    <button type=\"button\" class=\"btn btn-outline btn-danger\">
                                        <i class=\"fa fa-trash-o\"></i>
                                    </button>
                                </td>";
                                echo '</tr>'; 
                            }
                        }
                echo '
                        </tbody>
                    </table>';
                }
            }
            if($_POST['CRUD'] == 'Update'){}
            if($_POST['CRUD'] == 'Delete'){}
        }else{
            echo '';
        }
    }
}
?>