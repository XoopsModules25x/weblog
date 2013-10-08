<?php
/**
 * make monthly calendar block
 * @author	hodaka <hodaka@hodaka.org>
 * @version	1.0
 */

/**
 *
 */
if (!defined('DATE_WEBLOG_CALC_CLASS')) {
    define('DATE_WEBLOG_CALC_CLASS', 1);

class MyCalendar {
	/**
	 * @var	string the month to show
	 */
	var $month;
	/**
	 * @var	string	the year to show
	 */
	var $year;
	/**
	 * @var	string	today
	 */ 
	var $today;
	/**
	 * @var	string	mode post vars
	 */
	var $mode_var = "calMonth";

	/**
	 * @var	array	1 month calendar array
	 */
	var $calendar;
	/**
	 * @var	int		index to show the row number of the week
	 */
	var $row; 
	/**
	 * @var	int		index to show the col number of the day
	 */
	var $col;
	/**
	 * @var	array	days containing entries
	 */
	var $entries;

	function MyCalendar($month='') {			// construbtor to initiate date
		if( preg_match('|^(\d{4})(\d{2})$|',$month,$matches) ){
			$this->year = intval($matches[1]) ;
			$this->month = intval($matches[2]) ;
			$this->today = date("j") ;
		}else{
			$this->month = date("n");		// the month of system date
			$this->year  = date("Y");		// the year of system date
			$this->today = date("j");		// the day of system
		}
		$this->calendar = array();
		$this->calendar['days'] = array();
		$this->calendar['remarks'] = array();
		$this->row = 0;
		$this->col = 0;
	}

/*
	// requested to show another month¡©
	function IsMonthChangeRequested() {
		
		if (isset($_GET[$this->mode_var])) {
			return(intval($_GET[$this->mode_var]));
		} else {
			return false;
		}
	}

	// set year, month to show
	function setYearMonth() {
		
		if ($this->IsMonthChangeRequested()) {
			$this->year = date("Y", intval($_GET[$this->mode_var]));
			$this->month = date("n", intval($_GET[$this->mode_var]));
		}

		$this->calendar = array();
		$this->calendar['days'] = array();
		$this->calendar['remarks'] = array();
		$this->row = 0;
		$this->col = 0;
		
	}
*/
	// month title var
	function printTitleHeader() {
		
		$this->calendar['monthThis'] = mktime(0,0,0,$this->month,1,$this->year);
		$this->calendar['monthPrev'] = mktime(0,0,0,$this->month-1,1,$this->year);
		$this->calendar['monthNext'] = mktime(0,0,0,$this->month+1,1,$this->year);
		$this->calendar['yearPrev'] = mktime(0,0,0,$this->month,1,$this->year-1);
		$this->calendar['yearNext'] = mktime(0,0,0,$this->month,1,$this->year+1);		
		$this->calendar['month'] = $this->month;
		$this->calendar['year'] = $this->year;
		$this->calendar['today'] = $this->today;

	}

	// month name and day of the week
	function printHeader() {

	}

	// insert last month's days
	function printPrevMonth() {

		// what's a day of the week the 1st day of this month ?
		$firstday = date("w", mktime(0,0,0,$this->month,1,$this->year));
		if ($firstday > 0) {
			// How many days in last month¡©
			$days_prevmonth = date("t", mktime(0,0,0,$this->month - 1,1,$this->year));
		
			// insert last days until the 1st day of this month

			$days = $days_prevmonth - $firstday + 1;	// What day is Sunday ?
			while ($this->col < $firstday) {
				$this->calendar['days'][$this->row][$this->col] = $days;
				$this->calendar['remarks'][$this->row][$this->col] = "prevmonth";
				$this->col++;
				$days++;
			}
		}
	}

	// display this month
	function printThisMonth() {
		
		// insert days from 1 by 1
		for ($i=1; $i <= 31; $i++) {
			if (checkdate($this->month, $i, $this->year) == True) {
				
				// Saturday ?
				if ($this->col > 6) {
					$this->col = 0;
					$this->row++;
				}

				$this->calendar['days'][$this->row][$this->col] = $i;
				
				// is it today ?
				$remarks = 
				($i == $this->today && $this->month == date("n") && $this->year == date("Y"))? "today " : "";

				// is it Sunday ?
				$remarks .= ($this->col == 0)? "sunday " : "";
				
				// is it Saturday ?
				$remarks .= ($this->col == 6)? "saturday " : "";
				
				if($remarks != "") $remarks = substr($remarks, 0, -1);
				$this->calendar['remarks'][$this->row][$this->col] = $remarks;

				$this->col++;
			}

			else { break; }
		}
	}

	// insert next month's days
	function printNextMonth() {
		$days = 1;
		while (($this->col > 0) and ($this->col < 7)) {
			$this->calendar['days'][$this->row][$this->col] = $days;
			$this->calendar['remarks'][$this->row][$this->col] = "nextmonth";			
			$days++;
			$this->col++;
		}

	}

	// main
	function dispCalendar() {

//		$this->setYearMonth();			// set the month

		$this->printTitleHeader();		// navigation title

		$this->printHeader();			// day of the week

		$this->printPrevMonth();		// last week of the previous month
		$this->printThisMonth();		// display this month
		$this->printNextMonth();		// 1st week of the next month

		return $this->calendar;

	}
	

}

}
?>
