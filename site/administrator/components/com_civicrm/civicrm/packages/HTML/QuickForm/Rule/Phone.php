<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Phone number validation rule
 * 
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    HTML
 * @package     HTML_QuickForm
 * @author      Bertrand Mansion <bmansion@mamasam.com>
 * @copyright   2001-2009 The PHP Group
 * @license     http://www.php.net/license/3_01.txt PHP License 3.01
 * @version     CVS: $Id: Email.php,v 1.7 2009/04/04 21:34:04 avb Exp $
 * @link        http://pear.php.net/package/HTML_QuickForm
 */

/**
 * Abstract base class for QuickForm validation rules 
 */
require_once 'HTML/QuickForm/Rule.php';

/**
 * Phone validation rule
 *
 * @category    HTML
 * @package     HTML_QuickForm
 */
class HTML_QuickForm_Rule_Phone extends HTML_QuickForm_Rule
{
    var $regex = '/^\(\d{3}\)\d{3}-\d{4}$/';

    /**
     * Validates an email address
     *
     * @param     string    $phone          Phone number
     * @access    public
     * @return    boolean   true if phone n is valid
     */
    function validate($phone)
    {
        // Fix for bug #10799: add 'D' modifier to regex
        if (preg_match($this->regex , $phone))         {
            return true;
        }
        return false;
    } // end func validate


    function getValidationScript($options = null)
    {
        return array("  var regex = " . $this->regex . ";\n", "{jsVar} != '' && !regex.test({jsVar})");
    } // end func getValidationScript

} // end class HTML_QuickForm_Rule_Phone
?>
