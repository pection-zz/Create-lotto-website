<?php
require_once('config/lang/eng.php');
require_once('tcpdf.php');
require_once('htmltoolkit.php');

Class MYPDF extends TCPDF {
	public function Footer() {
		$this->SetY(-15);
		$this->SetFont('thsarabun', 'I', 9);
		$this->Cell(0, 10, 'หน้า '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// ค่าเริ่มต้นต่างๆ สามารถเข้าไปกำหนดได้ที่ไฟล์ tcpdf_config.php ในโฟลเดอร์ config
// สร้าง PDF document ใหม่
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
// กำหนดรายละเอียดของเอกสาร pdf แสดงเมื่อคลิกขวาที่ไฟล์ PDF แล้วเลือก Document Property
$pdf->SetCreator(PDF_CREATOR); // เครื่องมือสร้าง PDF  ค่าเริ่ม PDF_CREATOR = TCPDF
$pdf->SetAuthor('iCoffee Administrator System'); // ชื่อผู้สร้างไฟล์ PDF
$pdf->SetTitle('iCoffee Administrator System');//  กำหนด Title
$pdf->SetSubject('Data Report'); // กำหนด Subject
$pdf->SetKeywords(''); // กำหนด Keyword

//   กำหนดค่าเริ่มต้นสำหรับ Header
//	PDF_HEADER_LOGO  โลโก้รูปภาพส่วน Header
//	PDF_HEADER_LOGO_WIDTH ความกว้างของโลโก้ เป็น มิลเมตร (mm)
//	PDF_HEADER_TITLE หัวเรื่องของ Header
//	PDF_HEADER_STRING ข้อความที่ต้องการแสดงในส่วน header ขึ้นบรรทัดใหม่ใช้ \n
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "iCoffee Administrator System", "รายงานข้อมูล");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// กำหนดค่าเริ่มต้น Font สำหรับช่องว่าง
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//ตั้งค่าหน้ากระดาษ
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//กำหนดการแบ่งหน้าอัตโนมัติ
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// กำหนดอัดราส่วนของรูปภาพ
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

// กำหนดกลุ่มภาษา
$pdf->setLanguageArray($l); 

// กำหนด Font กรณีใช้ภาษาไทยใช้ thsarabun
$pdf->SetFont('thsarabun', '', 14);
?>