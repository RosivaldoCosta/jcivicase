<?php
/*
 ****************************************************************
 Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html
 *
 * ABPro is distributed WITHOUT ANY WARRANTY, or implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 *
 ************************************************************
 The latest version of ABPro is available to subscribers at:
 http://www.appointmentbookingpro.com/
 ************************************************************
*/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function backupnow() {
?>
<div style="overflow:scroll; width:100%">
<?php
	// -------------------------------------------------------------------------
	//  sv_apptpro2_requests
	// -------------------------------------------------------------------------
	$database = &JFactory::getDBO();
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_requests; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Appontments found for backup.<br>";	
	} else {
		echo "Dropping old Appontments backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_requests_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}

		echo "Create new Appontments backup table.. <br>";
		$sql = "create table #__sv_apptpro2_requests_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_requests; ";

		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_requests_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Appontments backup table .. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_requests_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";    
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
	
	// -------------------------------------------------------------------------
	//  sv_apptpro2_config
	// -------------------------------------------------------------------------
	echo "<br>Dropping old Configuration backup table..<br>";
	$sql = "drop table IF EXISTS #__sv_apptpro2_config_backup; ";
	$database->setQuery($sql);
	$database -> query();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}

	echo "Create new Configuration backup table.. <br>";
	$sql = "create table #__sv_apptpro2_config_backup type MyISAM as SELECT * FROM ". 
		"#__sv_apptpro2_config;";
	$database->setQuery($sql);
	$database -> query();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	$sql = "SELECT * FROM #__sv_apptpro2_config_backup; ";
	$database->setQuery($sql);
	$result = $database -> query();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	
	echo "Display new Configuration backup table.. <br>";
	$fields_num = mysql_num_fields($result);
    echo "<table class='adminheading'>".
               "<tr>".
                       "<th>Table: ".$database->getPrefix()."sv_apptpro2_config_backup</th>".
               "</tr>".
               "</table>";
	echo "<table class='adminlist' width='auto' ><tr>";
	// printing table headers
	for($i=0; $i<$fields_num; $i++){    
		$field = mysql_fetch_field($result);    
		echo "<td>{$field->name}</td>";}
		echo "</tr>\n";
	// printing table rows
	while($row = mysql_fetch_row($result)){
		echo "<tr>";    
		foreach($row as $cell)
		        echo "<td>$cell&nbsp;</td>";
				echo "</tr>\n";
	}
	echo "</table>\n";
	mysql_free_result($result);

	// -------------------------------------------------------------------------
	//  sv_apptpro2_resources
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_resources; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Resources found for backup.<br>";	
	} else {
		echo "<br>Dropping old Resources backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_resources_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Resources backup table.. <br>";
		$sql = "create table #__sv_apptpro2_resources_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_resources; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_resources_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Resources backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_resources_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_timeslots
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_timeslots; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Timeslots found for backup.<br>";	
	} else {
		echo "<br>Dropping old Timeslots backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_timeslots_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Timeslots backup table.. <br>";
		$sql = "create table #__sv_apptpro2_timeslots_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_timeslots; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_timeslots_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Timeslots backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_timeslots_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		

	// -------------------------------------------------------------------------
	//  sv_apptpro2_bookoffs
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_bookoffs; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No BookOffs found for backup.<br>";	
	} else {
		echo "<br>Dropping old BookOffs backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_bookoffs_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new BookOffs backup table.. <br>";
		$sql = "create table #__sv_apptpro2_bookoffs_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_bookoffs; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_bookoffs_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new BookOffs backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_bookoffs_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		
	// -------------------------------------------------------------------------
	//  sv_apptpro2_categories
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_categories; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Categories found for backup.<br>";	
	} else {
		echo "<br>Dropping old Categories backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_categories_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Categories backup table.. <br>";
		$sql = "create table #__sv_apptpro2_categories_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_categories; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_categories_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Categories backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_categories_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		
	// -------------------------------------------------------------------------
	//  sv_apptpro2_services
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_services; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Services found for backup.<br>";	
	} else {
		echo "<br>Dropping old Services backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_services_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Services backup table.. <br>";
		$sql = "create table #__sv_apptpro2_services_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_services; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_services_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Services backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_services_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_udfs
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_udfs; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No UDFs found for backup.<br>";	
	} else {
		echo "<br>Dropping old UDFs backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_udfs_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new UDFs backup table.. <br>";
		$sql = "create table #__sv_apptpro2_udfs_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_udfs; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_udfs_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new UDFs backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_udfs_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		
	// -------------------------------------------------------------------------
	//  sv_apptpro2_udfvalues
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_udfvalues; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No UDF Values found for backup.<br>";	
	} else {
		echo "<br>Dropping old UDF Values backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_udfvalues_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new UDF Values backup table.. <br>";
		$sql = "create table #__sv_apptpro2_udfvalues_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_udfvalues; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_udfvalues_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new UDF Values backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_udfvalues_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		

	// -------------------------------------------------------------------------
	//  sv_apptpro2_coupons
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_coupons; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Coupons found for backup.<br>";	
	} else {
		echo "<br>Dropping old Coupons backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_coupons_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Coupons backup table.. <br>";
		$sql = "create table #__sv_apptpro2_coupons_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_coupons; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_coupons_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Coupons backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_coupons_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		

	// -------------------------------------------------------------------------
	//  sv_apptpro2_seat_types
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_seat_types; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Seat Types found for backup.<br>";	
	} else {
		echo "<br>Dropping old Seat Types backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_seat_types_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Seat Types backup table.. <br>";
		$sql = "create table #__sv_apptpro2_seat_types_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_seat_types; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_seat_types_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Seat Types backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_seat_types_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		
	// -------------------------------------------------------------------------
	//  sv_apptpro2_seat_counts
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_seat_counts; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Seat Counts found for backup.<br>";	
	} else {
		echo "<br>Dropping old Seat Counts backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_seat_counts_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Seat Counts backup table.. <br>";
		$sql = "create table #__sv_apptpro2_seat_counts_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_seat_counts; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_seat_counts_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Seat Counts backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_seat_counts_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		
	// -------------------------------------------------------------------------
	//  sv_apptpro2_extras
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_extras; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Extras found for backup.<br>";	
	} else {
		echo "<br>Dropping old Extras backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_extras_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Extras backup table.. <br>";
		$sql = "create table #__sv_apptpro2_extras_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_extras; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_extras_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Extras backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_extras_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}

		
	// -------------------------------------------------------------------------
	//  sv_apptpro2_extras_data
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_extras_data; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Extras Data found for backup.<br>";	
	} else {
		echo "<br>Dropping old Extras Data backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_extras_data_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new Extras Data backup table.. <br>";
		$sql = "create table #__sv_apptpro2_extras_data_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_extras_data; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_extras_data_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new Extras Data backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_extras_data_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_paypal_transactions
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_paypal_transactions; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No PayPal Transactions found for backup.<br>";	
	} else {
		echo "<br>Dropping old PayPal Transactions backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_paypal_transactions_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new PayPal Transactions backup table.. <br>";
		$sql = "create table #__sv_apptpro2_paypal_transactions_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_paypal_transactions; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_paypal_transactions_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new PayPal Transactions backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_paypal_transactions_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		

	// -------------------------------------------------------------------------
	//  sv_apptpro2_user_credit
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_user_credit";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No User Credit date found for backup.<br>";	
	} else {
		echo "<br>Dropping old User Credit backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_user_credit_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new User Credit backup table.. <br>";
		$sql = "create table #__sv_apptpro2_user_credit_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_user_credit; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_user_credit_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new User Credit backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_user_credit_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		
	// -------------------------------------------------------------------------
	//  sv_apptpro2_user_credit_activity
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_user_credit_activity; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No User Credit Activity found for backup.<br>";	
	} else {
		echo "<br>Dropping old User Credit Activity backup table..<br>";
		$sql = "drop table IF EXISTS #__sv_apptpro2_user_credit_activity_backup; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Create new User Credit Activity backup table.. <br>";
		$sql = "create table #__sv_apptpro2_user_credit_activity_backup type MyISAM as SELECT * FROM ".
			"#__sv_apptpro2_user_credit_activity; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		$sql = "SELECT * FROM #__sv_apptpro2_user_credit_activity_backup; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Display new User Credit Activity backup table.. <br>";
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_user_credit_activity_backup</th>".
				   "</tr>".
				   "</table>";
		echo "<table class='adminlist' width='auto' ><tr class='row0'>";
		// printing table headers
		$k=0;
		for($i=0; $i<$fields_num; $i++){    
			$field = mysql_fetch_field($result);    
			echo "<td>{$field->name}</td>";}
			echo "</tr>\n";
		// printing table rows
		while($row = mysql_fetch_row($result)){
			echo "<tr class='row".$k."' >";
			foreach($row as $cell)
					echo "<td>$cell&nbsp;</td>";
					echo "</tr>\n";
					$k = 1 - $k;
		}
		echo "</table>\n";
		mysql_free_result($result);
	}
		
	// -------------------------------------------------------------------------
	//  sv_apptpro2_errorlog
	// -------------------------------------------------------------------------
	if(JRequest::getVar('chkBackupErrorLog')=='on'){
		$sql = "Select Count(*) as count FROM #__sv_apptpro2_errorlog; ";
		$database->setQuery($sql);
		$rowCount = Null;
		$rowCount = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		if($rowCount->count == 0){
			echo "No Error Log Entries found for backup.<br>";	
		} else {
			echo "<br>Dropping old Error Log backup table..<br>";
			$sql = "drop table IF EXISTS #__sv_apptpro2_errorlog_backup; ";
			$database->setQuery($sql);
			$database -> query();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
			}
		
			echo "Create new Error Log backup table.. <br>";
			$sql = "create table #__sv_apptpro2_errorlog_backup type MyISAM as SELECT * FROM ".
				"#__sv_apptpro2_errorlog; ";
			$database->setQuery($sql);
			$database -> query();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
			}
			$sql = "SELECT * FROM #__sv_apptpro2_errorlog_backup; ";
			$database->setQuery($sql);
			$result = $database -> query();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
			}
		
			echo "Display new Error Log backup table.. <br>";
			$fields_num = mysql_num_fields($result);
			echo "<table class='adminheading'>".
					   "<tr>".
							   "<th>Table: ".$database->getPrefix()."sv_apptpro2_errorlog_backup</th>".
					   "</tr>".
					   "</table>";
			echo "<table class='adminlist' width='auto' ><tr class='row0'>";
			// printing table headers
			$k=0;
			for($i=0; $i<$fields_num; $i++){    
				$field = mysql_fetch_field($result);    
				echo "<td>{$field->name}</td>";}
				echo "</tr>\n";
			// printing table rows
			while($row = mysql_fetch_row($result)){
				echo "<tr class='row".$k."' >";
				foreach($row as $cell)
						echo "<td>$cell&nbsp;</td>";
						echo "</tr>\n";
						$k = 1 - $k;
			}
			echo "</table>\n";
			mysql_free_result($result);
		}
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_reminderlog
	// -------------------------------------------------------------------------
	if(JRequest::getVar('chkBackupReminderLog')=='on'){
		$sql = "Select Count(*) as count FROM #__sv_apptpro2_reminderlog; ";
		$database->setQuery($sql);
		$rowCount = Null;
		$rowCount = $database -> loadObject();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		if($rowCount->count == 0){
			echo "No Reminder Log Entries found for backup.<br>";	
		} else {
			echo "<br>Dropping old Reminder Log backup table..<br>";
			$sql = "drop table IF EXISTS #__sv_apptpro2_reminderlog_backup; ";
			$database->setQuery($sql);
			$database -> query();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
			}
		
			echo "Create new Reminder Log backup table.. <br>";
			$sql = "create table #__sv_apptpro2_reminderlog_backup type MyISAM as SELECT * FROM ".
				"#__sv_apptpro2_reminderlog; ";
			$database->setQuery($sql);
			$database -> query();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
			}
			$sql = "SELECT * FROM #__sv_apptpro2_reminderlog_backup; ";
			$database->setQuery($sql);
			$result = $database -> query();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
			}
		
			echo "Display new Reminder Log backup table.. <br>";
			$fields_num = mysql_num_fields($result);
			echo "<table class='adminheading'>".
					   "<tr>".
							   "<th>Table: ".$database->getPrefix()."sv_apptpro2_reminderlog_backup</th>".
					   "</tr>".
					   "</table>";
			echo "<table class='adminlist' width='auto' ><tr class='row0'>";
			// printing table headers
			$k=0;
			for($i=0; $i<$fields_num; $i++){    
				$field = mysql_fetch_field($result);    
				echo "<td>{$field->name}</td>";}
				echo "</tr>\n";
			// printing table rows
			while($row = mysql_fetch_row($result)){
				echo "<tr class='row".$k."' >";
				foreach($row as $cell)
						echo "<td>$cell&nbsp;</td>";
						echo "</tr>\n";
						$k = 1 - $k;
			}
			echo "</table>\n";
			mysql_free_result($result);
		}
	}

	// -------------------------------------------------------------------------
	//  language file
	// -------------------------------------------------------------------------
	if(JRequest::getVar('chkBackupLangFile')=='on'){
		$file = JPATH_SITE."/language/en-GB/en-GB.com_rsappt_pro2.ini";
		$newfile = JPATH_SITE."/language/en-GB/en-GB.com_rsappt_pro2.ini_bac";

		if (!copy($file, $newfile)) {
		    echo "Failed to backed up ". $file;
		} else {
			echo "<br>Language file backed up.<br>";
		}
	}

	// -------------------------------------------------------------------------
	//  css file
	// -------------------------------------------------------------------------
	$file = JPATH_SITE."/components/com_rsappt_pro2/sv_apptpro.css";
	$newfile = JPATH_SITE."/components/com_rsappt_pro2/sv_apptpro.css_bac";

	if (!copy($file, $newfile)) {
		echo "Failed to backed up ". $file;
	} else {
		echo "<br>CSS file backed up.<br>";
	}


?>
</div>

<?php
}

?>
