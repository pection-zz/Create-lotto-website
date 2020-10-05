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
				
                if($_POST['View'] == "display-table-2digi-list"){
                    $_SESSION['PDFAgentID'] = $_POST['AgentID'];
                    $_SESSION['PDFPeriodID'] = $_POST['PeriodID'];
                    function GetAgentFullName($AgentID, $Conn){
                        $sql = "SELECT FullName FROM ".TABLE_AGENT." WHERE AgentID = '".$AgentID."' LIMIT 1";
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        if($result->rowCount() > 0){
                            while($data = $result->fetchObject()){
                                return $data->FullName;
                            }
                        }else{
                            return '-';
                        }
                    }
                    
                    function GetTotal($Numbers, $Mode, $PeriodID, $AgentID, $Conn){
                        if($Mode == 'Upper'){
                            $sql = "SELECT SUM(CreditUp) AS Total ";
                        }else{
                            $sql = "SELECT SUM(CreditDown) AS Total ";
                        }
                        if($AgentID == "All"){
                            $sql .= "FROM ".TABLE_NUMBERS_DETAIL." WHERE Numbers = ".$Numbers." AND NumberType = '2Digi' AND PeriodID = '".$PeriodID."'";   
                        }else{
                            $sql .= "FROM ".TABLE_NUMBERS_DETAIL." WHERE Numbers = ".$Numbers." AND NumberType = '2Digi' AND PeriodID = '".$PeriodID."' AND AgentID = '".$AgentID."'";
                        }
                        $result = $Conn->prepare($sql);
                        $result->execute();
                        $data = $result->fetchObject();
                        return $data->Total;
                    }
                    
                    $html = '
                    <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-2digi-list">
                        <thead>
                            <tr>
                                <th class="text-center">งวดวันที่</th>
                                <th class="text-center">ตัวแทนจำหน่าย</th>
                                <th class="text-center">ตัวเลข</th>
                                <th class="text-center">2 ตัวบน</th>
                                <th class="text-center">2 ตัวล่าง</th>
                                <th class="text-center">เครื่องมือ</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $sql = "SELECT DISTINCT Numbers FROM ".TABLE_NUMBERS_DETAIL;
                        if($_POST['AgentID'] == "All"){
                            $sql .= " WHERE NumberType = '2Digi' AND PeriodID = '".$_POST['PeriodID']."' ORDER BY Numbers ASC";
                        }else{
                            $sql .= " WHERE NumberType = '2Digi' AND PeriodID = '".$_POST['PeriodID']."' AND AgentID = '".$_POST['AgentID']."' ORDER BY Numbers ASC";
                        }
                        $result = $Conn->prepare($sql);
                        $result->execute();
						$TotalsUpper = 0;
						$TotalsLower = 0;
                        if($result->rowCount() > 0){
                            while($data = $result->fetchObject()){
                                if($_POST['AgentID'] == "All"){
                                    $AgentName = '-';
                                }else{
                                    $AgentName = GetAgentFullName($_POST['AgentID'], $Conn);
                                }
								$TempTotalsUpper = GetTotal($data->Numbers, 'Upper', $_POST['PeriodID'], $_POST['AgentID'], $Conn);
								$TempTotalsLower = GetTotal($data->Numbers, 'Lower', $_POST['PeriodID'], $_POST['AgentID'], $Conn);
								$TotalsUpper = $TotalsUpper + $TempTotalsUpper;
								$TotalsLower = $TotalsLower + $TempTotalsLower;
                                $html .= '<tr>';
                                $html .= "<td class=\"text-center\" data-th=\"งวดที่\">".$_POST['PeriodID']."</td>";
                                $html .= "<td class=\"text-center\" data-th=\"ตัวแทนจำหน่าย\">".$AgentName."</td>";
                                $html .= "<td class=\"text-center\" data-th=\"ตัวเลข\">$data->Numbers</td>";
                                $html .= "<td class=\"text-center\" data-th=\"2 ตัวบน\">".number_format($TempTotalsUpper, 2, '.', ',')."</td>";
                                $html .= "<td class=\"text-center\" data-th=\"2 ตัวล่าง\">".number_format($TempTotalsLower, 2, '.', ',')."</td>";
                                $html .= "<td class=\"text-center\" data-th=\"เครื่องมือ\">
                                    <button type=\"button\" class=\"btn btn-outline btn-info\" onclick=\"DetailSend2Digi('".$_POST['PeriodID']."', '".$_POST['AgentID']."', '".$data->Numbers."');\">
                                        <i class=\"fa fa-info-circle\"></i>
                                    </button>
                                </td>";
                                $html .= '</tr>'; 
                            }
                        }
                $html .= '
                        </tbody>
                    </table>{}'.$TotalsUpper.'{}'.$TotalsLower;
					echo $html;
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
