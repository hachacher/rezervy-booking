<?php 
if (!class_exists('rzvy_calendar')){
class rzvy_calendar extends rzvy_slots{
	public $conn;
		
	/* Function to fetch inline calendar */
	public function rzvy_generate_calendar($year, $month, $day_name_length = 3, $first_day = 0, $pn = array(), $time_interval, $rzvy_time_format, $advance_bookingtime, $currDateTime_withTZ, $rzvy_hide_already_booked_slots_from_frontend_calendar, $minimum_date, $maximum_date, $today_date, $service_id = 0, $rzvy_langNewArr, $rzvy_langDefaultArr, $staff_id, $gc_twowaysync_eventsArr = array()){
		$first_of_month = gmmktime(0, 0, 0, $month, 1, $year);
		
		/* generate all the day names according to the current locale */
		$day_names = array(); 
		for ($n = 0, $t = (3 + $first_day) * 86400; $n < 7; $n++, $t+=86400){
			/* %A means full textual day name */
			$day_names[$n] = ucfirst(date('l', $t)); 
		}

		$month = date('m', $first_of_month);
		$year = date('Y', $first_of_month);
		$month_name = date('F', $first_of_month);
		$weekday = date('w', $first_of_month);
		$year = trim($year);
		/* adjust for $first_day */
		$weekday = ($weekday + 7 - $first_day) % 7; 
		
		/* note that some locales don't capitalize month and day names */
		$month_name_title = strtolower(trim($month_name));
		if(isset($rzvy_langNewArr[$month_name_title])){
			$title = htmlentities($rzvy_langNewArr[$month_name_title]).", ". $year;
		}else{
			$title = htmlentities($rzvy_langDefaultArr[$month_name_title]).", ". $year;
		}

		/* Calendar Start */
		
		/* previous and next links, if applicable */
		$todaydatemonth = strtotime(date('Y-m-d'));
		$maximumbookingtime = strtotime('+'.$advance_bookingtime.' months',$todaydatemonth);		
		/* $previousmonthfromthis = strtotime('-1 months',strtotime($year.'-'.$month.'-'.date('d')));
		$nextmonthfromthis = strtotime('+1 months',strtotime($year.'-'.$month.'-'.date('d'))); */
		$previousmonthfromthis = strtotime('-1 months',strtotime($year.'-'.$month));
		$nextmonthfromthis = strtotime('+1 months',strtotime($year.'-'.$month));
		$p='<span></span>';
		$pl='';
		$n='<span></span>';
		$nl='';
		if(date('Ym',$previousmonthfromthis)<=date('Ym',$maximumbookingtime) && date('Ym',$previousmonthfromthis)>=date('Ym',$todaydatemonth)){
			$p='<i class="fa fa-chevron-left"></i>';
			$pl = date('Y-m-d',$previousmonthfromthis);
		}
		if(date('Ym',$nextmonthfromthis)<=date('Ym',$maximumbookingtime) && date('Ym',$nextmonthfromthis)>=date('Ym',$todaydatemonth)){
			$n='<i class="fa fa-chevron-right"></i>';
			$nl = date('Y-m-d',$nextmonthfromthis);
		}
		/* @list($p, $pl) = each($pn); @list($n, $nl) = each($pn);  */
		?>
		<!-- begin the new month table -->
		<div class='rzvy-inline-calendar-container-main whitebox'>
			<div class='rzvy-inline-calendar-container-main-row rzvy_pagi_cal_div'>
				<?php 
				if($p){ 
					?>
					<span class="rzvy_prev_icon pl-4"><?php echo ($pl ? '<a href="javascript:void(0)" class="btn btn-link text-dark rzvy_cal_prev_month" data-month="'.$pl.'">' . $p . '</a>' : '<a href="javascript:void(0)" class="btn btn-link text-dark">'.$p.'</a>'); ?></span>
					<?php 
				} 
				?>
				<span class="rzvy_center_title"><b><?php echo $title; ?></b></span>
				<?php 
				if($n){ 
					?>
					<span class="rzvy_next_icon pr-4"><?php echo ($nl ? '<a href="javascript:void(0)" class="btn btn-link text-dark rzvy_cal_next_month" data-month="'.$nl.'">' . $n . '</a>' : '<a href="javascript:void(0)" class="btn btn-link text-dark">'.$n.'</a>'); ?></span>
					<?php 
				}
				?>
			</div>
			<!-- column headings -->
			<div class='rzvy-inline-calendar-container-main-row'>
				<?php 
				foreach($day_names as $d){ 
					?>
					<div class='rzvy-inline-calendar-container-main-rowcol'><?php $day_name_title = strtolower(trim(substr($d,0,$day_name_length))); if(isset($rzvy_langNewArr[$day_name_title])){ echo $rzvy_langNewArr[$day_name_title]; }else{ echo $rzvy_langDefaultArr[$day_name_title]; } ?></div>
					<?php 
				} 
				?>
			</div>
			<div class='rzvy-inline-calendar-container-main-row'>
		<?php 
		if($weekday > 0) 
		{
			for ($i = 0; $i < $weekday; $i++) 
			{
				/* initial 'empty' days */
				?>
				<div class='rzvy-inline-calendar-container-main-rowcel'></div>
				<?php 
			}
		}
		for($day = 1, $days_in_month = gmdate('t',$first_of_month); $day <= $days_in_month; $day++, $weekday++)
		{
			$total_days_in_month_check = gmdate('t',$first_of_month);
			if($weekday == 7)
			{
				$weekday   = 0; 
				/* start a new week  */
				
				if($day > 1 && $day <= $total_days_in_month_check){
					?>
					</div>
					<div class='rzvy-inline-calendar-container-main-row'>
					<?php
				}else{
					?>
					<div class='rzvy-inline-calendar-container-main-row'>
					<?php 
				}
			}
			if(strtotime($year."-".$month."-".$day) < strtotime($today_date)){ 
				?>
				<div data-day="" class='rzvy-inline-calendar-container-main-rowcel previous_date'><p><?php echo $day; ?></p></div>
				<?php 
			}else{ 
				$avail_date = date("Y-m-d", strtotime($year."-".$month."-".$day));
				/* maximum date check  */
				if(strtotime($avail_date)>strtotime($maximum_date)){ 
					?>
					<div data-day="" class='rzvy-inline-calendar-container-main-rowcel previous_date'><p><?php echo $day; ?></p></div>
					<?php 
				}else{
					$isOffDay = $this->isDayOffCheck($avail_date, $service_id, $staff_id); 
					if($isOffDay){
						?>
						<div data-day="" class='rzvy-inline-calendar-container-main-rowcel previous_date'><p><?php echo $day; ?></p></div>
						<?php 
					}else{
						?>
						<div data-day="<?php echo $avail_date; ?>" class='rzvy-inline-calendar-container-main-rowcel full_day_available rzvy_date_selection <?php if($todaydatemonth==strtotime($avail_date)){ echo 'rzvy_todate'; } ?>'><p><?php echo $day; ?></p></div>
						<?php 
					}
				}
			} 
		}
		if($weekday != 7){
			/* remaining "empty" days */
			for($j = 1; $j <= (7-$weekday); $j++){
				?>
				<div class='rzvy-inline-calendar-container-main-rowcel'></div>
				<?php 
			}
		}

		?>
		</div>
		</div>
		<?php
	}
} 
} 
?>