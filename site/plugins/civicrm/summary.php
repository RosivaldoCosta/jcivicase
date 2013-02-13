<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
class plgCivicrmSummary extends JPlugin
{
	/**
		Summary Civicrm Plugin
	*/

	public function civicrm_summary($contactID, &$content, &$contentPlacement = CRM_Utils_Hook::SUMMARY_BELOW)
	{
		//Now define the parameters like this:
                $contentPlacement = $this->params->get( 'placement');
                $content = $this->params->get( 'content');

		
	}

}
?>
