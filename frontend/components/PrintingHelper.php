<?php
namespace frontend\components;

use Yii;
use yii\base\Component;
use frontend\components\AccessComponent;

class PrintingHelper extends Component 
{
	public static $printerSharingName = 'm80';
	
	public static function setFont($FontName){
		$SanSerif = chr(27).chr(107).chr(1);//ESC  k n 1 => NULL, SanSerif (DEFAULT)
		$Pica = chr(27).chr(33).chr(0);// ESC  ! n 0 => Pica
		$Elite = chr(27).chr(33).chr(1);// ESC  ! n 1 => Elite
		$Proporsional = chr(27).chr(33).chr(2);// ESC  ! n 2 => Proporsional
		$Condensed = chr(27).chr(33).chr(4);// ESC  ! n 4 => Condensed
		$Emphasized = chr(27).chr(33).chr(8);// ESC  ! n 8 => Emphasized
		$DoubleStrike = chr(27).chr(33).chr(16);// ESC  ! n 16 => DoubleStrike
		$DoubleWide = chr(27).chr(33).chr(32);// ESC  ! n 32 => DoubleWide
		$Italic = chr(27).chr(33).chr(64);// ESC  ! n 64 => Italic
		$Underline = chr(27).chr(33).chr(128);// ESC  ! n 64 => Underline    
		//echo "Font Setted : ".$FontName."<br>";
		return ($FontName) ? $$FontName : $SanSerif;
    }
	
	public static function LoopVar($Name, $Num){
		$Tab = chr(9);
		$Min = "-"; $SD = "="; $Line = Chr(10); $Space = " "; $Satu = "1"; $SatuL = "1\n"; $Pagar="#"; 
		$UnderScores = "_";
		$Garis=Chr(196);
		$vVal="";
		$vDel = "";        
		//log_message('error', " \n\r ".$_SERVER['REMOTE_ADDR'].' > LoopVar : '.$Name, TRUE);
		for($i=1;$i<=$Num;$i++){
			$vVal .=$$Name;
			$vDel = ".";
		}
		return $vVal;
    }
	
	public static function getPrinterSharingName()
	{
		return self::$printerSharingName;
	}
}
?>