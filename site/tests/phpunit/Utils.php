<?php  // vim: set si ai expandtab tabstop=4 shiftwidth=4 softtabstop=4:

/**
 *  File for the Utils class
 *
 *  (PHP 5)
 *  
 *   @author Walt Haas <walt@dharmatech.org> (801) 534-1262
 *   @copyright Copyright CiviCRM LLC (C) 2009
 *   @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU Affero General Public License version 3
 *   @version   $Id: Utils.php 23152 2009-08-05 19:38:03Z walt $
 *   @package   CiviCRM
 *
 *   This file is part of CiviCRM
 *
 *   CiviCRM is free software; you can redistribute it and/or
 *   modify it under the terms of the GNU Affero General Public License
 *   as published by the Free Software Foundation; either version 3 of
 *   the License, or (at your option) any later version.
 *
 *   CiviCRM is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public
 *   License along with this program.  If not, see
 *   <http://www.gnu.org/licenses/>.
 */


/**
 *  Utility functions
 *   @package   CiviCRM
 */
class Utils {

    /**
     *  PDO for the database
     *  @var PDO
     */
    public $pdo;

    /**
     *  Construct an object for this database
     */
    public function __construct( $host, $user, $pass )
    {
	
        try {
            $this->pdo = new PDO( "mysql:host={$host}",
                                  $user, $pass );
        } catch ( PDOException $e ) {
            echo "Can't connect to MySQL server:" . PHP_EOL
                . $e->getMessage() . PHP_EOL;
            exit(1);
        }
    }

    /**
     *  Prepare and execute a query
     *
     *  If the query fails, output a diagnostic message
     *  @param  string  Query to run
     *  @return mixed   PDOStatement => Results of the query
     *                  false        => Query failed
     */
    function do_query( $query )
    {
        //echo "do_query($query)\n";
        $stmt = $this->pdo->query( $query, PDO::FETCH_ASSOC );
        //echo "PDO returned";
        var_dump($stmt);
        if ( $this->pdo->errorCode() == 0 ) {
            echo "returning the PDOStmt\n";
            return $stmt;
        }
        //  operation failed, so output description of where and why
        $errorInfo = $this->pdo->errorInfo();
        echo "Oops, can't do query:\n    {$query}\n    in "
            . basename( __FILE__) . " line " . __LINE__.":\n    "
            . $errorInfo[0] . ": " . $errorInfo[2] . "\n    Call stack:\n";
        $backtrace = debug_backtrace();
        $dir_name = dirname( __FILE__ );
        $cwd_len = strlen( $dir_name ) + 1;
        foreach( $backtrace as $frame ){
            echo "      ";
            if ( array_key_exists( 'class', $frame ) ) {
                echo " class {$frame['class']}";
                if ( array_key_exists( 'function', $frame ) ) {
                    echo " method {$frame['function']}";
                }
            } else {
                if ( array_key_exists( 'function', $frame ) ) {
                    echo " function {$frame['function']}";
                }
            }
            if ( array_key_exists( 'file', $frame ) ) {
                echo " file ". substr( $frame['file'], $cwd_len );
            }
            if ( array_key_exists( 'line', $frame ) ) {
                echo " line {$frame['line']}";
            }
            echo "\n";
        }
        return false;
    }

} // class Utils

// -- set Emacs parameters --
// Local variables:
// mode: php;
// tab-width: 4
// c-basic-offset: 4
// c-hanging-comment-ender-p: nil
// indent-tabs-mode: nil
// End:
