<?php

defined('_JEXEC') or die('Direct access is not allowed');

class APhtml
{
	function list_components(&$rows)
	{
		?>
<div class="header icon-48-component"><?php echo JText::_( 'Components' ); ?></div>
		<form action="index.php?ap_task=list_components" method="post" name="adminForm">
		<table class="adminlist" cellspacing="1" id="componentlist">
			<thead>
			   <th>Name</th>
			</thead>
			<tbody>
			   <?php
			      $k = 0;
			      foreach ($rows AS $i => $row)
			      {
			      	  ?>
			      	  <tr class="row<?php echo $k;?>">
			      	     <td>
			      	     <?php if ($row->admin_menu_link) { ?>
			      	        <strong><a href="index.php?<?php echo $row->admin_menu_link;?>"><?php echo $row->name;?></a></strong>
			      	     <?php } else { ?>
			      	        <strong><span class="compname"><?php echo $row->name;?></span></strong>
			      	     <?php } ?>
			      	        <?php
			      	        if(count($row->children)) {
			      	        	foreach ($row->children AS $i2 => $child)
			      	        	{
			      	        		echo "<tr class='row$k'><td><ul><li><a href='index.php?$child->admin_menu_link'>$child->name</a></li></td></tr>";
			      	        		$k = 1 - $k;
			      	        	}
			      	        }
			      	     ?>
			      	     </td>
			      	  </tr>
			      	  <?php
			      	  $k = 1 - $k;
			      }
			   ?>
			</tbody>
		</table>	
		</form>
		<?php
	}
}
?>
