<?php
	
	function getDateFormat($date) {
		$tmpDate = date_create($date);
		$tmpDate = date_format($tmpDate,"Y-m-d");
		return $tmpDate;
	}
	
	function getDateDate() {
		$objTime = time();
		$tmpDate = date("Y-m-d",$objTime);
		return $tmpDate;
	}
	
	function getDateNow() {
		$objTime = time();
		$tmpDate = date("Y-m-d H:i:s",$objTime);
		return $tmpDate;
	}
	
	function getDateDiff($startDate, $endDate) {
		$tmpStart = strtotime($startDate);
		$tmpEnd = strtotime($endDate);
		$tmpDiff = $tmpEnd - $tmpStart;
		return round($tmpDiff / 86400);
	}
	
	function getYesOrDash($value) {
		if ($value == 0) {
			$strValue = '-';
		} else {
			$strValue = 'Yes';
		}
		return $strValue;
	}
	
	function getLeft($str, $length) {
	    return substr($str, 0, $length);
	}
	
	function getRight($str, $length) {
	    return substr($str, -$length);
	}

?>