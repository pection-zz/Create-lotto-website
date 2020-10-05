<?php
class Client{
	function IP(){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $IPAddress = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $IPAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $IPAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $IPAddress;
	}
	function OSName($UserAgent){
		$OSName = "Unknown";
		$OS = array(
			'/windows nt 10.0/i'    =>  'Windows 10',
			'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile'
        );
		foreach($OS as $regex => $value){ 
			if(preg_match($regex, $UserAgent)){
				$OSName = $value;
			}
		}
		return $OSName;
	}
	function BrowserName($UserAgent){
		$BrowserName = "Unknown";
		$Browser = array(
			'/edge/i'       =>  'Microsoft Edge',
            '/msie/i'       =>  'Internet Explorer',
			'/firefox/i'    =>  'Firefox',
			'/safari/i'     =>  'Safari',
			'/chrome/i'     =>  'Chrome',
			'/opera/i'      =>  'Opera',
			'/netscape/i'   =>  'Netscape',
			'/maxthon/i'    =>  'Maxthon',
			'/konqueror/i'  =>  'Konqueror',
			'/mobile/i'     =>  'Handheld Browser'
        );
		foreach($Browser as $regex => $value){ 
			if(preg_match($regex, $UserAgent)){
					$BrowserName = $value;
			}
		}
		return $BrowserName;
	}
}
?>