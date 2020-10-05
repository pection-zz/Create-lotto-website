<?php
require_once('../../common/assets/includes/session.php');
require_once('../../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../../common/assets/includes/tables.php');
	require_once('../assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){
        function GetSellData($PeriodID, $NumberType, $CreditType, $Conn){
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
            
            $sql = "SELECT SUM(".$CreditType.") AS Totals FROM ".TABLE_NUMBERS_DETAIL." WHERE PeriodID = '".$PeriodID."' AND NumberType = '".$NumberType."'";
            $result = $Conn->prepare($sql);
            $result->execute();
            $data = $result->fetchObject();
            if($data->Totals == null){
                return '0';
            }else{
                 return $data->Totals;
            }
        }
        
        function DateTimeThai($strDate, $ShowTime){
            $strYear = date("Y",strtotime($strDate))+543;
            $strMonth = date("n",strtotime($strDate));
            $strDay = date("j",strtotime($strDate));
            $strHour = date("H",strtotime($strDate));
            $strMinute = date("i",strtotime($strDate));
            $strSeconds = date("s",strtotime($strDate));
            $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
            $strMonthThai=$strMonthCut[$strMonth];
            if($ShowTime == 'Yes'){
                return "$strDay $strMonthThai $strYear $strHour:$strMinute";
            }else{
                return "$strDay $strMonthThai $strYear";
            }
        }
        
        if(isset($_POST['PeriodID']) && $_POST['PeriodID'] != ''){
            $PeriodID = $_POST['PeriodID'];
        }else{
            $sql = "SELECT PeriodID FROM ".TABLE_PERIOD." WHERE AcceptExpireTime < '".date("Y-m-d H:i:s")."' AND Status = 'Open' ORDER BY PeriodID DESC LIMIT 1";
            $result = $Conn->prepare($sql);
            $result->execute();
            if($result->rowCount() != 0){
                $data = $result->fetchObject();
                $PeriodID = $data->PeriodID;    
            }else{
                $PeriodID = '';
            }
        }
        $html = '
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-arrow-up fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">'.GetSellData($PeriodID, '2Digi', 'CreditUp', $Conn).'</div>
                            <div>2 ตัวบน</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">รายละเอียด</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
         <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-arrow-down fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">'.GetSellData($PeriodID, '2Digi', 'CreditDown', $Conn).'</div>
                            <div>2 ตัวล่าง</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">รายละเอียด</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-arrow-up fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">'.GetSellData($PeriodID, '3Digi', 'CreditUp', $Conn).'</div>
                            <div>3 ตัวเต็ง</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">รายละเอียด</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
         <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-arrow-down fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">'.GetSellData($PeriodID, '3Digi', 'CreditDown', $Conn).'</div>
                            <div>3 ตัวโต๊ด</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">รายละเอียด</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>{}';
        
        $html .= '
        <table class="table table-striped table-bordered table-hover table-data table-responsive" id="table-digi-list">
            <thead>
                <tr>
                    <th class="text-center">งวดวันที่</th>
                    <th class="text-center">วันที่จำหน่าย</th>
                    <th class="text-center">ตัวแทนจำหน่าย</th>
                    <th class="text-center">ตัวเลข</th>
                    <th class="text-center">2 ตัวบน / 3 ตัวตรง</th>
                    <th class="text-center">2 ตัวล่าง / 3 ตัวโต๊ด</th>
                </tr>
            </thead>
            <tbody>';
        $sql = "SELECT * FROM ".TABLE_NUMBERS_DETAIL.", ".TABLE_AGENT." WHERE ".TABLE_NUMBERS_DETAIL.".PeriodID = '".$PeriodID."' AND ".TABLE_AGENT.".AgentID = ".TABLE_NUMBERS_DETAIL.".AgentID ORDER BY DateTime DESC";
        $result = $Conn->prepare($sql);
        $result->execute();
        while($data = $result->fetchObject()){
            $html .= '<tr>';
            $html .= "<td class=\"text-center\" data-th=\"งวดวันที่\">".DateTimeThai($PeriodID, 'No')."</td>";
            $html .= "<td class=\"text-center\" data-th=\"วันที่จำหน่าย\">".DateTimeThai($data->DateTime, 'Yes')."</td>";
            $html .= "<td class=\"text-center\" data-th=\"ตัวแทนจำหน่าย\">".$data->FullName."</td>";
            $html .= "<td class=\"text-center\" data-th=\"ตัวเลข\">$data->Numbers</td>";
            $html .= "<td class=\"text-center\" data-th=\"2 ตัวบน / 3 ตัวตรง\">".number_format($data->CreditUp, 2, '.', ',')."</td>";
            $html .= "<td class=\"text-center\" data-th=\"2 ตัวล่าง / 3 ตัวโต๊ด\">".number_format($data->CreditDown, 2, '.', ',')."</td>";
            $html .= '</tr>';
        }
         $html .= '
            </tbody>
        </table>';
        echo $html;
    }
}
?>