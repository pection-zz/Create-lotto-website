<?php
require_once('../common/assets/includes/session.php');
require_once('../common/assets/class/pdo.php');

if(isset($Conn) && $Conn !== false){
    require_once('../common/assets/includes/tables.php');
	require_once('assets/class/security.php');
    
    $Security = new Security();
	if($Security->Authorize($Conn) == true){        
		include_once('../common/assets/plugins/pdf/setPDF.php');
        function DateThai($strDate){
			$strYear = date("Y",strtotime($strDate))+543;
			$strMonth= date("n",strtotime($strDate));
			$strDay= date("j",strtotime($strDate));
			$strHour= date("H",strtotime($strDate));
			$strMinute= date("i",strtotime($strDate));
			$strSeconds= date("s",strtotime($strDate));
			$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
			$strMonthThai=$strMonthCut[$strMonth];
			return "$strDay $strMonthThai $strYear $strHour:$strMinute";
		}
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
                $sql = "SELECT SUM(CreditUp) AS Total";
            }else{
                $sql = "SELECT SUM(CreditDown) AS Total";
            }
		$sql .= " FROM ".TABLE_NUMBERS_DETAIL." WHERE Numbers = ".$Numbers." AND NumberType = '2Digi' AND PeriodID = '".$PeriodID."'";
            if($AgentID != "All"){
                $sql .= " AND AgentID = '".$AgentID."'";
            }
            $result = $Conn->prepare($sql);
            $result->execute();
            $data = $result->fetchObject();
            return $data->Total;
        }
        
        $PerPage = 25;
		$Pages = 0;
        
        $sql = "SELECT DISTINCT Numbers FROM ".TABLE_NUMBERS_DETAIL." WHERE NumberType = '2Digi' AND PeriodID = '".$_SESSION['PDFPeriodID']."'";
        if($_SESSION['AgentID'] != "All"){
            $sql .= " AND AgentID = '".$_SESSION['AgentID']."'";
        }
		$sql .= " ORDER BY Numbers ASC";
        	$result = $Conn->prepare($sql);
		$result->execute();
		$Totals = $result->rowCount();
		if($Totals <= $PerPage){
			$Pages = 1;
		}else{
			if(($Totals % $PerPage) == 0){
				$Pages = ($Totals/$PerPage);
			}else{
				$Pages =($Totals/$PerPage)+1;
				$Pages = (int)$Pages;
			}
		}
        $pdfTitle = 'รายงานสรุปยอดขายเลข 2 ตัว';
        if($_SESSION['AgentID'] == "All"){
            $AgentName = '-';
        }else{
            $AgentName = GetAgentFullName($_SESSION['AgentID'], $Conn);
            $pdfTitle .= ' ของ'.$AgentName;
        }
        $AllData = 0;
        $AllUpper = 0;
        $AllLower = 0;
        $AllTotals = 0;
        $htmlcontent = '';

        for($CurrentPage = 1; $CurrentPage <= $Pages; $CurrentPage++){
            // Header
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "iLotto Administrator System", $pdfTitle);
            $pdf->SetAuthor('iLotto Administrator System');
            $pdf->SetTitle($pdfTitle);
            $pdf->setHeaderFont(array('thsarabun', '', 20));
            // Header
            $pdf->SetFont('thsarabun', '', 14);
            $Text = 'ออกรายงานข้อมูลเมื่อ '.DateThai(date("Y-m-d H:i:s"));
            //$pdf->Write(0, $Text, '', 0, 'R', true, 0, false, false, 0);
            $pdf->AddPage();
            $pdf->SetFont('thsarabun', '', 16);
            $Start = ($CurrentPage * $PerPage) - $PerPage;
            $sql = "SELECT DISTINCT Numbers FROM ".TABLE_NUMBERS_DETAIL;
            if($_SESSION['AgentID'] == "All"){
                $sql .= " WHERE NumberType = '2Digi' AND PeriodID = '".$_SESSION['PDFPeriodID']."' ORDER BY Numbers ASC LIMIT $Start,$PerPage";
            }else{
                $sql .= " WHERE NumberType = '2Digi' AND PeriodID = '".$_SESSION['PDFPeriodID']."' AND AgentID = '".$_SESSION['AgentID']."' ORDER BY Numbers ASC LIMIT $Start,$PerPage";
            }
			$result = $Conn->prepare($sql);
			$result->execute();
$htmlcontent .= <<<EOF
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right">$Text</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td bgcolor="#CCCCCC"><table width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td width="25%" align="center" valign="middle" bgcolor="#FFFFFF"><strong>งวดวันที่</strong></td>
        <td width="35%" align="center" valign="middle" bgcolor="#FFFFFF"><strong>ตัวแทนจำหน่าย</strong></td>
        <td width="10%" align="center" valign="middle" bgcolor="#FFFFFF"><strong>ตัวเลข</strong></td>
        <td width="15%" align="center" valign="middle" bgcolor="#FFFFFF"><strong>2 ตัวบน</strong></td>
        <td width="15%" align="center" valign="middle" bgcolor="#FFFFFF"><strong>2 ตัวล่าง</strong></td>
      </tr>
EOF;
            $PeriodID = $_SESSION['PDFPeriodID'];
            $Counter = $PerPage - $result->rowCount();
            while($data = $result->fetchObject()){
                $TempUpper = GetTotal($data->Numbers, 'Upper', $_SESSION['PDFPeriodID'], $_SESSION['AgentID'], $Conn);
                $Upper = number_format($TempUpper, 2, '.', ',');
                $TempLower = GetTotal($data->Numbers, 'Lower', $_SESSION['PDFPeriodID'], $_SESSION['AgentID'], $Conn);
                $Lower = number_format($TempLower, 2, '.', ',');
                $AllUpper = $AllUpper + $TempUpper;
                $AllLower = $AllLower + $TempLower;
                // Display Data
$htmlcontent .= <<<EOF
    <tr>
    <td align="center" bgcolor="#FFFFFF">$PeriodID</td>
    <td align="center" bgcolor="#FFFFFF">$AgentName</td>
    <td align="center" bgcolor="#FFFFFF">$data->Numbers</td>
    <td align="right" bgcolor="#FFFFFF">$Upper&nbsp;&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">$Lower&nbsp;&nbsp;</td>
    </tr>
EOF;
            
                $AllData++;
            } // while($data = $result->fetchObject())
            // Display Table Footer
for($i=1;$i<=$Counter;$i++){
$htmlcontent .= <<<EOF
	<tr>
    <td align="center" bgcolor="#FFFFFF">&nbsp;&nbsp;</td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;&nbsp;</td>
    <td align="center" bgcolor="#FFFFFF">&nbsp;&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF">&nbsp;&nbsp;</td>
  	</tr>
EOF;
}
            
$htmlcontent .= <<<EOF
    </table></td>
  </tr>
</table>
EOF;
            if($CurrentPage == $Pages){
                $AllTotals = $AllUpper + $AllLower;
                $AllTotals = number_format($AllTotals, 2, '.', ',');
                $AllUpper = number_format($AllUpper, 2, '.', ',');
                $AllLower = number_format($AllLower, 2, '.', ',');                
$htmlcontent .= <<<EOF
    <br>
	   <table width="98%" border="0" cellpadding="1" cellspacing="0">
            <thead></thead>
            <tbody>
	           <tr>
                    <td bgcolor="#FFFFFF">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td width="65%" align="center" valign="middle" align="right" bgcolor="#FFFFFF">รวมทั้งหมด</td>
                                    <td width="35%" align="center" valign="middle" align="right" bgcolor="#FFFFFF"><strong>$AllData</strong> รายการ</td>
                                </tr>
                                <tr>
                                    <td width="65%" align="center" valign="middle" align="right" bgcolor="#FFFFFF">รวมยอด 2 ตัวบน</td>
                                    <td width="35%" align="center" valign="middle" align="right" bgcolor="#FFFFFF"><strong>$AllUpper</strong> บาท</td>
                                </tr>
                                <tr>
                                    <td width="65%" align="center" valign="middle" align="right" bgcolor="#FFFFFF">รวมยอด 2 ตัวล่าง</td>
                                    <td width="35%" align="center" valign="middle" align="right" bgcolor="#FFFFFF"><strong>$AllLower</strong> บาท</td>
                                </tr>
                                <tr>
                                    <td width="65%" align="center" valign="middle" align="right" bgcolor="#FFFFFF">รวมทั้งหมด</td>
                                    <td width="35%" align="center" valign="middle" align="right" bgcolor="#FFFFFF"><strong>$AllTotals</strong> บาท</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
        </tbody>
	</table>
EOF;
            } // if($CurrentPage == $Pages)
            $htmlcontent = stripslashes($htmlcontent);
            $htmlcontent = AdjustHTML($htmlcontent);
            $pdf->writeHTML($htmlcontent, true, 0, true, 0);
            $htmlcontent = '';
        } // for($CurrentPage = 1; $CurrentPage <= $Pages; $CurrentPage++)
        $pdf->lastPage();
        $pdf->Output('report.send.2digi.pdf', 'I');
	}
}
?>
