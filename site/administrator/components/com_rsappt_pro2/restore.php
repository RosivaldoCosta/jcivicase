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

function buildInsert($table, $import_from = ""){
	// build insert based on number of columns in $table and $table_backup
	
	if($import_from != "" ){
		$table_backup = $import_from;
	} else {
		$table_backup = $table."_backup";
	}
	$return = "";
	
	$database = &JFactory::getDBO();
	// There may be less columns in the _backup than in the destination so we use columns 
	// from _backup to craete the insert. Other columns will default.
	
	// get columns for destination
	$sql = "show columns from ".$table_backup;
	$database->setQuery($sql);
	$destColumns = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		exit;
	}
	$return="insert into ".$table."(";
	for($i=0;$i<count($destColumns);$i++){
		$fields1 = $destColumns[$i];
		$return = $return.$fields1->Field;
		if($i<(count($destColumns))-1){
			$return = $return.", ";
		}
	}
	$return = $return.") ";

	$return = $return."select * from ".$table_backup;

	return $return;	
}

function restorenow() {
?>
<div style="overflow:scroll; width:100%">
<?php
	// -------------------------------------------------------------------------
	//  sv_apptpro2_config
	// -------------------------------------------------------------------------
	$database = &JFactory::getDBO();

	$sql = "Select Count(*) as count FROM #__sv_apptpro2_config_backup;";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No configuration information found in backup file.<br>";	
	} else {
		echo "Remove old configuration information...<br>";
		$sql = "DELETE FROM #__sv_apptpro2_config; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Load configuration from backup table... <br>";
		$sql = buildInsert("#__sv_apptpro2_config");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}

		echo "Display restored configuration... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_config;";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_config</th>".
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
	//  sv_apptpro2_resources
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_resources_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Resources found in backup file. No Resources restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_resources; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Resources from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_resources");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Resources... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_resources; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_resources</th>".
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
	}
	
	// -------------------------------------------------------------------------
	//  sv_apptpro2_requests
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_requests_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Appontments found in backup file. No Appontments restored.<br>";	
	} else {
		$sql = "DELETE FROM #__sv_apptpro2_requests; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Appontments from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_requests");
	
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}

		echo "Display restored Appontments... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_requests; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_requests</th>".
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_timeslots_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Timeslots found in backup file. No Timeslots restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_timeslots; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Timeslots from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_timeslots");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Timeslots... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_timeslots; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_timeslots</th>".
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
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_bookoffs
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_bookoffs_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No BookOffs found in backup file. No BookOffs restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_bookoffs; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore BookOffs from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_bookoffs");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored BookOffs... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_bookoffs; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_bookoffs</th>".
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
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_categories
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_categories_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Categories found in backup file. No Categories restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_categories; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Categories from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_categories");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Categories... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_categories; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_categories</th>".
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
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_services
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_services_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Services found in backup file. No Services restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_services; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Services from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_services");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Services... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_services; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_services</th>".
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
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_udfs
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_udfs_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No UDFs found in backup file. No UDFs restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_udfs; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore UDFs from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_udfs");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored UDFs... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_udfs; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_udfs</th>".
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
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_udfvalues
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_udfvalues_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No UDF Values found in backup file. No UDF Values restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_udfvalues; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore UDF Values from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_udfvalues");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored UDF Values... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_udfvalues; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_udfvalues</th>".
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
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_coupons
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_coupons_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Coupons found in backup file. No Coupons restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_coupons; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Coupons from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_coupons");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Coupons... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_coupons; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_timeslots</th>".
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
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_seat_types
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_seat_types_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Seat Types found in backup file. No Seat Types restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_seat_types; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Seat Types from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_seat_types");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Seat Types... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_seat_types; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_seat_types</th>".
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
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_seat_counts
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_seat_counts_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Seat Counts found in backup file. No Seat Counts restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_seat_counts; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Seat Counts from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_seat_counts");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Seat Counts... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_seat_counts; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_seat_counts</th>".
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
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_extras
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_extras_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Extras found in backup file. No Extras restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_extras; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Extras from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_extras");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Extras... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_extras; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_extras</th>".
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
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_extras_data
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_extras_data_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Extras Data found in backup file. No Extras Data restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_extras_data; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Extras Data from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_extras_data");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored Extras Data... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_extras_data; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_extras_data</th>".
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
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_paypal_transactions
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_paypal_transactions_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No PayPal Tranasctions found in backup file. No UDF Values restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_paypal_transactions; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore PayPal Tranasctions from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_paypal_transactions");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored PayPal Tranasctions... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_paypal_transactions; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_paypal_transactions</th>".
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
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_user_credit
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_user_credit_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No User Credit found in backup file. No User Credit restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_user_credit; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore User Credit from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_user_credit");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored User Credit... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_user_credit; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_user_credit</th>".
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
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_user_credit_activity
	// -------------------------------------------------------------------------
	$sql = "Select Count(*) as count FROM #__sv_apptpro2_user_credit_activity_backup; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No User Credit Activity found in backup file. No User Credit Activity restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_user_credit_activity; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore User Credit Activity from backup table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_user_credit_activity");
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		echo "Display restored User Credit Activity... <br>";
		$sql = "SELECT * FROM #__sv_apptpro2_user_credit_activity; ";
		$database->setQuery($sql);
		$result = $database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
		
		$fields_num = mysql_num_fields($result);
		echo "<table class='adminheading'>".
				   "<tr>".
						   "<th>Table: ".$database->getPrefix()."sv_apptpro2_user_credit_activity</th>".
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
	}


	// -------------------------------------------------------------------------
	//  sv_apptpro2_errorlog
	// -------------------------------------------------------------------------
	if(JRequest::getVar('chkRestoreErrorLog')=='on'){
		$sql = "Select Count(*) as count FROM #__sv_apptpro2_errorlog_backup; ";
			$database->setQuery($sql);
			$rowCount = Null;
			$rowCount = $database -> loadObject();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
			}
			if($rowCount->count == 0){
				echo "No Error Log found in backup file. No Error Log restored.<br>";	
			} else {
		
				$sql = "DELETE FROM #__sv_apptpro2_errorlog; "; 
				$database->setQuery($sql);
				$database -> query();
				if ($database -> getErrorNum()) {
					echo $database -> stderr();
				}
			
				echo "<br>Restore Error Log from backup table.. <br>";
				$sql = buildInsert("#__sv_apptpro2_errorlog");
				$database->setQuery($sql);
				$database -> query();
				if ($database -> getErrorNum()) {
					echo $database -> stderr();
				}
				
				echo "Display restored Error Log... <br>";
				$sql = "SELECT * FROM #__sv_apptpro2_errorlog; ";
				$database->setQuery($sql);
				$result = $database -> query();
				if ($database -> getErrorNum()) {
					echo $database -> stderr();
				}
				
				$fields_num = mysql_num_fields($result);
				echo "<table class='adminheading'>".
						   "<tr>".
								   "<th>Table: ".$database->getPrefix()."sv_apptpro2_errorlog</th>".
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
			}
	}

	// -------------------------------------------------------------------------
	//  sv_apptpro2_errorlog
	// -------------------------------------------------------------------------
	if(JRequest::getVar('chkRestoreReminderLog')=='on'){
		$sql = "Select Count(*) as count FROM #__sv_apptpro2_reminderlog_backup; ";
			$database->setQuery($sql);
			$rowCount = Null;
			$rowCount = $database -> loadObject();
			if ($database -> getErrorNum()) {
				echo $database -> stderr();
			}
			if($rowCount->count == 0){
				echo "No Reminder Log found in backup file. No Reminder Log restored.<br>";	
			} else {
		
				$sql = "DELETE FROM #__sv_apptpro2_reminderlog; "; 
				$database->setQuery($sql);
				$database -> query();
				if ($database -> getErrorNum()) {
					echo $database -> stderr();
				}
			
				echo "<br>Restore Reminder Log from backup table.. <br>";
				$sql = buildInsert("#__sv_apptpro2_reminderlog");
				$database->setQuery($sql);
				$database -> query();
				if ($database -> getErrorNum()) {
					echo $database -> stderr();
				}
				
				echo "Display restored Reminder Log... <br>";
				$sql = "SELECT * FROM #__sv_apptpro2_reminderlog; ";
				$database->setQuery($sql);
				$result = $database -> query();
				if ($database -> getErrorNum()) {
					echo $database -> stderr();
				}
				
				$fields_num = mysql_num_fields($result);
				echo "<table class='adminheading'>".
						   "<tr>".
								   "<th>Table: ".$database->getPrefix()."sv_apptpro2_reminderlog</th>".
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
			}
	}

	// -------------------------------------------------------------------------
	//  language file
	// -------------------------------------------------------------------------
	if(JRequest::getVar('chkRestoreLangFile')=='on'){
		$file = JPATH_SITE."/language/en-GB/en-GB.com_rsappt_pro2.ini_bac";
		$newfile = JPATH_SITE."/language/en-GB/en-GB.com_rsappt_pro2.ini";
		if(file_exists($file)){ 
			if (!copy($file, $newfile)) {
				echo "Failed to restore up ". $file;
			} else {
				echo "<br>Language file restored.<br>";
			}
		} else {
				echo "<br>No backup Language file found, Language file NOT restored.<br>";
		}
	}

	// -------------------------------------------------------------------------
	//  css file
	// -------------------------------------------------------------------------
	$file = JPATH_SITE."/components/com_rsappt_pro2/sv_apptpro.css_bac";
	$newfile = JPATH_SITE."/components/com_rsappt_pro2/sv_apptpro.css";
	if(file_exists($file)){ 
		if (!copy($file, $newfile)) {
			echo "Failed to restore up ". $file;
		} else {
			echo "<br>CSS file restored.<br>";
		}
	} else {
			echo "<br>No backup CSS file found, CSS file NOT restored.<br>";
	}
		
	echo "<p><span style='font-size:12px'><a href='index.php?option=com_rsappt_pro2&act=backup'>Continue...</a></span></p><br>&nbsp;";
?>
</div>
<?php
}

?>
