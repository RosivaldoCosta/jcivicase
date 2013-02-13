<?php


class Case extends CiviUnitTestCase
{
	function add( $params)
	{

	}

	function retrieve( &$params, &$defaults, &$ids)
	{

	}

	function processCaseActivity( $params)
	{

	}

	function processSignatureCaptureActivity( $params)
	{

	}

	function processChangeCaseStatusActivity( $params)
	{

	}

	function deleteCase($caseId, $moveToTrash = false)
	{

	}

	function deleteCaseActivity($activityId)
	{

	}


	function getCaseActivityQuery($type = 'upcoming', $userID = null, $condition = null, $isDeleted = 0)
	{

	}

	function getCases($allCases = true, $userID = null, $type = 'recent')
	{

	}

	function getCaseActivity( $caseID, $params, $contactID)
	{

	}

    /*
     * Helper function to create
     * a case 
     *
     * @return $caseID id of created case   
     */
    function create( $params ) {
        require_once "CRM/Case/BAO/Case.php";
        $caseID = CRM_Case_BAO_Case::create( $params );
        return $caseID;
    }


    /*
     * Helper function to create
     * a contact of type Individual
     *
     * @return $contactID id of created Individual
     */
    function createCase( $params = null ) {
        //compose the params, when not passed
        if ( !$params ) {
            $first_name = 'John';
            $last_name  = 'Doe';
            $contact_source = 'Testing purpase';
            $params = array(
                            'first_name'     => $first_name,
                            'last_name'      => $last_name,
                            'contact_source' => $contact_source
                            );
        }
        return self::create($params);
    }

    function createAdult( $params = null ) {
        //compose the params, when not passed
        if ( !$params ) {
            $first_name = 'John';
            $last_name  = 'Doe';
            $contact_source = 'Testing purpose';
            $params = array(
                            'first_name'     => $first_name,
                            'last_name'      => $last_name,
                            'contact_source' => $contact_source
                            );
        }
        return self::create($params);
    }

    /*
     * Helper function to delete a contact
     * 
     * @param  int  $contactID   id of the contact to delete
     * @return boolean true if contact deleted, false otherwise
     * 
     */
    function delete( $contactID ) {
        return CRM_Contact_BAO_Contact::deleteContact( $contactID );
    }
}

?>
