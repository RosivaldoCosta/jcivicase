<?php
defined('_JEXEC') or die('Restricted access'); 


class modJboloHelper	
{
//Get username array in chatbar
function getUserNameArray()
	{
		require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
		global $mainframe;
		$count		= '';
		$chatuser	= '';
		$chattitle	= '';
		$rows		= '';
		$listarray =array();
		$k=0;
		$user =& JFactory::getUser();	
		$doc =& JFactory::getDocument();
	
		if($chat_config['chatusertitle'])
			$chattitle	= 'username';
		else
			$chattitle	= 'name';

		if ($user->id) { 

			$db	= JFactory::getDBO();
			if($chat_config['community']==1)
			{ //this is for community builder
				if($chat_config['fonly'])
				{
					$query="SELECT DISTINCT a.id, u.".$chattitle.", b.avatar, u.name, u.username
					FROM
					(
					SELECT DISTINCT u.id 
					FROM jos_users u, jos_session s, jos_comprofiler_members a 
					LEFT JOIN jos_comprofiler b ON a.memberid = b.user_id 
					WHERE a.referenceid=".$user->id." 
					AND u.id = a.memberid 
					AND a.memberid IN ( s.userid ) 
					AND (a.accepted=1) 
					AND s.client_id = ".(int)$mainframe->isAdmin()." 
					AND u.block=0
					ORDER BY u.username
					) 
					AS a
					LEFT JOIN jos_comprofiler b ON b.user_id = a.id
					LEFT JOIN jos_comprofiler_members AS c on c.referenceid=a.id
					LEFT JOIN jos_users AS u ON u.id=a.id
					LEFT JOIN jos_session AS s ON s.userid=a.id 
					WHERE c.memberid=".$user->id."
					AND c.accepted=1
					AND b.banned=0
					AND s.client_id = ".(int)$mainframe->isAdmin()."
					ORDER BY u.".$chattitle; 
				}
				else
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
					"FROM #__users u, #__session s, #__comprofiler b
					WHERE u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
					ORDER BY u.".$chattitle ;
				}	
				
			}
			else if( $chat_config['community']==2 )
			{
				$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
				"FROM #__users u, #__session s ".
				"WHERE u.id = s.userid AND s.client_id = ".(int)$mainframe->isAdmin()."
				ORDER BY u.".$chattitle ;
			}
			else if( $chat_config['community']==0 )
			{ 
			// this is for jomsocial community 
				if($chat_config['fonly'])
				{
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
					. ' FROM #__community_connection as a, #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE a.`connect_from`='. $user->id
					. ' AND cu.userid=a.connect_to '
					. ' AND a.`status`=1 '
					. ' AND a.`connect_to`=b.`id` '
					. ' AND b.id=s.userid '
					. ' AND s.client_id = '.(int)$mainframe->isAdmin()
					. ' ORDER BY b.'.$chattitle ;
				}
				else
				{
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
					. ' FROM  #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = '.(int)$mainframe->isAdmin()
					. ' ORDER BY b.'.$chattitle ;
				}	
			}
			else
			{
				// this is for People Touch
				if($chat_config['fonly'])
				{	
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", b.username, b.name "
					. ' FROM #__community_userrelations as a, #__users as b , #__session AS s'
					. ' WHERE a.`user_id`='. $user->id
					. ' AND a.`status`=1 '
					. ' AND a.`friend_id`=b.`id` '
					. ' AND b.id=s.userid '
					. ' AND s.client_id = '.(int)$mainframe->isAdmin()
					. ' ORDER BY b.'.$chattitle ;
				}
				else
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
				"FROM #__users u, #__session s ".
				"WHERE u.id = s.userid AND s.client_id = ".(int)$mainframe->isAdmin()." AND u.id<> " . $user->id . 
				" ORDER BY u.".$chattitle ;
				}


			}
			
			$db->setQuery($query);
			$rows	=	$db->loadObjectList();
			$chatuser	.= "<div id='chbox-holder'>
								<div class='ch-box-tl'>
								<div class='ch-box-tr'>	".Jtext::_('MOD_CHAT_TITLE')."</div></div>							
								
								<div class='ch-box-mid'>
								<ul id='jbusers'>
								<li class='logeduser'>".$user->$chattitle."</li>"; 

			$count	= count($rows);
			$i=1;
			$itemid	= JRequest::getVar('Itemid');
			if($count)
			foreach ($rows as $row)
				if( $row->id != $user->id )
				{
					if( $chat_config['community']==1 || $chat_config['community']!=2 )
					{
						$img	= '';
						if($chat_config['community']==1){
							$a	=	'<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_comprofiler&&task=userProfile&user='.$row->id.'&Itemid='.$itemid.'">';
							if($row->avatar)
								$img	= $a.'<img src="images/comprofiler/'.$row->avatar.'" width="120" height="90" alt="'.$row->username.'" ></a>';	
							else
								$img	= $a.'<img src="components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png" alt="'.$row->username.'"></a>';
											
						}	
						else if(!$chat_config['community']) 
						{
							$a	= '<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_community&view=profile&userid='.$row->id.'&Itemid='.$itemid.'">';
							$img= $a.'<img src="'.$row->thumb.'" alt="'.$row->username.'"></a>';
						}						
						
						$chatuser	.= "<li><a class='tt' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
						$listarray[$k] = $row->username;
							$k++;				
										
						}
					else
					{	//this is for stand alone
						$chatuser	.= "<li><a class='tt' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							$listarray[$k] = $row->username;
							$k++;
	
					}
				}
				
				if($user && !$rows)
					$chatuser	.= "<div class='onoffmsg'>".Jtext::_('MOD_ONLINE_MSG')."</div>";
									
					$chatuser .="</ul></div></div>";
		}
		else
			$chatuser	.= Jtext::_('MOD_OFFLINE_MSG');

		if($count>10)	$chatuser	= "<div style='height:200px; overflow:auto;'>" . $chatuser . "</div>";

		
	return $listarray;
	
	}


//For Module display
function getList(&$params)
	{
		require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
		global $mainframe;
		$count		= '';
		$chatuser	= '';
		$chattitle	= '';
		$rows		= '';
		$user =& JFactory::getUser();	
		$doc =& JFactory::getDocument();
	
		if($chat_config['chatusertitle'])
			$chattitle	= 'username';
		else
			$chattitle	= 'name';

		if ($user->id) { 

			$db	= JFactory::getDBO();
			if($chat_config['community']==1)
			{ //this is for community builder
				if($chat_config['fonly'])
				{
					$query="SELECT DISTINCT a.id, u.".$chattitle.", b.avatar, u.name, u.username
					FROM
					(
					SELECT DISTINCT u.id 
					FROM jos_users u, jos_session s, jos_comprofiler_members a 
					LEFT JOIN jos_comprofiler b ON a.memberid = b.user_id 
					WHERE a.referenceid=".$user->id." 
					AND u.id = a.memberid 
					AND a.memberid IN ( s.userid ) 
					AND (a.accepted=1) 
					AND s.client_id = ".(int)$mainframe->isAdmin()."
					AND u.block=0
					ORDER BY u.username
					) 
					AS a
					LEFT JOIN jos_comprofiler b ON b.user_id = a.id
					LEFT JOIN jos_comprofiler_members AS c on c.referenceid=a.id
					LEFT JOIN jos_users AS u ON u.id=a.id
					LEFT JOIN jos_session AS s ON s.userid=a.id 
					WHERE c.memberid=".$user->id."
					AND c.accepted=1
					AND b.banned=0
					AND s.client_id = ".(int)$mainframe->isAdmin()."
					ORDER BY u.".$chattitle;
				}
				else
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
					"FROM #__users u, #__session s, #__comprofiler b
				WHERE u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()." AND u.id<> " . $user->id . 
					" ORDER BY u.".$chattitle ;
				}	
				
			}
			else if( $chat_config['community']==2 )
			{
				$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
				"FROM #__users u, #__session s ".
				"WHERE u.id = s.userid AND s.client_id = ".(int)$mainframe->isAdmin()." AND u.id<> " . $user->id . 
				" ORDER BY u.".$chattitle ;
			}
			else if( $chat_config['community']==0 )
			{ 
			// this is for jomsocial community 
				if($chat_config['fonly'])
				{
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
					. ' FROM #__community_connection as a, #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE a.`connect_from`='. $user->id
					. ' AND cu.userid=a.connect_to '
					. ' AND a.`status`=1 '
					. ' AND a.`connect_to`=b.`id` '
					. ' AND b.id=s.userid '
					. ' AND s.client_id = '.(int)$mainframe->isAdmin().' '
					. ' ORDER BY b.'.$chattitle ;
				}
				else
				{
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
					. ' FROM  #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = '.(int)$mainframe->isAdmin().' AND b.id<>'. $user->id
					. ' ORDER BY b.'.$chattitle ;
				}	
			}
			else
			{
				// this is for People Touch
				if($chat_config['fonly'])
				{
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", b.username, b.name "
					. ' FROM #__community_userrelations as a, #__users as b , #__session AS s'
					. ' WHERE a.`user_id`='. $user->id
					. ' AND a.`status`=1 '
					. ' AND a.`friend_id`=b.`id` '
					. ' AND b.id=s.userid '
					. ' AND s.client_id = '.(int)$mainframe->isAdmin().' '
					. ' ORDER BY b.'.$chattitle ;
				}
				else
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
				"FROM #__users u, #__session s ".
				"WHERE u.id = s.userid AND s.client_id = ".(int)$mainframe->isAdmin()." AND u.id<> " . $user->id . 
				" ORDER BY u.".$chattitle ;
				}


			}
			


//////////////////////////////////////////////////////////
			//Select Group ID		
		$groupselect =  $params->get( 'groupselect',0 );
		$persongroup = $params->get( 'persongroup',0 );
		$pagegroup =  $params->get( 'pagegroup',0 );
			
		if($persongroup==1 && $groupselect==1 && !($pagegroup==1))
			{
				$memberid=array();
				$memberidlst=array();
				$memberiduniq=array();
				$rows1=array();
	
				$query = "SELECT DISTINCT groupid "
						. ' FROM  #__supergroup_members '
						. ' WHERE memberid='. $user->id ;
				
				$db->setQuery($query);
				$rows	=	$db->loadObjectList();
				
				$i=0;
				foreach ($rows as $row)
				{
					$query = "SELECT DISTINCT memberid "
					. ' FROM  #__supergroup_members, #__session s '
					. ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
					$db->setQuery($query);
					$memberid[$i] = $db->loadObjectList();
					$i++;
				}	
				$i=0;		
				foreach ($memberid as $abc)
				{
					foreach($abc as $ab)
					{
						$memberidlst[$i] = $ab->memberid;
						$i++;
					}
				}
	
				$memberiduniq = array_unique($memberidlst);
				$j=0;
				foreach($memberiduniq as $aa)
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
						"FROM #__users u, #__session s, #__comprofiler b
						WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
						ORDER BY u.".$chattitle ;
						$db->setQuery($query);
						$rows1[$j] = $db->loadObjectList();
						$j++;
				}
				$newrows=array();	
				$j=0;		
				foreach($rows1 as $rown)
				{
					foreach($rown as $row)		
					{		
						$newrows[$j]=$row;
						$j++;
					}
				}
				$rows = $newrows;
			}
////////////////////////////////////////
			else if($pagegroup==1 && $groupselect==1 && !($persongroup==1))
			{
				$task = JRequest::getVar('task');
				$groupid = JRequest::getVar('id');
				if($task=='group')
				{
					$query = "SELECT DISTINCT memberid "
						. ' FROM  #__supergroup_members, #__session s '
						. ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
					$db->setQuery($query);
					$rows = $db->loadObjectList();
					$rows1=array();	
					$j=0;
					foreach($rows as $aa)
					{
						$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
						"FROM #__users u, #__session s, #__comprofiler b
						WHERE u.id=".$aa->memberid." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
						ORDER BY u.".$chattitle ;
							$db->setQuery($query);
							$rows1[$j] = $db->loadObjectList();
							$j++;
					}
					$newrows=array();	
					$j=0;	
					foreach($rows1 as $rown)
					{
						foreach($rown as $row)		
						{		
							$newrows[$j]=$row;
							$j++;
						}
					}
					$rows = $newrows;
				}
				else
				{
					$db->setQuery($query);
					$rows = $db->loadObjectList();
				}
			}
		else if($pagegroup==0 && $persongroup==0)
		{
			$db->setQuery($query);
			$rows = $db->loadObjectList();
		}
///////////////////////////////////////////////////////////////////////////
//////JiveCode
///////////////////////
		if($persongroup==1 && $groupselect==2 && !($pagegroup==1))
		{
			$memberid=array();
			$memberidlst=array();
			$memberiduniq=array();
			$rows1=array();
			$query = "SELECT DISTINCT id_group "
					. ' FROM  #__gj_users '
					. ' WHERE id_user='. $user->id ;
			
			$db->setQuery($query);
			$rows	=	$db->loadObjectList();
			
			$i=0;
			foreach ($rows as $row)
			{
				$query = "SELECT DISTINCT id_user "
				. ' FROM  #__gj_users, #__session s '
				. ' WHERE id_group='. $row->id_group.' AND id_user IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
				$db->setQuery($query);
				$memberid[$i] = $db->loadObjectList();
				$i++;
			}	
			$i=0;		
			foreach ($memberid as $abc)
			{
				foreach($abc as $ab)
				{
					$memberidlst[$i] = $ab->id_user;
					$i++;
				}
			}
			
			$memberiduniq = array_unique($memberidlst);
			$j=0;
			foreach($memberiduniq as $aa)
			{
				$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
					"FROM #__users u, #__session s, #__comprofiler b
					WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
					ORDER BY u.".$chattitle ;
					$db->setQuery($query);
					$rows1[$j] = $db->loadObjectList();
					$j++;
			}
			$newrows=array();	
			$j=0;		
			foreach($rows1 as $rown)
			{
				foreach($rown as $row)		
				{		
					$newrows[$j]=$row;
					$j++;
				}
			}
			$rows = $newrows;
		}
///////////////////////
		else if($pagegroup==1 && $groupselect==2 && !($persongroup==1))
		{	
			$option = JRequest::getVar('option');
			$groupid = JRequest::getVar('groupid');
			
			if($option=='com_groupjive' && !($groupid==null))
			{
				$query = "SELECT DISTINCT id_user "
					. ' FROM  #__gj_users, #__session s '
					. ' WHERE id_group='.$groupid.' AND id_user IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$rows1=array();	
				$j=0;
				foreach($rows as $aa)
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
					"FROM #__users u, #__session s, #__comprofiler b
					WHERE u.id=".$aa->id_user." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
					ORDER BY u.".$chattitle ;
						$db->setQuery($query);
						$rows1[$j] = $db->loadObjectList();
						$j++;
				}
				$newrows=array();	
				$j=0;		
				foreach($rows1 as $rown)
				{
					foreach($rown as $row)		
					{		
						$newrows[$j]=$row;
						$j++;
					}
				}
				$rows = $newrows;
			}
			else
			{
				$db->setQuery($query);
				$rows = $db->loadObjectList();
			}
		}
		else if($pagegroup==0 && $persongroup==0)
		{
			$db->setQuery($query);
			$rows = $db->loadObjectList();
		}

////////////////////////////
//Jomsocial Groups::::::
////////////////////////////	
		if($persongroup==1 && $groupselect==3 && !($pagegroup==1))
		{
			$memberid=array();
			$memberidlst=array();
			$memberiduniq=array();
			$rows1=array();

			$query = "SELECT DISTINCT groupid "
					. ' FROM  #__community_groups_members '
					. ' WHERE memberid='. $user->id ;
			
			$db->setQuery($query);
			$rows	=	$db->loadObjectList();;
			$i=0;
			foreach ($rows as $row)
			{  
				$query = "SELECT DISTINCT memberid "
				. ' FROM  #__community_groups_members, #__session s '
				. ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
				$db->setQuery($query);
				$memberid[$i] = $db->loadObjectList();
				$i++;
			}	
			$i=0;		
			foreach ($memberid as $abc)
			{
				foreach($abc as $ab)
				{
					$memberidlst[$i] = $ab->memberid;
					$i++;
				}
			}

			$memberiduniq = array_unique($memberidlst);
			$j=0;
			foreach($memberiduniq as $aa)
			{  
				$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
					. ' FROM  #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE b.id='.$aa.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = '.(int)$mainframe->isAdmin()
					. ' ORDER BY b.'.$chattitle ;
					$db->setQuery($query);
					$rows1[$j] = $db->loadObjectList();
					$j++;
			}
			$newrows=array();	
			$j=0;		
			foreach($rows1 as $rown)
			{
				foreach($rown as $row)		
				{		
					$newrows[$j]=$row;
					$j++;
				}
			}
			$rows = $newrows;
		}

//////////////////////////////////
		else if($pagegroup==1 && $groupselect==3 && !($persongroup==1))
		{
			$view = JRequest::getVar('view');
			$groupid = JRequest::getVar('groupid');
			if($view=='groups' && !($groupid==null))
			{
				$query = "SELECT DISTINCT memberid "
					. ' FROM  #__community_groups_members, #__session s '
					. ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$rows1=array();	
				$j=0;
				foreach($rows as $aa)
				{	
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
						. ' FROM  #__users as b , #__session AS s , #__community_users cu '
						. ' WHERE b.id='.$aa->memberid.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = '.(int)$mainframe->isAdmin()
						. ' ORDER BY b.'.$chattitle ;
						$db->setQuery($query);
						$rows1[$j] = $db->loadObjectList();					
						$j++;
				}
				$newrows=array();	
				$j=0;		
				foreach($rows1 as $rown)
				{
					foreach($rown as $row)		
					{		
						$newrows[$j]=$row;
						$j++;
					}
				}
					$rows = $newrows;
			}
			else
			{
				$db->setQuery($query);
				$rows = $db->loadObjectList();
			}
		}
		else if($pagegroup==0 && $persongroup==0)
		{
				$db->setQuery($query);
				$rows = $db->loadObjectList();
		}

		if($pagegroup==1 && $persongroup==1)
		{
				$db->setQuery($query);
				$rows = $db->loadObjectList();
		}

				$sql = "SELECT chat_status FROM #__jbolo_status 
						WHERE joom_id = {$user->id}";

						$db->setQuery($sql);
						$chats = $db->loadResult();
						if(!isset($chats))
						{
							$chats=1;
							$chatstat = new stdClass();
							$chatstat->joom_id = $user->id;
							$chatstat->chat_status = $chats;
							$db->insertObject('#__jbolo_status', $chatstat);
						}
						if($chats==0)
						{
							$text_status= JText::_('INVISIBLE_TEXT');
						}
						elseif($chats==1)
						{
							$text_status= JText::_('AVAILABLE_TEXT');
						}
						elseif($chats==2)
						{
							$text_status= JText::_('AWAY_TEXT');
						}
						elseif($chats==3)
						{
							$statusqry = "SELECT status_msg FROM #__jbolo_status 
						WHERE joom_id = {$user->id}";
							$db->setQuery($statusqry);
							$text_status = $db->loadResult();
						}
					$chatuser	.= "<div id='chbox-holder'>			
								<div class='ch-box-tl'>
								<div class='ch-box-tr' onclick=showchatdiv();><div id=down-arrow>	".Jtext::_('MOD_CHAT_TITLE')."</div></div></div>
								<div id='ch_box_status'>
								<a style='display:block;' onclick='chat_status(1);'>".JText::_('AVAILABLE_TEXT')."</a>
								<a style='display:block;' onclick='chat_status(2);'>".JText::_('AWAY_TEXT')."</a>
								<a style='display:block;' onclick='chat_status(0);'>".JText::_('INVISIBLE_TEXT')."</a>
								<a style='display:block;' onclick='jb_show_prompt();'>".JText::_('CUSTOM_TEXT')."</a>
								</div>							
								<div class='ch-box-mid'><div class='ch-box-statusbox'><span>"
								.JText::_('YOUR_STATUS')."</span>
								<span id=inside-ch-box-tl>$text_status</span></div>
								<ul id='jbusers'>";
					if($chats==0)
					{
					$chatuser .=	"<li class='logeduser img_off'>".$user->$chattitle."</li>"; 
					}
					elseif($chats==1)
					{
					$chatuser .=	"<li class='logeduser img_green'>".$user->$chattitle."</li>"; 
					}
					elseif($chats==2)
					{
					$chatuser .=	"<li class='logeduser img_green'>".$user->$chattitle."</li>"; 
					}
					elseif($chats==3)
					{
						$chatuser .=	"<li class='logeduser img_green'>".$user->$chattitle."</li>"; 
					}
			

			$itemid	= JRequest::getVar('Itemid');

			$count	= count($rows);
			$i=1;
			
			if($count)
			foreach ($rows as $row)
				if( $row->id != $user->id )
				{
					$sql = "SELECT chat_status FROM #__jbolo_status 
					WHERE joom_id = {$row->id}";
					$db->setQuery($sql);
					$chats = $db->loadResult();
					if($chats==0)
					{
						continue;
					}
					if( $chat_config['community']==1 || $chat_config['community']!=2 )
					{
						$img	= '';
						if($chat_config['community']==1){
							$a	=	'<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_comprofiler&&task=userProfile&user='.$row->id.'&Itemid='.$itemid.'">';
							if(isset($row->avatar))
								$img	= $a.'<img src="images/comprofiler/'.$row->avatar.'" width="120" height="90" alt="'.$row->username.'" ></a>';	
							else
								$img	= $a.'<img src="components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png" alt="'.$row->username.'"></a>';
											
						}	
						else if(!$chat_config['community']) 
						{
							$a	= '<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_community&view=profile&userid='.$row->id.'&Itemid='.$itemid.'">';
							$img= $a.'<img src="'.$row->thumb.'" alt="'.$row->username.'"></a>';
						}						
						
						if($chats==2)
							{
								$chatuser	.= "<li id=jb_user_".$row->id."><a class='tt img_red' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							}
							else
							{
								$chatuser	.= "<li id=jb_user_".$row->id."><a class='tt img_green' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							}
										
										
						}
					else
					{	//this is for stand alone
						/* Changes for status icons*/
						if($chats==2)
							{
								$chatuser	.= "<li id=jb_user_".$row->id."><a class='tt img_red' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							}
							else
							{
								$chatuser	.= "<li id=jb_user_".$row->id."><a class='tt img_green' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							}
						/* END Changes for status icons*/

					//	$chatuser	.= "<li id=jb_user_".$row->id."><a class='tt' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
					}
				}
				
				if($user && !$rows)
					$chatuser	.= "<div class='onoffmsg'>".Jtext::_('MOD_ONLINE_MSG')."</div>";
									
					$chatuser .="</ul></div></div>";
		}
		else
			$chatuser	.= Jtext::_('MOD_OFFLINE_MSG');

		if($count>5)	$chatuser	= "
						<style type='text/css'>
div.jb_chat_scroll { overflow: auto;
height:200px }
</style>

<!--[if IE]>
<style type='text/css'>
div.jb_chat_scroll { overflow: hidden;position:relative; overflow-x:auto; overflow-y:auto; padding-bottom:15px; }
</style>
<![endif]-->
						<div class='jb_chat_scroll' style=''>" . $chatuser . "</div>";
	return $chatuser;
	}



//For FB List
function getNameArray(&$params)
	{
		require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
		global $mainframe;
		$count		= '';
		$chatuser	= '';
		$chattitle	= '';
		$rows		= '';
		$user =& JFactory::getUser();	
		$doc =& JFactory::getDocument();
	
		if($chat_config['chatusertitle'])
			$chattitle	= 'username';
		else
			$chattitle	= 'name';

		if ($user->id) { 

			$db	= JFactory::getDBO();
			if($chat_config['community']==1)
			{ //this is for community builder
				if($chat_config['fonly'])
				{
					$query="SELECT DISTINCT a.id, u.".$chattitle.", b.avatar, u.name, u.username
					FROM
					(
					SELECT DISTINCT u.id 
					FROM jos_users u, jos_session s, jos_comprofiler_members a 
					LEFT JOIN jos_comprofiler b ON a.memberid = b.user_id 
					WHERE a.referenceid=".$user->id." 
					AND u.id = a.memberid 
					AND a.memberid IN ( s.userid ) 
					AND (a.accepted=1) 
					AND s.".(int)$mainframe->isAdmin()."
					AND u.block=0
					ORDER BY u.username
					) 
					AS a
					LEFT JOIN jos_comprofiler b ON b.user_id = a.id
					LEFT JOIN jos_comprofiler_members AS c on c.referenceid=a.id
					LEFT JOIN jos_users AS u ON u.id=a.id
					LEFT JOIN jos_session AS s ON s.userid=a.id 
					WHERE c.memberid=".$user->id."
					AND c.accepted=1
					AND b.banned=0
					AND s.client_id = ".(int)$mainframe->isAdmin()."
					ORDER BY u.".$chattitle ;
				}
				else
				{
				$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
				"FROM #__users u, #__session s, #__comprofiler b
				WHERE u.id=b.user_id AND u.id IN ( s.userid ) AND u.id<> " . $user->id . " AND s.client_id = ".(int)$mainframe->isAdmin()." ORDER BY u.".$chattitle ;
				
				}	
				
			}
			else if( $chat_config['community']==2 )
			{
				//standalone
				$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
				"FROM #__users u, #__session s ".
				"WHERE u.id = s.userid AND s.client_id = ".(int)$mainframe->isAdmin()." AND u.id<> " . $user->id . 
				" ORDER BY u.".$chattitle ;
			}
			else if( $chat_config['community']==0 )
			{ 
			// this is for jomsocial community 
				if($chat_config['fonly'])
				{
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
					. ' FROM #__community_connection as a, #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE a.`connect_from`='. $user->id
					. ' AND cu.userid=a.connect_to '
					. ' AND a.`status`=1 '
					. ' AND a.`connect_to`=b.`id` '
					. ' AND b.id=s.userid '
					. ' AND s.client_id = '.(int)$mainframe->isAdmin()
					. ' ORDER BY b.'.$chattitle ;
				}
				else
				{
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
					. ' FROM  #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = '.(int)$mainframe->isAdmin().'  AND b.id<>'. $user->id
					. ' ORDER BY b.'.$chattitle ;
				}	
			}
			else
			{
				// this is for People Touch
				if($chat_config['fonly'])
				{	
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", b.username, b.name "
					. ' FROM #__community_userrelations as a, #__users as b , #__session AS s'
					. ' WHERE a.`user_id`='. $user->id
					. ' AND a.`status`=1 '
					. ' AND a.`friend_id`=b.`id` '
					. ' AND b.id=s.userid '
					. ' AND s.client_id = '.(int)$mainframe->isAdmin()
					. ' ORDER BY b.'.$chattitle ;
				}
				else
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
				"FROM #__users u, #__session s ".
				"WHERE u.id = s.userid AND s.client_id = ".(int)$mainframe->isAdmin()." AND u.id<> " . $user->id . 
				" ORDER BY u.".$chattitle ;
				}


			}

//////////////////////////////////////////////////////////
			//Select Group ID		
		$groupselect =  $params->get( 'groupselect',0 );
		$persongroup = $params->get( 'persongroup',0 );
		$pagegroup =  $params->get( 'pagegroup',0 );
			
		if($persongroup==1 && $groupselect==1 && !($pagegroup==1))
			{
				$memberid=array();
				$memberidlst=array();
				$memberiduniq=array();
				$rows1=array();
	
				$query = "SELECT DISTINCT groupid "
						. ' FROM  #__supergroup_members '
						. ' WHERE memberid='. $user->id ;
				
				$db->setQuery($query);
				$rows	=	$db->loadObjectList();
				
				$i=0;
				foreach ($rows as $row)
				{
					$query = "SELECT DISTINCT memberid "
					. ' FROM  #__supergroup_members, #__session s '
					. ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
					$db->setQuery($query);
					$memberid[$i] = $db->loadObjectList();
					$i++;
				}	
				$i=0;		
				foreach ($memberid as $abc)
				{
					foreach($abc as $ab)
					{
						$memberidlst[$i] = $ab->memberid;
						$i++;
					}
				}
	
				$memberiduniq = array_unique($memberidlst);
				$j=0;
				foreach($memberiduniq as $aa)
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
						"FROM #__users u, #__session s, #__comprofiler b
						WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
						ORDER BY u.".$chattitle ;
						$db->setQuery($query);
						$rows1[$j] = $db->loadObjectList();
						$j++;
				}
				$newrows=array();	
				$j=0;		
				foreach($rows1 as $rown)
				{
					foreach($rown as $row)		
					{		
						$newrows[$j]=$row;
						$j++;
					}
				}
				$rows = $newrows;
			}
////////////////////////////////////////
			else if($pagegroup==1 && $groupselect==1 && !($persongroup==1))
			{
				$task = JRequest::getVar('task');
				$groupid = JRequest::getVar('id');
				if($task=='group')
				{
					$query = "SELECT DISTINCT memberid "
						. ' FROM  #__supergroup_members, #__session s '
						. ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
					$db->setQuery($query);
					$rows = $db->loadObjectList();
					$rows1=array();	
					$j=0;
					foreach($rows as $aa)
					{
						$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
						"FROM #__users u, #__session s, #__comprofiler b
						WHERE u.id=".$aa->memberid." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
						ORDER BY u.".$chattitle ;
							$db->setQuery($query);
							$rows1[$j] = $db->loadObjectList();
							$j++;
					}
					$newrows=array();	
					$j=0;	
					foreach($rows1 as $rown)
					{
						foreach($rown as $row)		
						{		
							$newrows[$j]=$row;
							$j++;
						}
					}
					$rows = $newrows;
				}
				else
				{
					$db->setQuery($query);
					$rows = $db->loadObjectList();
				}
			}
		else if($pagegroup==0 && $persongroup==0)
		{
			$db->setQuery($query);
			$rows = $db->loadObjectList();
		}
///////////////////////////////////////////////////////////////////////////
//////JiveCode
///////////////////////
		if($persongroup==1 && $groupselect==2 && !($pagegroup==1))
		{
			$memberid=array();
			$memberidlst=array();
			$memberiduniq=array();
			$rows1=array();
			$query = "SELECT DISTINCT id_group "
					. ' FROM  #__gj_users '
					. ' WHERE id_user='. $user->id ;
			
			$db->setQuery($query);
			$rows	=	$db->loadObjectList();
			
			$i=0;
			foreach ($rows as $row)
			{
				$query = "SELECT DISTINCT id_user "
				. ' FROM  #__gj_users, #__session s '
				. ' WHERE id_group='. $row->id_group.' AND id_user IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
				$db->setQuery($query);
				$memberid[$i] = $db->loadObjectList();
				$i++;
			}	
			$i=0;		
			foreach ($memberid as $abc)
			{
				foreach($abc as $ab)
				{
					$memberidlst[$i] = $ab->id_user;
					$i++;
				}
			}
			
			$memberiduniq = array_unique($memberidlst);
			$j=0;
			foreach($memberiduniq as $aa)
			{
				$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
					"FROM #__users u, #__session s, #__comprofiler b
					WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
					ORDER BY u.".$chattitle ;
					$db->setQuery($query);
					$rows1[$j] = $db->loadObjectList();
					$j++;
			}
			$newrows=array();	
			$j=0;		
			foreach($rows1 as $rown)
			{
				foreach($rown as $row)		
				{		
					$newrows[$j]=$row;
					$j++;
				}
			}
			$rows = $newrows;
		}
///////////////////////
		else if($pagegroup==1 && $groupselect==2 && !($persongroup==1))
		{	
			$option = JRequest::getVar('option');
			$groupid = JRequest::getVar('groupid');
			
			if($option=='com_groupjive' && !($groupid==null))
			{
				$query = "SELECT DISTINCT id_user "
					. ' FROM  #__gj_users, #__session s '
					. ' WHERE id_group='.$groupid.' AND id_user IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$rows1=array();	
				$j=0;
				foreach($rows as $aa)
				{
					$query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
					"FROM #__users u, #__session s, #__comprofiler b
					WHERE u.id=".$aa->id_user." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = ".(int)$mainframe->isAdmin()."
					ORDER BY u.".$chattitle ;
						$db->setQuery($query);
						$rows1[$j] = $db->loadObjectList();
						$j++;
				}
				$newrows=array();	
				$j=0;		
				foreach($rows1 as $rown)
				{
					foreach($rown as $row)		
					{		
						$newrows[$j]=$row;
						$j++;
					}
				}
				$rows = $newrows;
			}
			else
			{
				$db->setQuery($query);
				$rows = $db->loadObjectList();
			}
		}
		else if($pagegroup==0 && $persongroup==0)
		{
			$db->setQuery($query);
			$rows = $db->loadObjectList();
		}

////////////////////////////
//Jomsocial Groups::::::
////////////////////////////	
		if($persongroup==1 && $groupselect==3 && !($pagegroup==1))
		{
			$memberid=array();
			$memberidlst=array();
			$memberiduniq=array();
			$rows1=array();

			$query = "SELECT DISTINCT groupid "
					. ' FROM  #__community_groups_members '
					. ' WHERE memberid='. $user->id ;
			
			$db->setQuery($query);
			$rows	=	$db->loadObjectList();;
			$i=0;
			foreach ($rows as $row)
			{  
				$query = "SELECT DISTINCT memberid "
				. ' FROM  #__community_groups_members, #__session s '
				. ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
				$db->setQuery($query);
				$memberid[$i] = $db->loadObjectList();
				$i++;
			}	
			$i=0;		
			foreach ($memberid as $abc)
			{
				foreach($abc as $ab)
				{
					$memberidlst[$i] = $ab->memberid;
					$i++;
				}
			}

			$memberiduniq = array_unique($memberidlst);
			$j=0;
			foreach($memberiduniq as $aa)
			{  
				$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
					. ' FROM  #__users as b , #__session AS s , #__community_users cu '
					. ' WHERE b.id='.$aa.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = '.(int)$mainframe->isAdmin()
					. ' ORDER BY b.'.$chattitle ;
					$db->setQuery($query);
					$rows1[$j] = $db->loadObjectList();
					$j++;
			}
			$newrows=array();	
			$j=0;		
			foreach($rows1 as $rown)
			{
				foreach($rown as $row)		
				{		
					$newrows[$j]=$row;
					$j++;
				}
			}
			$rows = $newrows;
		}

//////////////////////////////////
		else if($pagegroup==1 && $groupselect==3 && !($persongroup==1))
		{
			$view = JRequest::getVar('view');
			$groupid = JRequest::getVar('groupid');
			if($view=='groups' && !($groupid==null))
			{
				$query = "SELECT DISTINCT memberid "
					. ' FROM  #__community_groups_members, #__session s '
					. ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = '.(int)$mainframe->isAdmin() ;
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$rows1=array();	
				$j=0;
				foreach($rows as $aa)
				{	
					$query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
						. ' FROM  #__users as b , #__session AS s , #__community_users cu '
						. ' WHERE b.id='.$aa->memberid.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = '.(int)$mainframe->isAdmin()
						. ' ORDER BY b.'.$chattitle ;
						$db->setQuery($query);
						$rows1[$j] = $db->loadObjectList();					
						$j++;
				}
				$newrows=array();	
				$j=0;		
				foreach($rows1 as $rown)
				{
					foreach($rown as $row)		
					{		
						$newrows[$j]=$row;
						$j++;
					}
				}
					$rows = $newrows;
			}
			else
			{
				$db->setQuery($query);
				$rows = $db->loadObjectList();
			}
		}
		else if($pagegroup==0 && $persongroup==0)
		{
				$db->setQuery($query);
				$rows = $db->loadObjectList();
		}

		if($pagegroup==1 && $persongroup==1)
		{
				$db->setQuery($query);
				$rows = $db->loadObjectList();
		}


			$chatuser	.= 	"<ul id='jfbusers'>";

			$itemid	= JRequest::getVar('Itemid');

			$count	= count($rows);
			$i=1;
			if($count>0)
			foreach ($rows as $row)
				if( $row->id != $user->id )
				{
					$sql = "SELECT chat_status FROM #__jbolo_status 
					WHERE joom_id = {$row->id}";
					$db->setQuery($sql);
					$chats = $db->loadResult();
					if($chats==0)
					{
						continue;
					}
					if( $chat_config['community']==1 || $chat_config['community']!=2 )
					{
						$img	= '';
						if($chat_config['community']==1){
							$a	=	'<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_comprofiler&&task=userProfile&user='.$row->id.'&Itemid='.$itemid.'">';
							if(isset($row->avatar))
								$img	= $a.'<img src="images/comprofiler/'.$row->avatar.'" width="120" height="90" alt="'.$row->username.'" ></a>';	
							else
								$img	= $a.'<img src="components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png" alt="'.$row->username.'"></a>';
											
						}	
						else if(!$chat_config['community']) 
						{
							$a	= '<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_community&view=profile&userid='.$row->id.'&Itemid='.$itemid.'">';
							$img= $a.'<img src="'.$row->thumb.'" alt="'.$row->username.'"></a>';
						}						
						
						//$chatuser	.= "<li id=jfb_user_".$row->id."><a class='jfb_anchor' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
						/* Changes for status icons*/
						if($chats==2)
							{
								$chatuser	.= "<li id=jb_user_".$row->id."><a class='jfb_anchor img_red' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							}
							else
							{
								$chatuser	.= "<li id=jb_user_".$row->id."><a class='jfb_anchor img_green' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							}
						/* END Changes for status icons*/				
										
						}
					else
					{	//this is for stand alone
						/* Changes for status icons*/
						if($chats==2)
							{
								$chatuser	.= "<li id=jb_user_".$row->id."><a class='jfb_anchor img_red' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							}
							else
							{
								$chatuser	.= "<li id=jb_user_".$row->id."><a class='jfb_anchor img_green' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
							}
						/* END Changes for status icons*/

						//$chatuser	.= "<li id=jfb_user_".$row->id."><a class='jfb_anchor' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
					}
				}
				
				if($user && !$rows)
					$chatuser	.= "<div class='onoffmsg'>".Jtext::_('MOD_ONLINE_MSG')."</div>";
									
					$chatuser .="</ul>";
		}
		else
			$chatuser	.= Jtext::_('MOD_OFFLINE_MSG');

		/*if($count>5)	$chatuser	= "
						<style type='text/css'>
div.jb_chat_scroll { overflow: auto;
height:200px }
</style>

<!--[if IE]>
<style type='text/css'>
div.jb_chat_scroll { overflow: hidden;position:relative; overflow-x:auto; overflow-y:auto; padding-bottom:15px; }
</style>
<![endif]-->
						<div class='jb_chat_scroll' style=''>" . $chatuser . "</div>";*/
	return $chatuser;
	}



function getStatus()
{
	$user =& JFactory::getUser();
	$db	= JFactory::getDBO();	
	$sql = "SELECT chat_status FROM #__jbolo_status WHERE joom_id = {$user->id}";
	$db->setQuery($sql);
	$chats = $db->loadResult();
	if(!isset($chats))
	{
		$chats=1;
		$chatstat = new stdClass();
		$chatstat->joom_id = $user->id;
		$chatstat->chat_status = $chats;
		$db->insertObject('#__jbolo_status', $chatstat);
	}
	return $chats;
}

function getCustomStatus()
{
	$user =& JFactory::getUser();
	$db	= JFactory::getDBO();	
	$sql = "SELECT status_msg FROM #__jbolo_status WHERE joom_id = {$user->id}";
	$db->setQuery($sql);
	return $db->loadResult();
}	

}
