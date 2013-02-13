<?php
/**
 * Tine 2.0
 * 
 * @package     Tinebase
 * @subpackage  Exception
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @version     $Id: NotFound.php 9423 2009-07-14 11:01:10Z c.weiss@metaways.de $
 *
 */

/**
 * NotFound exception
 * 
 * @package     Tinebase
 * @subpackage  Exception
 */
class Tinebase_Exception_NotFound extends Tinebase_Exception
{
    
    public function __construct($_message, $_code=404) {
    	parent::__construct($_message, $_code);
    }
}