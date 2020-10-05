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
                $_POST['AgentID'] = $_SESSION['AgentID'];
                function GetSellData($PeriodID, $AgentID, $NumberType, $CreditType, $Conn){
                    if($NumberType == '2Digi'){
                        $NumberType = '2Digi';
                    }else{
                        $NumberType = '3Digi';
                    }
                    if($CreditType == 'CreditUp'){
                        $CreditType = 'CreditUp';
                    }else{
                        $CreditType = 'CreditDown';
                    }

                    $sql = "SELECT SUM(".$CreditType.") AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND AgentID = '".$AgentID."' AND NumberType = '".$NumberType."'";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    $data = $result->fetchObject();
                    if($data->Totals == null){
                        return '0';
                    }else{
                         return $data->Totals;
                    }
                }
                $html = '';
                if($_POST['AgentID'] == 'All'){
                    $sql = "SELECT * FROM ".TABLE_AGENT;
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    if($result->rowCount() > 0){
                        while($data = $result->fetchObject()){
                            $Com2DigiUp = $data->Com2DigiUp;
                            $Com2DigiDown = $data->Com2DigiDown;
                            $Com3DigiUp = $data->Com3DigiUp;
                            $Com3DigiDown = $data->Com3DigiDown;
                            $TotalsSell2DigiUp = GetSellData($_POST['PeriodID'], $data->AgentID, '2Digi', 'CreditUp', $Conn);
                            $TotalsSell2DigiDown = GetSellData($_POST['PeriodID'], $data->AgentID, '2Digi', 'CreditDown', $Conn);
                            $TotalsSell3DigiUp = GetSellData($_POST['PeriodID'], $data->AgentID, '3Digi', 'CreditUp', $Conn);
                            $TotalsSell3DigiDown = GetSellData($_POST['PeriodID'], $data->AgentID, '3Digi', 'CreditDown', $Conn);
                            $Com0 = ($TotalsSell2DigiUp * $Com2DigiUp) / 100;
                            $Com1 = ($TotalsSell2DigiDown * $Com2DigiDown) / 100;
                            $Com2 = ($TotalsSell3DigiUp * $Com3DigiUp) / 100;
                            $Com3 = ($TotalsSell3DigiDown * $Com3DigiDown) / 100;
                            
                            $html .= '<br>
                            <div class="row>
                                <div class="col-lg-12 col-sm-12 text-right">'.$data->FullName.'</div>
                            </div>
                            <div class="row>
                                <div class="col-lg-12 col-sm-12">
                                    <table class="table table-striped table-bordered table-hover table-data table-responsive" id="T1">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ยอดขาย 2 ตัวบน</th>
                                                <th class="text-center">ค่าคอม 2 ตัวบน</th>
                                                <th class="text-center">ยอดขาย 2 ตัวล่าง</th>
                                                <th class="text-center">ค่าคอม 2 ตัวล่าง</th>
                                                <th class="text-center">ยอดขาย 3 ตัวตรง</th>
                                                <th class="text-center">ค่าคอม 3 ตัวตรง</th>
                                                <th class="text-center">ยอดขาย 3 ตัวโต๊ด</th>
                                                <th class="text-center">ค่าคอม 3 ตัวโต๊ด</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-th="ยอดขาย 2 ตัวบน">'.$TotalsSell2DigiUp.'</td>
                                                <td data-th="ค่าคอม 2 ตัวบน">'.$Com0.'</td>
                                                <td data-th="ยอดขาย 2 ตัวล่าง">'.$TotalsSell2DigiDown.'</td>
                                                <td data-th="ค่าคอม 2 ตัวล่าง">'.$Com1.'</td>
                                                <td data-th="ยอดขาย 3 ตัวตรง">'.$TotalsSell3DigiUp.'</td>
                                                <td data-th="ค่าคอม 3 ตัวตรง">'.$Com2.'</td>
                                                <td data-th="ยอดขาย 3 ตัวโต๊ด">'.$TotalsSell3DigiDown.'</td>
                                                <td data-th="ค่าคอม 3 ตัวโต๊ด">'.$Com3.'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>';
                        }
                    }
                }else{
                    $sql = "SELECT * FROM ".TABLE_AGENT." WHERE AgentID = '".$_POST['AgentID']."' LIMIT 1";
                    $result = $Conn->prepare($sql);
                    $result->execute();
                    $data = $result->fetchObject();
                    
                    $Com2DigiUp = $data->Com2DigiUp;
                    $Com2DigiDown = $data->Com2DigiDown;
                    $Com3DigiUp = $data->Com3DigiUp;
                    $Com3DigiDown = $data->Com3DigiDown;
                    $TotalsSell2DigiUp = GetSellData($_POST['PeriodID'], $_POST['AgentID'], '2Digi', 'CreditUp', $Conn);
                    $TotalsSell2DigiDown = GetSellData($_POST['PeriodID'], $_POST['AgentID'], '2Digi', 'CreditDown', $Conn);
                    $TotalsSell3DigiUp = GetSellData($_POST['PeriodID'], $_POST['AgentID'], '3Digi', 'CreditUp', $Conn);
                    $TotalsSell3DigiDown = GetSellData($_POST['PeriodID'], $_POST['AgentID'], '3Digi', 'CreditDown', $Conn);
                    $Com0 = ($TotalsSell2DigiUp * $Com2DigiUp) / 100;
                    $Com1 = ($TotalsSell2DigiDown * $Com2DigiDown) / 100;
                    $Com2 = ($TotalsSell3DigiUp * $Com3DigiUp) / 100;
                    $Com3 = ($TotalsSell3DigiDown * $Com3DigiDown) / 100;
                    $html .= '<br>
                    <div class="row>
                        <div class="col-lg-12 col-sm-12 text-right">'.$data->FullName.'</div>
                    </div>
                    <div class="row>
                        <div class="col-lg-12 col-sm-12">
                            <table class="table table-striped table-bordered table-hover table-data table-responsive" id="T1">
                                <thead>
                                    <tr>
                                        <th class="text-center">ยอดขาย 2 ตัวบน</th>
                                        <th class="text-center">ค่าคอม 2 ตัวบน</th>
                                        <th class="text-center">ยอดขาย 2 ตัวล่าง</th>
                                        <th class="text-center">ค่าคอม 2 ตัวล่าง</th>
                                        <th class="text-center">ยอดขาย 3 ตัวตรง</th>
                                        <th class="text-center">ค่าคอม 3 ตัวตรง</th>
                                        <th class="text-center">ยอดขาย 3 ตัวโต๊ด</th>
                                        <th class="text-center">ค่าคอม 3 ตัวโต๊ด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-th="ยอดขาย 2 ตัวบน">'.$TotalsSell2DigiUp.'</td>
                                        <td data-th="ค่าคอม 2 ตัวบน">'.$Com0.'</td>
                                        <td data-th="ยอดขาย 2 ตัวล่าง">'.$TotalsSell2DigiDown.'</td>
                                        <td data-th="ค่าคอม 2 ตัวล่าง">'.$Com1.'</td>
                                        <td data-th="ยอดขาย 3 ตัวตรง">'.$TotalsSell3DigiUp.'</td>
                                        <td data-th="ค่าคอม 3 ตัวตรง">'.$Com2.'</td>
                                        <td data-th="ยอดขาย 3 ตัวโต๊ด">'.$TotalsSell3DigiDown.'</td>
                                        <td data-th="ค่าคอม 3 ตัวโต๊ด">'.$Com3.'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>';
                }
                echo $html;
            }
            if($_POST['CRUD'] == 'Update'){}
            if($_POST['CRUD'] == 'Delete'){}
        }
    }
}
?>
