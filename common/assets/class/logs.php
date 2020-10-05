<?php
class Logs{
	function Write($Path, $LogsType, $Text){
		if(!file_exists($Path)){
			mkdir($Path);
			$Index = $Path."index.php";
			if(!file_exists($Index)){
				$FileOpen = fopen($Index, 'w');
				fclose($FileOpen);
			}
		}
		$FileName = $Path.$LogsType."-".date("Y-m-d").".txt";
		if(file_exists($FileName)){
			$FileOpen = fopen($FileName, 'a');
		}else{
			$FileOpen = fopen($FileName, 'w');
		}
		if($FileOpen){
			fwrite($FileOpen, $Text."\r\n");
			return true;
		}else{
			return false;
		}
		fclose($FileOpen);
	}
	function DeleteAll($Path){
		if(file_exists($Path)){
			if(is_dir($Path)){
                $dir_handle = opendir($Path);
            }      
            if(!$dir_handle){
                return false;
            }      
            while($file = readdir($dir_handle)){
                if($file != "." && $file != ".."){
                    if(!is_dir($Path."/".$file)){
                        unlink($Path."/".$file);
                    }else{
                        DeleteAll($Path.'/'.$file);
                    }            
                }
            }
            closedir($dir_handle);
            rmdir($Path);
            return true;
		}
	}
}
?>