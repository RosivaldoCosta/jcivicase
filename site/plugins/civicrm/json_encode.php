<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmJson_Encode extends JPlugin
{
	/**
		JSON Encode Civicrm Plugin
	*/	

	public function civicrm_json_encode(&$json, $params, $selectorElements = null, $context, $id )
	{
		$json = "";
		if( $context == 'scheduler')
		{
        		$json .= "{\n";
        		$json .= "\"totalCount\": \"$params->N\",\n";
        		$json .= "\"rows\": [";
        		$rc = false;

			while($params->fetch() )
			{
            			if ( $rc ) $json .= ",";
            			$json .= "\n{";
            			$json .= "\"cid\":'".$params->id."',";
            			$json .= "\"name\":'".str_replace("'","",$params->data)."'";
            			$addcomma = false;
            			$json .= "}";
            			$rc = true;
        		}

        		$json .= "]\n";
        		$json .= "}";
		}

        	return $json;	
	}

}
?>
