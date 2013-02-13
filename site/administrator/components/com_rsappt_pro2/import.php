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
	// Only insert columns from the old that are in the new, other new columns will default
	
	// get columns for destination
	$sql = "show columns from ".$table;
	$database->setQuery($sql);
	$destColumns = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		exit;
	}

	// get columns for source
	$sql = "show columns from ".$table_backup;
	$database->setQuery($sql);
	$sourceColumns = $database -> loadObjectList();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
		exit;
	}
	
	// ABPro2 has new id columns names for MVC
	$new_id_name = rename_primary_key($table_backup);
	$tables_not_mvc = array("#__sv_apptpro2_udfvalues", "#__sv_apptpro2_extras_data", "#__sv_apptpro2_seat_counts", "#__sv_apptpro2_user_credit_activity");
	$select_fields = "";
	$return="insert into ".$table."(";
	for($i=0;$i<count($destColumns);$i++){
		$fields1 = $destColumns[$i];
		if($i==0){
			if (in_array($table, $tables_not_mvc)) {
				$return = $return.$destColumns[$i]->Field.", ";
			} else {
				$return = $return.$new_id_name.", ";
			}
			$select_fields = $sourceColumns[$i]->Field.", ";
		} else {
			// check to see if this field is 1.4.x table 
			for($i3=0;$i3<count($sourceColumns);$i3++){
				if ($fields1->Field == $sourceColumns[$i3]->Field){
					$return = $return.$fields1->Field.", ";
					$select_fields = $select_fields.$fields1->Field.", ";
					break;				
				}
			}
		}	
	}
	$select_fields = substr($select_fields, 0 , strlen($select_fields)-2); // remove tailing " ,"
	//echo "<br>";
	//echo $select_fields;
	
	$return = substr($return, 0 , strlen($return)-2); // remove tailing " ,"
	//echo "<br>";
	//echo "<br>";
	//echo $return;
	//exit;
	$return = $return.") ";

	$select = " select ".$select_fields;
	$select .= " from ".$table_backup;

	return $return.$select;	
}

function rename_primary_key($table){
	return str_replace("#__sv_apptpro", "id", $table);
}



function importnow() {
?>
<div style="overflow:scroll; width:100%">
<?php
	// -------------------------------------------------------------------------
	//  sv_apptpro2_config
	// -------------------------------------------------------------------------
	$database = &JFactory::getDBO();

	$sql = "Select Count(*) as count FROM #__sv_apptpro_config;";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No configuration information found in source file.<br>";	
	} else {
		echo "Remove old configuration information...<br>";
		$sql = "DELETE FROM #__sv_apptpro2_config; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "Load configuration from source table... <br>";
		$sql = buildInsert("#__sv_apptpro2_config", "#__sv_apptpro_config" );
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_resources; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Resources found in source file. No Resources restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_resources; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Resources from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_resources", "#__sv_apptpro_resources");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_requests; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Appontments found in source file. No Appontments restored.<br>";	
	} else {
		$sql = "DELETE FROM #__sv_apptpro2_requests; ";
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Appontments from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_requests", "#__sv_apptpro_requests");
	
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_timeslots; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Timeslots found in source file. No Timeslots restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_timeslots; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Timeslots from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_timeslots", "#__sv_apptpro_timeslots");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_bookoffs; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No BookOffs found in source file. No BookOffs restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_bookoffs; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore BookOffs from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_bookoffs", "#__sv_apptpro_bookoffs");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_categories; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Categories found in source file. No Categories restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_categories; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Categories from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_categories", "#__sv_apptpro_categories");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_services; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Services found in source file. No Services restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_services; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Services from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_services", "#__sv_apptpro_services" );
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_udfs; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No UDFs found in source file. No UDFs restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_udfs; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore UDFs from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_udfs", "#__sv_apptpro_udfs");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_udfvalues; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No UDF Values found in source file. No UDF Values restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_udfvalues; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore UDF Values from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_udfvalues", "#__sv_apptpro_udfvalues");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_coupons; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Coupons found in source file. No Coupons restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_coupons; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Coupons from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_coupons", "#__sv_apptpro_coupons");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_seat_types; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Seat Types found in source file. No Seat Types restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_seat_types; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Seat Types from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_seat_types", "#__sv_apptpro_seat_types");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_seat_counts; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Seat Counts found in source file. No Seat Counts restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_seat_counts; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Seat Counts from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_seat_counts", "#__sv_apptpro_seat_counts");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_extras; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Extras found in source file. No Extras restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_extras; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Extras from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_extras", "#__sv_apptpro_extras");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_extras_data; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No Extras Data found in source file. No Extras Data restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_extras_data; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore Extras Data from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_extras_data", "#__sv_apptpro_extras_data");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_paypal_transactions; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No PayPal Tranasctions found in source file. No UDF Values restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_paypal_transactions; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore PayPal Tranasctions from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_paypal_transactions", "#__sv_apptpro_paypal_transactions");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_user_credit; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No User Credit found in source file. No User Credit restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_user_credit; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore User Credit from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_user_credit", "#__sv_apptpro_user_credit");
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
	$sql = "Select Count(*) as count FROM #__sv_apptpro_user_credit_activity; ";
	$database->setQuery($sql);
	$rowCount = Null;
	$rowCount = $database -> loadObject();
	if ($database -> getErrorNum()) {
		echo $database -> stderr();
	}
	if($rowCount->count == 0){
		echo "No User Credit Activity found in source file. No User Credit Activity restored.<br>";	
	} else {

		$sql = "DELETE FROM #__sv_apptpro2_user_credit_activity; "; 
		$database->setQuery($sql);
		$database -> query();
		if ($database -> getErrorNum()) {
			echo $database -> stderr();
		}
	
		echo "<br>Restore User Credit Activity from source table.. <br>";
		$sql = buildInsert("#__sv_apptpro2_user_credit_activity", "#__sv_apptpro_user_credit_activity");
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


		
	echo "<p><span style='font-size:12px'><a href='index.php?option=com_rsappt_pro2&act=backup'>Continue...</a></span></p><br>&nbsp;";
?>
</div>
<?php
}

?>
