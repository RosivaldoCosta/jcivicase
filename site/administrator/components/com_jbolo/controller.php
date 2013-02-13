<?php 
/**
 * @This component is to be converted from
 * joomla1.o to 1.5 This is the file where 
 * the control come after calling by main file 
 * in this component main file is invitex.php;
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class JboloController extends JController
{	
	function display()
	{
		global $mainframe;

		$vName = JRequest::getCmd('view', 'cp');
		$controllerName = JRequest::getCmd( 'controller', 'cp' );
		$cp		=	'';
		$config		=	'';

		switch($vName)
		{
			case 'config':
				$config	=	true;
			break;
			case 'cp':
				$cp	=	true;
			break;
		}
		JSubMenuHelper::addEntry(JText::_('MENU_TITLE3'), 'index.php?option=com_jbolo',$cp);
		JSubMenuHelper::addEntry(JText::_('MENU_TITLE2'), 'index.php?option=com_jbolo&view=config',$config);


		switch ($vName)
		{
			case 'config':
				$mName = 'config';
				$vLayout = JRequest::getCmd( 'layout', 'config' );
			break;
			case 'cp':
			default:
				$vName = 'cp';
				$vLayout = JRequest::getCmd( 'layout', 'default' );
				$mName = 'cp';
			break;
		}
		
		//$vLayout = JRequest::getCmd( 'layout', 'cp' );
		$mName = 'cp';

		$document = &JFactory::getDocument();
		$vType	  = $document->getType();

		// Get/Create the view
		$view = &$this->getView( $vName, $vType);

		// Get/Create the model
		if ($model = &$this->getModel($mName)) {
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}

		// Set the layout
		$view->setLayout($vLayout);

		// Display the view
		$view->display();
	}

	function getVersion()
	{
		echo $recdata = file_get_contents('http://techjoomla.com/vc/index.php?key=abcd1234&product=jbolo');
		jexit();
	}	
	
    function chatheartbeat() {
        require(str_replace('administrator'.DS, '', JPATH_COMPONENT).DS."config".DS."config.php");

        global $mainframe;
        $app       = & JFactory::getApplication();
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();
        if (!$user->id) { 
        echo json_encode(array("login"=>0));
        jexit();
        }

    
        $log_tim = JRequest::getVar('logtim');
        $session_life      = $app->getCfg('lifetime');

        if($log_tim>=$session_life)
        {
        $mainframe->logout();
        $logout = array('logout'=>1);
        echo json_encode($logout);
        jexit();
        }

        $tstamp = JRequest::getVar('tstamp');
        $lststamp = JRequest::getVar('lststamp');

        /*$sql = "SELECT * FROM #__jbolo AS c 
        WHERE c.to = {$user->id} AND c.sent BETWEEN FROM_UNIXTIME($lststamp) AND FROM_UNIXTIME($tstamp) ORDER by id ASC";*/

        $sql = "SELECT * FROM #__jbolo AS c 
        WHERE c.to = {$user->id} AND recd = 0 
        ORDER by id ASC";
        $db->setQuery($sql);
        $chats = $db->loadObjectList();

        //Add to 2.9.0 Beta
        /*$sql = "SELECT a.* FROM #__jbolo AS a, #__jbolo_group as b, #__jbolo_group_xref AS c WHERE LENGTH(`to`)>6 AND a.to = b.chatroom_id AND b.userid = {$user->id} AND c.userid = {$user->id} AND c.jid = a.id AND b.status=1 AND c.recd= 0 ORDER by id ASC";
        $db->setQuery($sql);
        $gchats = $db->loadObjectList();    
        if(is_array($gchats))   
            $chats = array_merge($chats, $gchats);*/

        $items = '';
        
        $chatBoxes = array();
        
        $listajax = '';
        $newcountuserno = '';
        $userno = JRequest::getVar('userno');
        //If FB is selected in the backend
        jimport( 'joomla.application.module.helper' );

        if(JModuleHelper::getModule( 'jbolo' ))
        {
            $module = JModuleHelper::getModule( 'jbolo' );
            $moduleParams = new JParameter( $module->params );
            $limit = intval($moduleParams->get( 'modorbar', 1 ));
            if($limit==0)
            {
                $listajax = $this->getNewFBList();
            }
            else
            {
                $listajax = $this->getNewList();
            }

            $listajax = str_replace(array("\r", "\r\n", "\n"),'',$listajax ); 
            $countuserno = explode("<li",$listajax);

            $newcountuserno = sizeof($countuserno)-1;
        }
        

        foreach ($chats as $chat) {
            if(is_numeric($chat->from)) {
                if(strlen($chat->to)>5) {
                    $udetails = JFactory::getUser($chat->from);
                    $chat->from = $chat->to;            
                }           
                else {      
                    $udetails = JFactory::getUser($chat->from);
                }

                if($chat_config['chatusertitle'])
                    $chatfrom= $udetails->username;//$details->name;
                else
                    $chatfrom= $udetails->name;//$details->username;

                if (!isset($_SESSION['openChatBoxes'][$chat->from]) && isset($_SESSION['chatHistory'][$chat->from])) {
                    $items = $_SESSION['chatHistory'][$chat->from];
                }
            }
            else
            {
                $chat->message = $this->sanitize($chat->message);
                $says = JText::_('says');
                $items .= <<<EOD
    {"s": "2",
    "f": "$chat->to",
    "show": "{$chat->from}",
    "m": "{$chat->message}"},
EOD;
            continue;
            }

            $chat->message = $this->sanitize($chat->message);
            $says = JText::_('says');

            if(JModuleHelper::getModule( 'jbolo' ))
            {
                $module = JModuleHelper::getModule( 'jbolo' );
                $moduleParams = new JParameter( $module->params );
                $limit = intval($moduleParams->get( 'intensiveupdate', 0 ));
                if($limit==0)
                {
                    if($newcountuserno != $userno)
                    {
                    $items .= <<<EOD
                        {"s": "0",
                        "f": "{$chat->from}",
                        "show": "{$chatfrom}",
                        "m": "{$chat->message}",
                        "lst": "{$listajax}"},
EOD;
                    }
                    else
                    {
                    $items .= <<<EOD
                        {"s": "0",
                        "f": "{$chat->from}",
                        "show": "{$chatfrom}",
                        "m": "{$chat->message}"},
EOD;
                    }
                }
                else
                {
                $items .= <<<EOD
                        {"s": "0",
                        "f": "{$chat->from}",
                        "show": "{$chatfrom}",
                        "m": "{$chat->message}"},
EOD;
                }
            }
            else
            {
                $items .= <<<EOD
                        {"s": "0",
                        "f": "{$chat->from}",
                        "show": "{$chatfrom}",
                        "m": "{$chat->message}"},
EOD;
            }

        if (!isset($_SESSION['chatHistory'][$chat->from])) {
            $_SESSION['chatHistory'][$chat->from] = '';
        }
        else
        $_SESSION['chatHistory'][$chat->from] .= <<<EOD
                                 {"s": "0",
                "f": "{$chat->from}",
                "show": "{$chatfrom}",
                "m": "{$chat->message}"},
EOD;
        
            unset($_SESSION['tsChatBoxes'][$chat->from]);
            $_SESSION['openChatBoxes'][$chat->from] = $chat->sent;
        }

        if (!empty($_SESSION['openChatBoxes'])) {
        foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
            if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
                $now = time()-strtotime($time);
                $message = sprintf(JText::_('Sent at'),JFactory::getDate($time)->toFormat(JText::_("SENT_AT_FORMAT")));

                if ($now>180) {

                        $items .= <<<EOD
        {"s": "2",
        "f": "$chatbox",
        "show": "{$chatfrom}",
        "m": "{$message}",
    "silent":"1"

                    },
EOD;

        if (!isset($_SESSION['chatHistory'][$chatbox])) {
            $_SESSION['chatHistory'][$chatbox] = '';
        }

        $_SESSION['chatHistory'][$chatbox] .= <<<EOD
            {"s": "2",
    "f": "$chatbox",
    "show": "{$chatfrom}",
    "m": "{$message}"},
EOD;
                $_SESSION['tsChatBoxes'][$chatbox] = 1;
            }
            }
        }
    }

        $sql = "UPDATE #__jbolo AS c 
        SET recd = 1 
        WHERE c.to = {$user->id} AND recd = 0";
        $db->setQuery($sql);
        $db->query();
        //Add to 2.9.0 Beta
        /*
        $sql = "UPDATE #__jbolo AS a, #__jbolo_group_xref AS b
        SET b.recd = 1 
        WHERE a.id = b.jid AND b.userid = {$user->id} AND b.recd = 0";
        $db->setQuery($sql);
        $db->query();*/

        if ($items != '') {
            $items = substr($items, 0, -1);
        }
        else
        {
            if(JModuleHelper::getModule( 'jbolo' ))
            {
                $module = JModuleHelper::getModule( 'jbolo' );
                $moduleParams = new JParameter( $module->params );
                $limit = intval($moduleParams->get( 'intensiveupdate', 0 ));
                if($limit==1)
                {
                    if($newcountuserno != $userno)
                    {
                        $items .= <<<EOD
                        {
                            "lst": "{$listajax}"
                        },
EOD;
                    }
                }
            }
        }

    if($items!=null)
    {
        header('Content-type: application/json');
        ?>
        {"l":[<?php echo $items;?>]}
    
    <?php
    }
                jexit();
    }
    
    function getinfo() {
        require(str_replace('administrator'.DS, '', JPATH_COMPONENT).DS."config".DS."config.php");
        $uid = JRequest::getInt('uid');
        $details = JFactory::getUser($uid);
        if($chat_config['chatusertitle'])
        $chattitle= $details->username;//$details->name;
        else
        $chattitle= $details->name;//$details->username;

        //** Edited for thumbnails  Start **//
        $thumbdetails = '';

        $db = &JFactory::getDBO();
        $sql = "SELECT chat_status FROM #__jbolo_status 
        WHERE joom_id = {$uid}";
        $db->setQuery($sql);
        $status = $db->loadResult();
        $cmsg = '';     
        if($status==3)
        {
            $sql = "SELECT status_msg FROM #__jbolo_status WHERE joom_id = {$uid}";
            $db->setQuery($sql);
            $cmsg = addslashes($db->loadResult());
        }

        if($chat_config['community']==0)
        {
            if(! class_exists('CFactory'))
            {
              require_once( str_replace(DS.'administrator', '', JPATH_BASE) . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');    
            }
        $tempuser = & CFactory::getUser( $details->id );
        $thumbdetails = $tempuser->getThumbAvatar();
        }
        elseif($chat_config['community']==1)
        {
            $database = &JFactory::getDBO();
            $q="SELECT avatar, avatarapproved FROM #__comprofiler WHERE user_id=$uid";
            $database->setQuery($q);
            $usera= $database->loadObject();
                     
            $img_path = JURI::base() . "images/comprofiler";
                     
            if($usera->avatar && $usera->avatarapproved)
            {
                if(substr_count($usera->avatar, "/") == 0)
                {
                    $thumbdetails = $img_path . '/tn' . $usera->avatar;
                }
                else
                {
                    $thumbdetails = $img_path . '/' . $usera->avatar;
                }
            }
            elseif ($usera->avatar)
            {
                $thumbdetails = JURI::base()."components/com_comprofiler/plugin/language/default_language/images/tnpendphoto.jpg";
            }
             else
            {
                $thumbdetails = JURI::base()."components/com_comprofiler/plugin/language/default_language/images/tnnophoto.jpg";
            }
        }       
        //** Edited for thumbnails  End **//
        
        //Add to 2.9.0 Beta
        /*if(strlen($uid)>6) {  
            if($chat_config['chatusertitle'])
                $chatname = 'username';
            else
                $chatname = 'name';

            $query = "SELECT u.".$chatname." FROM #__users as u, #__jbolo_group as g WHERE g.chatroom_id=".$uid." AND g.status = 1 AND u.id = g.userid";
            $db->setQuery($query);  
            $usrlist = $db->loadResultArray();
            $chattitle= JText::_('GROUP_CHAT').' '.implode(',',$usrlist); 
        }*/

        header('Content-type: application/json');
        ?>
        {
            "id": "<?php echo $details->id ;?>",
            "name": "<?php  echo $chattitle;?>",
            "username": "<?php echo $details->username; ?>",
            "status": "<?php echo $status; ?>",
            "cmsg": "<?php echo $cmsg; ?>",
            "thumb" : "<?php echo $thumbdetails; ?>"
        }
        <?php
        jexit();
    }
    
    function sendchat() {

        $user =& JFactory::getUser();
        if (!$user->id) {   echo json_encode(array("login"=>0));jexit(); }
        
        $db =& JFactory::getDBO();
        $from = $user->id;
        $to = JRequest::getVar('to');
        $message = JRequest::getVar('message');
        
        /* Bad Words Start*/
        if($message != '')
        {   
            require(str_replace('administrator'.DS, '', JPATH_COMPONENT).DS."config".DS."config.php");
            $badwords = str_replace(' ', '', $chat_config['badwords']);
            if($badwords!=null)
            {
                $badwords = explode(",",$badwords);
                for($i = 0; $i<sizeof($badwords); $i++)
                {
                    $badwords[$i] = '/'.$badwords[$i].'/i';
                }
                $replacement = '****';
                $message = preg_replace($badwords, $replacement, $message);
            }
        }
        /* Bad Words End*/

        $_SESSION['openChatBoxes'][$to] = date('Y-m-d H:i:s', time());
        $messagesan = $this->sanitize($message);

        if (!isset($_SESSION['chatHistory'][$to])) {
            $_SESSION['chatHistory'][$to] = '';
        }

        $_SESSION['chatHistory'][$to] .= <<<EOD
            {
                "s": "1",
                "f": "{$to}",
                "m": "{$messagesan}"
             },
EOD;


        unset($_SESSION['tsChatBoxes'][$to]);
        if(strlen($to)>6) {
            $this->groupPush($from,$to,$message);
        }
        else {
            $chat = new stdClass();
            $chat->from = $from;
            $chat->to = $to;
            $chat->message = $message;
            $chat->sent = date('Y-m-d H:i:s');
            $db->insertObject('#__jbolo', $chat);
        }

        $sql = "SELECT * FROM #__session
        WHERE userid = {$to}";
        $db->setQuery($sql);
        $chats = $db->loadResult();
        echo JText::_('me');
        if(strlen($to)>6)
            jexit();

        if($chats==null)
        {
            echo "0";
        }
        else
        {
            $sql = "SELECT chat_status FROM #__jbolo_status
            WHERE joom_id = {$to}";
            $db->setQuery($sql);
            $chat = $db->loadResult();
            if($chat==0)
            {
                echo "0";
            }
            elseif($chat==2)
            {
                echo "2";
            }
        }

        jexit();
    }
    
    function sanitize($text) {

        $text = str_replace("\n\r","\n",$text);
        $text = str_replace("\r\n","\n",$text);
        $text = str_replace("\n","<br>",$text);
        $text = addslashes( $text );
    
        return $text;
    }
    
    function closechat() {
        //If FB is selected in the backend
        jimport( 'joomla.application.module.helper' );
        $user = JFactory::getUser();
        $chatboxid = JRequest::getVar('chatbox');
        $chattitle = $this->getAccessName();
        $db = JFactory::getDBO();
        if(JModuleHelper::getModule( 'jbolo' ))
        {
            $module = JModuleHelper::getModule( 'jbolo' );
            $moduleParams = new JParameter( $module->params );
            $limit = intval($moduleParams->get( 'modorbar', 1 ));
            //0 for fb:1 for JBolo
            if(!($limit==0))
            {
                unset($_SESSION['openChatBoxes'][$chatboxid]);
            }
        }
        else
        {
            unset($_SESSION['openChatBoxes'][$chatboxid]);
        }

        if(strlen($chatboxid)>6)
        {
            //Add to 2.9.0 Beta
            /*
            $sql = "UPDATE #__jbolo_group SET status = 0 WHERE chatroom_id=".$chatboxid." AND userid=".$user->id;
            $db->setQuery($sql);
            $db->query();

            $this->groupPush('GroupNotifier',$chatboxid,JFactory::getUser($user->id)->$chattitle.' '.JText::_('HAS_LEFT'));*/
        }

        echo "1";
        jexit();
    }
    
    function clearchat() {
        $chatboxid = JRequest::getVar('chatbox');
        unset($_SESSION['openChatBoxes'][$chatboxid]);
        echo "1";
        jexit();
    }
    
    //to get username or name according to config.
    function getAccessName()
    {
        require(str_replace('administrator'.DS, '', JPATH_COMPONENT).DS."config".DS."config.php");
        if($chat_config['chatusertitle'])
            return $chattitle= 'username';//$details->name;
        else
            return $chattitle= 'name';
    }
    
    function startchatsession() {

        $user =& JFactory::getUser();
        if (!$user->id) { return; }

        $items = '';
    
        if (!empty($_SESSION['openChatBoxes'])) {
            foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
                $items .= $this->chatboxsession($chatbox);
            }
        }


        if ($items != '') {
            $items = substr($items, 0, -1);
        }

    $user->name = str_replace(array("\r", "\r\n", "\n"),'',$user->name); 

    header('Content-type: application/json');
    ?>
    {
            "username": "<?php echo $user->id ;?>",
            "show": "<?php echo $user->name; ?>",
            "me": "<?php echo JText::_('me'); ?>",
            "items": [
                <?php echo $items;?>
              ]
    }
    <?php
        jexit();
    }
    
    function chatboxsession($chatbox) {
    
        $items = '';
    
        if (isset($_SESSION['chatHistory'][$chatbox])) {
            $items = $_SESSION['chatHistory'][$chatbox];
        }

        return $items;
    }
    
    function getNewList()
    {
        require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
        global $mainframe;
        $count      = '';
        $chatuser   = '';
        $chattitle  = '';
        $rows       = '';
        $user =& JFactory::getUser();   
        $doc =& JFactory::getDocument();
    
        if($chat_config['chatusertitle'])
            $chattitle  = 'username';
        else
            $chattitle  = 'name';

        if ($user->id) { 

            $db = JFactory::getDBO();
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
                    AND s.client_id = 0 
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
                    AND s.client_id = 0
                    ORDER BY u.".$chattitle;
                }
                else
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                    "FROM #__users u, #__session s, #__comprofiler b
                WHERE u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0 AND u.id<> " . $user->id . 
                    " ORDER BY u.".$chattitle ;
                }   
                
            }
            else if( $chat_config['community']==2 )
            {
                        //standalone
                $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
                "FROM #__users u, #__session s ".
                "WHERE u.id = s.userid AND s.client_id = 0 AND u.id<> " . $user->id . 
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
                    . ' AND s.client_id = 0 '
                    . ' ORDER BY b.'.$chattitle ;
                }
                else
                {
                    $query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
                    . ' FROM  #__users as b , #__session AS s , #__community_users cu '
                    . ' WHERE b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0 AND b.id<> ' . $user->id
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
                    . ' AND s.client_id = 0 '
                    . ' ORDER BY b.'.$chattitle ;
                }
                else
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
                "FROM #__users u, #__session s ".
                "WHERE u.id = s.userid AND s.client_id = 0 AND u.id<> " . $user->id . 
                " ORDER BY u.".$chattitle ;
                }


            }


//////////////////////////////////////////////////////////
            //Select Group ID       

        jimport( 'joomla.application.module.helper' );
        
        if(JModuleHelper::getModule( 'jbolo' ))
        {
            $module = JModuleHelper::getModule( 'jbolo' );
            $moduleParams = new JParameter( $module->params );
        }
        $groupselect =  intval($moduleParams->get( 'groupselect',0 ));
        $persongroup = intval($moduleParams->get( 'persongroup',0 ));
        $pagegroup =  intval($moduleParams->get( 'pagegroup',0 ));
            
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
                $rows   =   $db->loadObjectList();
                
                $i=0;
                foreach ($rows as $row)
                {
                    $query = "SELECT DISTINCT memberid "
                    . ' FROM  #__supergroup_members, #__session s '
                    . ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
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
                        WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
                        . ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
                    $db->setQuery($query);
                    $rows = $db->loadObjectList();
                    $rows1=array(); 
                    $j=0;
                    foreach($rows as $aa)
                    {
                        $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                        "FROM #__users u, #__session s, #__comprofiler b
                        WHERE u.id=".$aa->memberid." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
            $rows   =   $db->loadObjectList();
            
            $i=0;
            foreach ($rows as $row)
            {
                $query = "SELECT DISTINCT id_user "
                . ' FROM  #__gj_users, #__session s '
                . ' WHERE id_group='. $row->id_group.' AND id_user IN ( s.userid ) AND s.client_id = 0' ;
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
                    WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
                    . ' WHERE id_group='.$groupid.' AND id_user IN ( s.userid ) AND s.client_id = 0' ;
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $rows1=array(); 
                $j=0;
                foreach($rows as $aa)
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                    "FROM #__users u, #__session s, #__comprofiler b
                    WHERE u.id=".$aa->id_user." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
            $rows   =   $db->loadObjectList();;
            $i=0;
            foreach ($rows as $row)
            {  
                $query = "SELECT DISTINCT memberid "
                . ' FROM  #__community_groups_members, #__session s '
                . ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
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
                    . ' WHERE b.id='.$aa.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0'
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
                    . ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $rows1=array(); 
                $j=0;
                foreach($rows as $aa)
                {   
                    $query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
                        . ' FROM  #__users as b , #__session AS s , #__community_users cu '
                        . ' WHERE b.id='.$aa->memberid.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0'
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

                
                    //$chatuser .= "<ul id='jbusers'>"; 

                    $sql = "SELECT chat_status FROM #__jbolo_status 
                        WHERE joom_id = {$user->id}";

                    $db->setQuery($sql);
                    $chats = $db->loadResult();

                    if($chats==0)
                    {
                        $chatuser .=    "<li class='logeduser img_off'>".$user->$chattitle."</li>"; 
                    }
                    elseif($chats==1)
                    {
                        $chatuser .=    "<li class='logeduser img_green'>".$user->$chattitle."</li>"; 
                    }
                    elseif($chats==2)
                    {
                        $chatuser .=    "<li class='logeduser img_red'>".$user->$chattitle."</li>"; 
                    }
                    elseif($chats==3)
                    {
                        $chatuser .=    "<li class='logeduser img_green'>".$user->$chattitle."</li>"; 
                    }

            $itemid = JRequest::getVar('Itemid');

            $count  = count($rows);
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
                        $img    = '';
                        if($chat_config['community']==1){
                            $a  =   '<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_comprofiler&&task=userProfile&user='.$row->id.'&Itemid='.$itemid.'">';
                            if(isset($row->avatar))
                                $img    = $a.'<img src="images/comprofiler/'.$row->avatar.'" width="120" height="90" alt="'.$row->username.'" ></a>';   
                            else
                                $img    = $a.'<img src="components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png" alt="'.$row->username.'"></a>';
                                            
                        }   
                        else if(!$chat_config['community']) 
                        {
                            $a  = '<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_community&view=profile&userid='.$row->id.'&Itemid='.$itemid.'">';
                            $img= $a.'<img src="'.$row->thumb.'" alt="'.$row->username.'"></a>';
                        }                       
                        
                            if($chats==2)
                            {
                                $chatuser   .= "<li id=jb_user_".$row->id."><a class='tt img_red' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            }
                            else
                            {
                                $chatuser   .= "<li id=jb_user_".$row->id."><a class='tt img_green' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            }
                                        
                                        
                        }
                    else
                    {   //this is for stand alone
                                if($chats==2)
                            {
                                $chatuser   .= "<li id=jb_user_".$row->id."><a class='tt img_red' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            }
                            else
                            {
                                $chatuser   .= "<li id=jb_user_".$row->id."><a class='tt img_green' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            }
                    }
                }
                
                if($user && !$rows)
                    $chatuser   .= "<div class='onoffmsg'>".Jtext::_('MOD_ONLINE_MSG')."</div>";
                                    
                    //$chatuser .="</ul>";
        }
        else
            $chatuser   .= Jtext::_('MOD_OFFLINE_MSG');

        if($count>5)    $chatuser   = "
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
    
    function chat_status()
    {
        $user =& JFactory::getUser();
        if (!$user->id) { return; }
        
        $db =& JFactory::getDBO(); 
        $stats = JRequest::getVar('stats');
        $msg = JRequest::getVar('msg');
        $msg = addslashes(strip_tags($msg));
        $sql = "SELECT chat_status FROM #__jbolo_status 
            WHERE joom_id = {$user->id}";
        $db->setQuery($sql);
        $chats = $db->loadResult();
        if($chats == null)
        {
            $chatstat = new stdClass();
            $chatstat->joom_id = $user->id;
            $chatstat->chat_status = $stats;
            $chatstat->status_msg = $msg;
            $db->insertObject('#__jbolo_status', $chatstat);
        }
        else
        {
            if($stats==3)
            {
                echo $sql = "UPDATE `jos_jbolo_status` SET `chat_status` = '".$stats."',
`status_msg` = '".$msg."' WHERE `joom_id` =".$user->id." LIMIT 1" ;
                $db->setQuery($sql);
                $db->query();
            }
            else
            {           
                $sql = "UPDATE #__jbolo_status 
                SET chat_status = {$stats}
                WHERE joom_id = {$user->id}";
                $db->setQuery($sql);
                $db->query();
            }
        }
        jexit();
    }
    
    function downloadFile()
    {
        // Allow direct file download (hotlinking)?
        // Empty - allow hotlinking
        // If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
        define('ALLOWED_REFERRER', '');
    
        // Download folder, i.e. folder where you keep all files for download.
        // MUST end with slash (i.e. "/" )
        define('BASE_DIR',JPATH_ADMINISTRATOR.'/components/com_jbolo/uploads/');

        // log downloads?  true/false
        define('LOG_DOWNLOADS',true);

        // log file name
        define('LOG_FILE','downloads.log');

        // Allowed extensions list in format 'extension' => 'mime type'
        // If myme type is set to empty string then script will try to detect mime type 
        // itself, which would only work if you have Mimetype or Fileinfo extensions
        // installed on server.
        $allowed_ext = array (
        
          // archives
          'zip' => 'application/zip',

          // documents
          'pdf' => 'application/pdf',
          'doc' => 'application/msword',
          'xls' => 'application/vnd.ms-excel',
          'ppt' => 'application/vnd.ms-powerpoint',
          
          // executables
          /*'exe' => 'application/octet-stream',*/

          // images
          'gif' => 'image/gif',
          'png' => 'image/png',
          'jpe' => 'image/jpeg',
          'jpe' => 'image/pjpeg',
          'jpeg' => 'image/jpeg',
          'jpeg' => 'image/pjpeg',
          'jpg' => 'image/jpeg',
          'jpg' => 'image/pjpeg',

          // audio
          'mp3' => 'audio/mpeg',
          'wav' => 'audio/x-wav',

          // video
          'mpeg' => 'video/mpeg',
          'mpg' => 'video/mpeg',
          'mpe' => 'video/mpeg',
          'mov' => 'video/quicktime',
          'avi' => 'video/x-msvideo'
        );



        ####################################################################
        ###  DO NOT CHANGE BELOW
        ####################################################################

        // If hotlinking not allowed then make hackers think there are some server problems
        if (ALLOWED_REFERRER !== ''
        && (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
        ) {
          die("Internal server error. Please contact system administrator.");
        }

        // Make sure program execution doesn't time out
        // Set maximum script execution time in seconds (0 means no limit)
        set_time_limit(0);

        if (!isset($_GET['f']) || empty($_GET['f'])) {
          die("Please specify file name for download.");
        }

        // Get real file name.
        // Remove any path info to avoid hacking by adding relative path, etc.
        $fname = basename($_GET['f']);

        // get full file path (including subfolders)
        $file_path = '';
        $this->find_file(BASE_DIR, $fname, $file_path);
        
        if (!is_file($file_path)) {
          die("File does not exist. Make sure you specified correct file name."); 
        }

        // file size in bytes
        $fsize = filesize($file_path); 
        //echo $fsize;die;
        // file extension
        $fext = strtolower(substr(strrchr($fname,"."),1));

        // check if allowed extension
        if (!array_key_exists($fext, $allowed_ext)) {
          die("Not allowed file type."); 
        }

        // get mime type
        if ($allowed_ext[$fext] == '') {
          $mtype = '';
          // mime type is not set, get from server settings
          if (function_exists('mime_content_type')) {
            $mtype = mime_content_type($file_path);
          }
          else if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME); // return mime type
            $mtype = finfo_file($finfo, $file_path);
            finfo_close($finfo);  
          }
          if ($mtype == '') {
            $mtype = "application/force-download";
          }
        }
        else {
          // get mime type defined by admin
          $mtype = $allowed_ext[$fext];
        }

        // Browser will try to save file with this filename, regardless original filename.
        // You can override it if needed.

        if (!isset($_GET['fc']) || empty($_GET['fc'])) {
          $asfname = $fname;
        }
        else {
          // remove some bad chars
          $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
          if ($asfname === '') $asfname = 'NoName';
        }
        //echo $asfname;die;
        // set headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: $mtype");
        header("Content-Disposition: attachment; filename=\"$asfname\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . $fsize);

        // download
        // @readfile($file_path);
        $file = @fopen($file_path,"rb");
        
        if ($file) {
          while(!feof($file)) {
            print(fread($file, 1024*8));
            flush();
            if (connection_status()!=0) {
              @fclose($file);
              die();
            }
          }
          @fclose($file);
        }
        
        // log downloads
        if (!LOG_DOWNLOADS) die();

        $f = @fopen(LOG_FILE, 'a+');
        if ($f) {
          @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
          @fclose($f);
        }

    }
    
    // Check if the file exists
    // Check in subfolders too
    function find_file ($dirname, $fname, &$file_path) {

      $dir = opendir($dirname);

      while ($file = readdir($dir)) {
        if (empty($file_path) && $file != '.' && $file != '..') {
          if (is_dir($dirname.'/'.$file)) {
            find_file($dirname.'/'.$file, $fname, $file_path);
          }
          else {
            if (file_exists($dirname.'/'.$fname)) {
              $file_path = $dirname.'/'.$fname;
              return;
            }
          }
        }
      }

    } // find_file

    function getNewFBList()
    {
        require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
        global $mainframe;
        $count      = '';
        $chatuser   = '';
        $chattitle  = '';
        $rows       = '';
        $user =& JFactory::getUser();   
        $doc =& JFactory::getDocument();
    
        if($chat_config['chatusertitle'])
            $chattitle  = 'username';
        else
            $chattitle  = 'name';

        if ($user->id) { 

            $db = JFactory::getDBO();
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
                    AND s.client_id = 0 
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
                    AND s.client_id = 0
                    ORDER BY u.".$chattitle;
                }
                else
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                "FROM #__users u, #__session s, #__comprofiler b
                WHERE u.id=b.user_id AND u.id IN ( s.userid ) AND u.id<> " . $user->id . " AND s.client_id = 0 ORDER BY u.".$chattitle ;
                }   
                
            }
            else if( $chat_config['community']==2 )
            {
                        //standalone
                $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
                "FROM #__users u, #__session s ".
                "WHERE u.id = s.userid AND s.client_id = 0 AND u.id<> " . $user->id . 
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
                    . ' AND s.client_id = 0 '
                    . ' ORDER BY b.'.$chattitle ;
                }
                else
                {
                $query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
                    . ' FROM  #__users as b , #__session AS s , #__community_users cu '
                    . ' WHERE b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0  AND b.id<> ' . $user->id
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
                    . ' AND s.client_id = 0 '
                    . ' ORDER BY b.'.$chattitle ;
                }
                else
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
                "FROM #__users u, #__session s ".
                "WHERE u.id = s.userid AND s.client_id = 0 AND u.id<> " . $user->id . 
                " ORDER BY u.".$chattitle ;
                }


            }


//////////////////////////////////////////////////////////
            //Select Group ID       
        jimport( 'joomla.application.module.helper' );
        
        if(JModuleHelper::getModule( 'jbolo' ))
        {
            $module = JModuleHelper::getModule( 'jbolo' );
            $moduleParams = new JParameter( $module->params );
        }
        $groupselect =  intval($moduleParams->get( 'groupselect',0 ));
        $persongroup = intval($moduleParams->get( 'persongroup',0 ));
        $pagegroup =  intval($moduleParams->get( 'pagegroup',0 ));
            
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
                $rows   =   $db->loadObjectList();
                
                $i=0;
                foreach ($rows as $row)
                {
                    $query = "SELECT DISTINCT memberid "
                    . ' FROM  #__supergroup_members, #__session s '
                    . ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
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
                        WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
                        . ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
                    $db->setQuery($query);
                    $rows = $db->loadObjectList();
                    $rows1=array(); 
                    $j=0;
                    foreach($rows as $aa)
                    {
                        $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                        "FROM #__users u, #__session s, #__comprofiler b
                        WHERE u.id=".$aa->memberid." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
            $rows   =   $db->loadObjectList();
            
            $i=0;
            foreach ($rows as $row)
            {
                $query = "SELECT DISTINCT id_user "
                . ' FROM  #__gj_users, #__session s '
                . ' WHERE id_group='. $row->id_group.' AND id_user IN ( s.userid ) AND s.client_id = 0' ;
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
                    WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
                    . ' WHERE id_group='.$groupid.' AND id_user IN ( s.userid ) AND s.client_id = 0' ;
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $rows1=array(); 
                $j=0;
                foreach($rows as $aa)
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                    "FROM #__users u, #__session s, #__comprofiler b
                    WHERE u.id=".$aa->id_user." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
            $rows   =   $db->loadObjectList();;
            $i=0;
            foreach ($rows as $row)
            {  
                $query = "SELECT DISTINCT memberid "
                . ' FROM  #__community_groups_members, #__session s '
                . ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
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
                    . ' WHERE b.id='.$aa.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0'
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
                    . ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $rows1=array(); 
                $j=0;
                foreach($rows as $aa)
                {   
                    $query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
                        . ' FROM  #__users as b , #__session AS s , #__community_users cu '
                        . ' WHERE b.id='.$aa->memberid.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0'
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


            $chatuser   .=  "<ul id='jfbusers'>";
            $itemid = JRequest::getVar('Itemid');

            $count  = count($rows);
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
                        $img    = '';
                        if($chat_config['community']==1){
                            $a  =   '<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_comprofiler&&task=userProfile&user='.$row->id.'&Itemid='.$itemid.'">';
                            if(isset($row->avatar))
                                $img    = $a.'<img src="images/comprofiler/'.$row->avatar.'" width="120" height="90" alt="'.$row->username.'" ></a>';   
                            else
                                $img    = $a.'<img src="components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png" alt="'.$row->username.'"></a>';
                                            
                        }   
                        else if(!$chat_config['community']) 
                        {
                            $a  = '<a style="text-decoration:none;" href="'.JURI::base().'index.php?option=com_community&view=profile&userid='.$row->id.'&Itemid='.$itemid.'">';
                            $img= $a.'<img src="'.$row->thumb.'" alt="'.$row->username.'"></a>';
                        }                       
                        
                        //$chatuser .= "<li id=jfb_user_".$row->id."><a class='jfb_anchor' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            /* Changes for status icons*/
                        if($chats==2)
                            {
                                $chatuser   .= "<li id=jb_user_".$row->id."><a class='jfb_anchor img_red' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            }
                            else
                            {
                                $chatuser   .= "<li id=jb_user_".$row->id."><a class='jfb_anchor img_green' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            }
                        /* END Changes for status icons*/               
                                        
                        }
                    else
                    {   
                        /* Changes for status icons*/
                        if($chats==2)
                            {
                                $chatuser   .= "<li id=jb_user_".$row->id."><a class='jfb_anchor img_red' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            }
                            else
                            {
                                $chatuser   .= "<li id=jb_user_".$row->id."><a class='jfb_anchor img_green' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                            }
                        /* END Changes for status icons*/
                        //$chatuser .= "<li id=jfb_user_".$row->id."><a class='jfb_anchor' href=javascript:void(0) onclick=javascript:chatWith('".$row->id."')>".$row->$chattitle."</a></li>";
                    }
                }
                
                if($user && !$rows)
                    $chatuser   .= "<div class='onoffmsg'>".Jtext::_('MOD_ONLINE_MSG')."</div>";
                    
                    $chatuser .="</ul>";
        }
        else
            $chatuser   .= Jtext::_('MOD_OFFLINE_MSG');

            if($count>5)    $chatuser   = "
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
    
    function getPlainList()
    {
        require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
        global $mainframe;
        $count      = '';
        $chatuser   = array();
        $chattitle  = '';
        $rows       = '';
        $user =& JFactory::getUser();   
        $doc =& JFactory::getDocument();
    
        if($chat_config['chatusertitle'])
            $chattitle  = 'username';
        else
            $chattitle  = 'name';

        if ($user->id) { 

            $db = JFactory::getDBO();
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
                    AND s.client_id = 0 
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
                    AND s.client_id = 0
                    ORDER BY u.".$chattitle;
                }
                else
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                "FROM #__users u, #__session s, #__comprofiler b
                WHERE u.id=b.user_id AND u.id IN ( s.userid ) AND u.id<> " . $user->id . " AND s.client_id = 0 ORDER BY u.".$chattitle ;
                }   
                
            }
            else if( $chat_config['community']==2 )
            {
                        //standalone
                $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
                "FROM #__users u, #__session s ".
                "WHERE u.id = s.userid AND s.client_id = 0 AND u.id<> " . $user->id . 
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
                    . ' AND s.client_id = 0 '
                    . ' ORDER BY b.'.$chattitle ;
                }
                else
                {
                $query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
                    . ' FROM  #__users as b , #__session AS s , #__community_users cu '
                    . ' WHERE b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0  AND b.id<> ' . $user->id
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
                    . ' AND s.client_id = 0 '
                    . ' ORDER BY b.'.$chattitle ;
                }
                else
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name  " .
                "FROM #__users u, #__session s ".
                "WHERE u.id = s.userid AND s.client_id = 0 AND u.id<> " . $user->id . 
                " ORDER BY u.".$chattitle ;
                }


            }


//////////////////////////////////////////////////////////
            //Select Group ID       
        jimport( 'joomla.application.module.helper' );
        
        if(JModuleHelper::getModule( 'jbolo' ))
        {
            $module = JModuleHelper::getModule( 'jbolo' );
            $moduleParams = new JParameter( $module->params );
        }
        $groupselect =  intval($moduleParams->get( 'groupselect',0 ));
        $persongroup = intval($moduleParams->get( 'persongroup',0 ));
        $pagegroup =  intval($moduleParams->get( 'pagegroup',0 ));
            
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
                $rows   =   $db->loadObjectList();
                
                $i=0;
                foreach ($rows as $row)
                {
                    $query = "SELECT DISTINCT memberid "
                    . ' FROM  #__supergroup_members, #__session s '
                    . ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
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
                        WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
                        . ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
                    $db->setQuery($query);
                    $rows = $db->loadObjectList();
                    $rows1=array(); 
                    $j=0;
                    foreach($rows as $aa)
                    {
                        $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                        "FROM #__users u, #__session s, #__comprofiler b
                        WHERE u.id=".$aa->memberid." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
            $rows   =   $db->loadObjectList();
            
            $i=0;
            foreach ($rows as $row)
            {
                $query = "SELECT DISTINCT id_user "
                . ' FROM  #__gj_users, #__session s '
                . ' WHERE id_group='. $row->id_group.' AND id_user IN ( s.userid ) AND s.client_id = 0' ;
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
                    WHERE u.id=".$aa." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
                    . ' WHERE id_group='.$groupid.' AND id_user IN ( s.userid ) AND s.client_id = 0' ;
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $rows1=array(); 
                $j=0;
                foreach($rows as $aa)
                {
                    $query = "SELECT DISTINCT u.id, u.".$chattitle.", u.username, u.name, b.avatar " .
                    "FROM #__users u, #__session s, #__comprofiler b
                    WHERE u.id=".$aa->id_user." AND u.id=b.user_id AND u.id IN ( s.userid ) AND s.client_id = 0
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
            $rows   =   $db->loadObjectList();;
            $i=0;
            foreach ($rows as $row)
            {  
                $query = "SELECT DISTINCT memberid "
                . ' FROM  #__community_groups_members, #__session s '
                . ' WHERE groupid='. $row->groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
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
                    . ' WHERE b.id='.$aa.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0'
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
                    . ' WHERE groupid='. $groupid.' AND memberid IN ( s.userid ) AND s.client_id = 0' ;
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $rows1=array(); 
                $j=0;
                foreach($rows as $aa)
                {   
                    $query = "SELECT DISTINCT b.id, b.".$chattitle.", cu.thumb, b.username, b.name "
                        . ' FROM  #__users as b , #__session AS s , #__community_users cu '
                        . ' WHERE b.id='.$aa->memberid.' AND b.id=cu.userid AND b.id IN (s.userid) AND s.client_id = 0'
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

            $itemid = JRequest::getVar('Itemid');
                
            $chatboxtitle = JRequest::getVar('uid');
            
            if(strlen($chatboxtitle)>6)
            {
                $query = "SELECT userid FROM #__jbolo_group WHERE chatroom_id=".$chatboxtitle." AND userid<>".$user->id." AND status = 1";
                $db->setQuery($query);  
                $grpchatlist = $db->loadResultArray();
            }
            //print_r($grpchatlist);
            //print_r($rows);
            $count  = count($rows);
            $i=1;
            if($count)
            foreach ($rows as $row)
            {
                if( $row->$chattitle == $user->$chattitle )
                    continue;

                if( $row->id == $chatboxtitle )
                    continue;               
        
                if(isset($grpchatlist)) {
                    if (in_array($row->id, $grpchatlist)) {
                        continue;
                    }
                }
            
                $sql = "SELECT chat_status FROM #__jbolo_status 
                WHERE joom_id = {$row->id}";
                $db->setQuery($sql);
                $chats = $db->loadResult();
                if($chats==0)
                    continue;
                
                $items .= <<<EOD
{"i": "{$row->id}",
"o": "{$row->$chattitle}",},
EOD;
                
            }
        }

    if ($items != '') {
            $items = substr($items, 0, -1);
        }
        
    if($items!=null)
    {
        header('Content-type: application/json');
        ?>
{"l":[<?php echo $items;?>]}
    
    <?php
    }
        jexit();
    }
    
    function groupChat() {
        $db = JFactory::getDBO();
        $user[] = JRequest::getVar('owner');
        $user[] = JRequest::getVar('chat1');
        $user[] = JRequest::getVar('chat2');
        $tstamp = JRequest::getVar('tstamp');
        $chattitle = $this->getAccessName();

        if(strlen($user[1])>6)
        {
            $query = "SELECT userid FROM #__jbolo_group WHERE chatroom_id=".$user[1];
            $db->setQuery($query);  
            $grpchatlist = $db->loadResultArray();

            if(isset($grpchatlist)) {
                if (in_array($user[2], $grpchatlist)) {
                        $sql = "UPDATE #__jbolo_group SET status = 1 WHERE chatroom_id =".$user[1]." AND userid = ".$user[2];
                        $db->setQuery($sql);
                        $db->query();
                        $this->groupPush('GroupNotifier',$user[1],JFactory::getUser($user[2])->$chattitle.' '.JText::_('HAS_JOINED'));
                }
                else {
                    $groupdata = new stdClass;
                    $groupdata->id = '';
                    $groupdata->chatroom_id = $user[1];
                    $groupdata->userid = $user[2];
                    $groupdata->type = 0;
                    $groupdata->status = 1;
                    if(!$db->insertObject('#__jbolo_group', $groupdata, 'id')) {
                        echo $db->stderr();
                        return false;
                    }
                    $this->groupPush('GroupNotifier',$user[1],JFactory::getUser($user[2])->$chattitle.' '.JText::_('HAS_JOINED'));
                    echo JFactory::getUser($user[2])->$chattitle;
                }
            }
        }
        else
        {
            foreach($user as $us) { 
                $groupdata = new stdClass;
                $groupdata->id = '';
                $groupdata->chatroom_id = $tstamp;
                $groupdata->userid = $us;
                $groupdata->type = 0;
                $groupdata->status = 1;
                if(!$db->insertObject('#__jbolo_group', $groupdata, 'id')) {
                    echo $db->stderr();
                    return false;
                }
            }
            $this->groupPushFirst($user,$tstamp);
        }
        jexit();
    }
    
    function groupPush($from,$chatroom_id,$message)
    {
        $user =& JFactory::getUser();
        if (!$user->id) { return; }
        
        $db =& JFactory::getDBO();
        $chat = new stdClass();
        $chat->from = $from;
        $chat->to = $chatroom_id;
        $chat->message = $message;
        $chat->sent = date('Y-m-d H:i:s');

        $db->insertObject('#__jbolo', $chat);
        $jid = $db->insertid();
        
        $query = "SELECT userid FROM #__jbolo_group WHERE chatroom_id=".$chatroom_id." AND userid<>".$user->id." AND status = 1";
        $db->setQuery($query);  
        $usrlist = $db->loadResultArray();
        foreach($usrlist as $usr)
        {
            $grp = new stdClass();
            $grp->jid = $jid;
            $grp->userid = $usr;
            $grp->recd = 0;
            $db->insertObject('#__jbolo_group_xref', $grp);
        }
    }
    
    function groupPushFirst($userarray,$chatroom_id)
    {
        $user =& JFactory::getUser();
        if (!$user->id) { return; }
        
        $from = 'GroupNotifier';
        $chattitle = $this->getAccessName();

        for($i=0;$i<3;$i++)
        {
            $message = JFactory::getUser($userarray[$i])->$chattitle.' '.JText::_('HAS_JOINED');
            $db =& JFactory::getDBO();
            $chat = new stdClass();
            $chat->from = $from;
            $chat->to = $chatroom_id;
            $chat->message = $message;
            $chat->sent = date('Y-m-d H:i:s');
            $db->insertObject('#__jbolo', $chat);
            $jid = $db->insertid();
            $j=1;
            $k=2;
            if($i==1)
            {
                $j=2;
                $k=0;
            }
            elseif($i==2)
            {
                $j=0;
                $k=1;
            }
            $grp = new stdClass();
            $grp->jid = $jid;
            $grp->userid = $userarray[$j];
            $grp->recd = 0;
            $db->insertObject('#__jbolo_group_xref', $grp);

            $grp = new stdClass();
            $grp->jid = $jid;
            $grp->userid = $userarray[$k];
            $grp->recd = 0;
            $db->insertObject('#__jbolo_group_xref', $grp);
        }
    }
    
    function purge() 
    {   
        require(str_replace('administrator'.DS, '', JPATH_COMPONENT).DS."config".DS."config.php");
            $purge  = $chat_config['purge'];
            if($purge)
            {           
                if($chat_config['key']==JRequest::getVar('purge'))
                {
                    $db     =& JFactory::getDBO();
                    $db->setQuery("DELETE FROM #__jbolo WHERE DATEDIFF('".date('Y-m-d')."',sent) >=".$chat_config['days']);
                    $db->query();
                    $this->purgefiles();
                }
                }
            return 1;
    }
    
    private function purgefiles()
    {
        require(str_replace('administrator'.DS, '', JPATH_COMPONENT).DS."config".DS."config.php");
        $uploaddir = str_replace('administrator'.DS, '', JPATH_COMPONENT).DS."uploads".DS;
        $handle = opendir($uploaddir);
        $exp_file_time=3600*24*$chat_config['days'];
        $currts = time();
        while(false !== ($file = readdir($handle)))
        {
            if( $file != ".." && $file != "." )
            {
                $diffts = $currts - filemtime($uploaddir.$file);
                if($diffts > $exp_file_time ){
                    unlink($uploaddir.$file);
                }
            }
            
        }
    }
    
    function uploadFile()
    {
    
         if(!(JRequest::getVar('tuser') && JRequest::getVar('fuser')))
        {
            return;
        }

    // Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
        $POST_MAX_SIZE = ini_get('post_max_size');
        $unit = strtoupper(substr($POST_MAX_SIZE, -1));
        $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

        if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
            header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
            echo "POST exceeded maximum allowed size.";
            jexit();
        }

    // Settings
        $save_path = JPATH_ADMINISTRATOR."/components/com_jbolo/uploads/";       // The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
        $upload_name = "Filedata";
        $max_file_size_in_bytes = 2147483647;               // 2GB in bytes
        $extension_whitelist = array("jpe", "jpg", "jpeg", "gif", "png", "zip", "pdf", "doc", "xls", "ppt", "mp3", "wav", "mpeg", "mpg", "mpe", "mov", "avi");  // Allowed file extensions
        $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';                // Characters allowed in the file name (in a Regular Expression format)
    
    // Other variables  
        $MAX_FILENAME_LENGTH = 260;
        $file_name = "";
        $file_extension = "";
        $uploadErrors = array(
            0=>"There is no error, the file uploaded with success",
            1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
            2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
            3=>"The uploaded file was only partially uploaded",
            4=>"No file was uploaded",
            6=>"Missing a temporary folder"
        );


    // Validate the upload
        if (!isset($_FILES[$upload_name])) {
            echo "No upload found in \$_FILES for " . $upload_name;
            jexit();
        } else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
            echo $uploadErrors[$_FILES[$upload_name]["error"]];
            jexit();
        } else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
            echo "Upload failed is_uploaded_file test.";
            jexit();
        } else if (!isset($_FILES[$upload_name]['name'])) {
            echo "File has no name.";
            jexit();
        }
    
    // Validate the file size (Warning: the largest files supported by this code is 2GB)
        $file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
        if (!$file_size || $file_size > $max_file_size_in_bytes) {
            echo "File exceeds the maximum allowed size";
            jexit();
        }
    
        if ($file_size <= 0) {
            echo "File size outside allowed lower bound";
            jexit();
        }


    // Validate file name (for our purposes we'll just remove invalid characters)
        $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
        
        if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
            echo "Invalid file name";
            jexit();
        }


    // Validate that we won't over-write an existing file
        /*if (file_exists($save_path . $file_name)) {
            echo "File with this name already exists";
            jexit();
        }*/

    // Validate file extension
        $path_info = pathinfo($_FILES[$upload_name]['name']);
        $file_extension = $path_info["extension"];
        $is_valid_extension = false;
        foreach ($extension_whitelist as $extension) {
            if (strcasecmp($file_extension, $extension) == 0) {
                $is_valid_extension = true;
                break;
            }
        }
        if (!$is_valid_extension) {
            echo "Invalid file extension";
            jexit();
        }
        $file_name=str_replace(" ","",$file_name);//added by MANOJ to save files without any space in filename
        if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name))     {
            echo "File could not be saved.";
            jexit();
        }
        else
        {
            $db =& JFactory::getDBO();
            $from = JRequest::getVar('fuser');
            $to = JRequest::getVar('tuser');
            $message = "has sent you a file. Download link: ".JURI::base().'index.php?option=com_jbolo&action=downloadFile&format=raw&f='.$file_name;

            $_SESSION['openChatBoxes'][$to] = date('Y-m-d H:i:s', time());

            if (!isset($_SESSION['chatHistory'][$to])) {
                $_SESSION['chatHistory'][$to] = '';
            }

            $_SESSION['chatHistory'][$to] .= <<<EOD
                {
                    "s": "1",
                    "f": "{$to}",
                    "m": "{$message}"
                 },
EOD;
            unset($_SESSION['tsChatBoxes'][$to]);

$_SESSION['chatHistory'][$to] .= <<<EOD
                {
                    "s": "1",
                    "f": "{$from}",
                    "m": "{$message}"
                 },
EOD;
            unset($_SESSION['tsChatBoxes'][$from]);


            $chat = new stdClass();
            $chat->from = $from;
            $chat->to = $to;
            $chat->message = $message;
            $chat->sent = date('Y-m-d H:i:s');
            
            $db->insertObject('#__jbolo', $chat);
    
            $sql = "SELECT * FROM #__session
            WHERE userid = {$to}";
            $db->setQuery($sql);
            $chats = $db->loadResult();

            echo "File Sent";
            jexit();
        }

        jexit();
    }
    
}

