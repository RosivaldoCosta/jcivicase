<style>
table {
    border: 1px solid #999999;
    border-collapse: collapse;
    margin: 5px 1px 5px 1px;
}

table tr {
    border-top: 1px solid #999999;
    color: #333333;
}
table td {
    /*border-right: 0 none;*/
    padding: 4px;
}
table th {
    /*border-right: 0 none;*/
    padding: 4px;
    margin:-2px;
}

.ast_container{
    clear: left;
    float: left;
    font-size: 12px;
    margin: 10px 5px 10px 10px;
    overflow: visible;
    width:98%;
}
.crm-pager, .search-status {
margin-bottom:5px;
    background-color: #E6E6E6;
    border: 1px solid #CCCCCC;
    color: #000000;
    height: 25px;
    padding-top: 5px;
    position: relative;
}
.element-right {
    float: right;
    margin-right: 35px;
}
table#autosaveList{
    display: block;
    width: 100%;
    min-width: 1280px;
    word-wrap: break-word;
}
table#autosaveList tbody{
    display: block;
    width: 100%;
    min-width: 1280px;
    word-wrap: break-word;
}

table#autosaveList tr{
    display: block;
    width: 100%;
    word-wrap: break-word;
    cursor: context-menu;
}
#autosaveList{
	width:100%;
}
fieldset{
	width:100%;
}
<?php if($this->nowMode == 1){ ?>
.t_id{
	width:4%;
	text-align: center;
}
.t_data{
	width:62%;
	word-wrap: break-word;
}
.t_url{
	width:13%;
}
.t_time{
	width:5%;
}
.t_caseId{
	width:4%;
	text-align: center;
}
.t_cid{
	width:4%;
	text-align: center;
}
.t_actType{
	width:4%;
	text-align: center;
}
<?php } else { ?>

table th{
	display:inline-block;
}
table td{
	display:inline-block;
}
.t_id{
	width:70px;
	text-align: center;
}
.t_data{
	width:600px;
	/*width:50%;*/
	word-wrap: break-word;
}
.t_url{
	width:200px;
}
.t_time{
	width:100px;
}
.t_caseId{
	width:70px;
	text-align: center;
}
.t_cid{
	width:70px;
	text-align: center;
}
.t_actType{
	width:50px;
	text-align: center;
}

.t_id input{
	width:70px;
}
.t_time input{
	width:100px;
}
.t_caseId input{
	width:70px;
}
.t_cid input{
	width:70px;
}
.t_actType input{
	width:50px;
}
textarea{
	width:100%;
}
#ap-content{
	min-width: 1280px;
}
<?php } ?>


#button_area{
	float:left;
	margin-left:15px;
}
#switch_mode{
	background:#9D9D9D;
	color:#fff;
	font-weight:bold;
	cursor:pointer;
}
</style>

<div class='ast_container'>

	<div>
		<form name='adminForm' action='<?php echo JURI::base();?>index.php?option=com_autosave' method='post'>
			<input type='hidden' name='mode_view' value='<?php echo $this->nowMode; ?>'></input>
 			<?php echo $this->pageNav; ?>
 		
		 <div  class="crm-pager">
		 	<div id='button_area'>
		 		<button id='switch_mode' onclick="switchModeView('<?php echo $this->nextMode; ?>');"><?php if($this->nextMode == 1){echo 'Viewing';} else {echo 'Edit';} ?></button>
		 	</div>
		 	<div style='float:right;'>
				<?php echo $this->fcase; ?>
				<?php echo $this->factivity; ?>
			</div>
		 </div> 
  		</form>      
 	
		<table id='autosaveList'>
			<tr>
				<th class='t_id'>Id</th>
				<th class='t_data'>Data</th>
				<th class='t_url'>URL</th>
				<th class='t_time'>Time</th>
				<th class='t_caseId'>Case Id </th>
				<th class='t_cid'>Contact Id</th>
				<th class='t_actType'>Activity Type</th>
			</tr>
	<?php
	if($this->nowMode == 1){
		foreach($this->saveList as $list){
			//$var = json_decode(html_entity_decode($list['data']));
			echo '<tr>'; 
				echo '<td class=\'t_id\'>'.$list['id'].'</td>'; 
				/*
				echo '<td>';
					echo '<table>';
					foreach($var as $key=>$val){
						echo '<tr><td>'.$key.'</td><td>'.$val.'</td></tr>';
					}
					echo '</table>'; 
				echo '</td>'; 
				*/
				echo '<td class=\'t_data\'>'.$list['data'].'</td>';
				echo '<td class=\'t_url\'>'.$list['url'].'</td>'; 
				echo '<td class=\'t_time\'>'.$list['time'].'</td>'; 
				echo '<td class=\'t_caseId\'>'.$list['case_id'].'</td>'; 
				echo '<td class=\'t_cid\'>'.$list['contact_id'].'</td>';
				echo '<td class=\'t_actType\'>'.$list['activity_type'].'</td>'; 
				//$dat = json_decode(html_entity_decode($list['data']));
			echo '</tr>';
		}
	} else {
		foreach($this->saveList as $list){
			//$var = json_decode(html_entity_decode($list['data']));
			echo '<tr>'; 
				echo '<td class=\'t_id\'><input type=\'text\'  value=\''.$list['id'].'\'></input></td>'; 
				/*
				echo '<td>';
					echo '<table>';
					foreach($var as $key=>$val){
						echo '<tr><td>'.$key.'</td><td>'.$val.'</td></tr>';
					}
					echo '</table>'; 
				echo '</td>'; 
				*/
				echo '<td class=\'t_data\'><textarea rows="4" cols="85">'.$list['data'].'</textarea></td>';
				echo '<td class=\'t_url\'><textarea rows="4" cols="15">'.$list['url'].'</textarea></td>'; 
				echo '<td class=\'t_time\'><input type=\'text\' value=\''.$list['time'].'\'></input></td>'; 
				echo '<td class=\'t_caseId\'><input type=\'text\' value=\''.$list['case_id'].'\'></input></td>'; 
				echo '<td class=\'t_cid\'><input type=\'text\' value=\''.$list['contact_id'].'\'></input></td>';
				echo '<td class=\'t_actType\'><input type=\'text\' value=\''.$list['activity_type'].'\'></input></td>'; 
				//$dat = json_decode(html_entity_decode($list['data']));
			echo '</tr>';
		}
	}

	?>
		</table> 

	</div>
</div>
<script type="text/javascript">
//needed for Table Column ordering
function autosaveTableOrdering( f_activity, f_case, task ) {
	var form = document.adminForm;
	form.filter_activity.value = f_activity;
	form.filter_case.value = f_case;
	submitform( task );
} 
function switchModeView( v_mode ) {
	document.adminForm.mode_view.value = v_mode;
	submitform( task );
} 
</script>