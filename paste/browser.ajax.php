<?php

/*
 * DataTables server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

require_once '../config.php';

// DB table to use
$table = 'pin';


mb_substitute_character('entity');
// Table's primary key
$primaryKey = 'ts';
$columns = array(
    array(
        'db' => 'ts',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_'.$d;
        }
    ),
    array(
        'db'        => 'url',
        'dt'        => 'd_act',
        'formatter' => function( $d, $row ) {
            return '<a href="?purgeitem='. $d .'">Purge</a>';
        }
    ),
    array(
        'db'        => 'ts',
        'dt'        => 'd_date',
        'formatter' => function( $d, $row ) {
            return date( 'Y-M-d H:i:s', strtotime("@".$d));
        }
    ),
    array(
        'db'        => 'url',
        'dt'        => 'd_url',
        'formatter' => function( $d, $row ) {
            return '<a href="' . $d . '">' . $d . '</a>';
        }
    ),
    array(
        'db'        => 'description',
        'dt'        => 'd_desc',
        'formatter' => function( $d, $row ) {
            $desc = mb_substr(mb_convert_encoding($d, "UTF-8"), 0, 76) . " ...";
            if (substr($d, 0, 8) === 'URL http') {
//                error_log("d_desc starts: ".print_r($row,true));
                return "<a href=\"".substr($d,4)."\">".$desc."</a>";
            } else if ( $row[lang] == 'URL') {
//                error_log("lang = URL : ".print_r($row,true));
                return "<a href=\"".$row[rawtext]."\">".$desc."</a>";
            } else {
//                error_log("normal row: ".print_r($row,true));
                return $desc;
            }
        }
    ),
    array( 'db' => 'rawtext' ),
    array( 'db' => 'lang' ),
);

$sql_details = array(
	'user' => cfg('DB_USER'),
	'pass' => cfg('DB_PASS'),
	'db'   => cfg('DB_DB'),
	'host' => cfg('DB_HOST')
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

// in case private/sensitive URLs should be skipped list them like below:
$noautotitleRegex = '.*filesmonster.*|.*pornhub.*|.*unusualporn.net.*';

$json = json_encode( SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, NULL ,'description NOT REGEXP "' . $noautotitleRegex . '"' ), JSON_UNESCAPED_UNICODE );
echo $json;
