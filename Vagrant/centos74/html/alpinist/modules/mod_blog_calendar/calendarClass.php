<?php
//this is the PHP Calendar Class

// PHP Calendar Class Version 1.4 (5th March 2001)
//  
// Copyright David Wilkinson 2000 - 2001. All Rights reserved.
// Copyright Copyright (C) 2010 Groon solutions (by modified portion) ver.2.3
// 
// This software may be used, modified and distributed freely
// providing this copyright notice remains intact at the head 
// of the file.
//
// This software is freeware. The author accepts no liability for
// any loss or damages whatsoever incurred directly or indirectly 
// from the use of this script. The author of this software makes 
// no claims as to its fitness for any purpose whatsoever. If you 
// wish to use this software you should first satisfy yourself that 
// it meets your requirements.
//
// URL:   http://www.cascade.org.uk/software/php/calendar/
// Email: davidw@cascade.org.uk
// @version		$Id: calendarClass.php 88 2012-01-24 08:51:40Z BsAlpinist ver.2.3.0 $
jimport( 'joomla.utilities.string' );

class Calendar
{
    function getDayNames(){return $this->dayNames;}
    function setDayNames($names){$this->dayNames = $names;}
    
    function getMonthNames(){return $this->monthNames;}
    function setMonthNames($names){$this->monthNames = $names;}
    
    function getStartDay(){return $this->startDay;}
    function setStartDay($day){$this->startDay = $day;    }

    function getStartMonth(){return $this->startMonth;}
    function setStartMonth($month){$this->startMonth = $month;}
    
   /*
        Return the HTML for a specified month
    */
    function getMonthView($month, $year, $day,$headerformat)
    {
        return $this->getMonthHTML($month, $year, $day,1,$headerformat);
    }
    
   
    
    
    /********************************************************************************
    
        The rest are private methods. No user-servicable parts inside.
        
        You shouldn't need to call any of these functions directly.
        
    *********************************************************************************/


    /*
        Calculate the number of days in a month, taking into account leap years.
    */
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }
   
        $d = $this->daysInMonth[$month - 1];
   
        if ($month == 2)
        {
            // Check for leap year
            // Forget the 4000 rule, I doubt I'll be around then...
        
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }
    
        return $d;
    }


    /*
        Generate the HTML for a given month
    */
    function getMonthHTML($m, $y, $day, $showYear = 1,$headerformat)
    {
        $mainframe = JFactory::getApplication();
        $zone= $mainframe->getCfg('offset');
        $tmzone = new DateTimeZone($zone);
		$dtm = new DateTime("now", $tmzone);
		$offset = $dtm->getOffset() / 3600;
		
        $s = "";
        
        $a = $this->adjustDate($m, $y);
        $month = $a[0];
        $year = $a[1];        
        
    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year)+$offset*60*60);
    	$header = new JDate(mktime(12, 0, 0, $month, 1, $year),$tmzone);
// for BsAlpinist 2011-11-15
//    	$header = $header->format($headerformat);
    	$header = JHtml::_('date', $header, JText::_($headerformat));
    	$first = $date["wday"];
    	$monthName = $this->monthNames[$month - 1];
    	
    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);
    	$prevyear = $this->adjustDate($month, $year - 1);
    	$nextyear = $this->adjustDate($month, $year + 1);

    	if ($showYear == 1)
    	{
    	    $prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
    	    $nextMonth = $this->getCalendarLink($next[0], $next[1]);
    	    $nextYear  = $this->getCalendarLink($nextyear[0], $nextyear[1]);
    	    $prevYear  = $this->getCalendarLink($prevyear[0], $prevyear[1]);
    	}
    	else
    	{
    	    $prevMonth = "";
    	    $nextMonth = "";
            $prevYear = "";
    	    $nextYear = "";
    	}

    	$s .= "<table id=\"tableCalendar-".$this->modid."\" class=\"blogCalendar\">\n";
    	$s .= "<tr>\n";
    	$s .= "<td align=\"center\" class=\"blogCalendarHeader headerArrow\">" . "<a class=\"headerArrow\" id=\"prevYear-" . $this->modid . "\" href=\"$prevYear\"><i class='icon-previous'></i></a>"  . "</td>\n";
// for BsAlpinist 2011-11-15
//    	$s .= "<td align=\"center\" class=\"blogCalendarHeader headerArrow\">" . "<a class=\"headerArrow\" id=\"prevMonth-" . $this->modid . "\" href=\"$prevMonth\">&lt;</a>"  . "</td>\n";
		$s .= "<td align=\"center\" class=\"blogCalendarHeader headerArrow\">" . "<a class=\"headerArrow\" id=\"prevMonth-" . $this->modid . "\" href=\"$prevMonth\">"
				."<i class='icon-play iconrevers'></i></a>"  . "</td>\n";
    	$s .= "<td align=\"center\" class=\"blogCalendarHeader headerDate\" colspan=\"3\">";
    	if($this->monthLink != "") {
    	 $s .= "<a class=\"headerDate\" href=\"" . $this->monthLink . "\">".$header."</a></td>\n"; 
    	} else {
    	 $s .= $header."</td>\n";
    	}
// for BsAlpinist 2011-11-15
//    	$s .= "<td align=\"center\" class=\"blogCalendarHeader headerArrow\">" . "<a class=\"headerArrow\" id=\"nextMonth-" . $this->modid . "\" href=\"$nextMonth\" >&gt;</a>"  . "</td>\n";
		$s .= "<td align=\"center\" class=\"blogCalendarHeader headerArrow\">" . "<a class=\"headerArrow\" id=\"nextMonth-" . $this->modid . "\" href=\"$nextMonth\" >"
				."<i class='icon-play'></i></a>"  . "</td>\n";
    	$s .= "<td align=\"center\" class=\"blogCalendarHeader headerArrow\">" . "<a class=\"headerArrow\" id=\"nextYear-" . $this->modid . "\" href=\"$nextYear\"><i class='icon-forward'></i></a>"  . "</td>\n";
    	$s .= "</tr>\n";
    	
    	$s .= "<tr>\n";
		for($i=0;$i<7;$i++){
    	$s .= "<td class=\"dayName\">" . $this->dayNames[($this->startDay+$i)%7] . "</td>\n";
		}
    	$s .= "</tr>\n";
    	
    	$d = $this->startDay + 1 - $first;
    	while ($d > 1)
    	{
    	    $d -= 7;
    	}

		$today = getdate(time()+$offset*60*60);
		$target = null;
		if($day){
			$target = array("year"=>$y,"mon"=>$m,"mday"=>$day);
		}
    	
    	while ($d <= $daysInMonth)
    	{
    	    $s .= "<tr>\n";
    	    for ($i = 0; $i < 7; $i++) {
    	    	$class = "blogCalendarDay ";
				if( is_array($target) ) {
					if( $year == $target["year"] && $month == $target["mon"] && $d == $target["mday"]) {
						$class = "highlight blogCalendarDay ";
					}
				}
				if( $year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) {
					$class =  "highlight blogCalendarDay blogCalendarToday ";
				}
    	        $s .= "<td class=\"";
				$tdEnd="\" >";
    	        if ($d > 0 && $d <= $daysInMonth)
    	        {
    	            $link = $this->getDateLink($d, $month, $year);
					if( $link && strstr($class,"highlight blogCalendarDay") ){
						$s .= $class . "blogCalendarTodayLink ". $tdEnd .
							"<a class=\"blogCalendarToday\"" .
							" href=\"" . $link . "\" >$d</a>";
					}
					else{
						if( $link == "" ) $s .= $class."blogCalendarDayEmpty".$tdEnd.$d;
						else {
							$s .= "blogCalendarDay blogCalendarLink".$tdEnd."<a class=\"blogCalendarDay \" href=\"" . $link . "\">$d</a>";
						}
					}
    	        }
    	        else
    	        {
    	            $s .= "blogCalendarDay blogCalendarDayEmpty " . $tdEnd . "&nbsp;";
    	        }
      	        $s .= "</td>\n";       
        	    $d++;
    	    }
    	    $s .= "</tr>\n";    
    	}
    	
    	$s .= "</table>\n";
    	
    	return $s;
    }
    
 
    function adjustDate($month, $year)
    {
        $a = array();  
        $a[0] = $month;
        $a[1] = $year;
        
        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }
        
        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }
        return $a;
    }


    /* 
        The start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    var $startDay = 0;

    /* 
        The start month of the year. This is the month that appears in the first slot
        of the calendar in the year view. January = 1.
    */
    var $startMonth = 1;

    /*
        The labels to display for the days of the week. The first entry in this array
        represents Sunday.
    */
    var $dayNames = array("S", "M", "T", "W", "T", "F", "S");
    
    /*
        The labels to display for the months of the year. The first entry in this array
        represents January.
    */
    var $monthNames = array("January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December");
                            
                            
    /*
        The number of days in each month. You're unlikely to want to change this...
        The first entry in this array represents January.
    */
    var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
	var $modid = "";
    
}
?>