<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A world table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By world there is only one group (the 'world' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'world';
$active_record = TRUE;

$db['world']['hostname'] = 'localhost';
$db['world']['username'] = 'root';
$db['world']['password'] = 'jincheol';
$db['world']['database'] = 'world';
$db['world']['dbdriver'] = 'mysql';
$db['world']['dbprefix'] = '';
$db['world']['pconnect'] = TRUE;
$db['world']['db_debug'] = TRUE;
$db['world']['cache_on'] = FALSE;
$db['world']['cachedir'] = '';
$db['world']['char_set'] = 'utf8';
$db['world']['dbcollat'] = 'utf8_general_ci';
$db['world']['swap_pre'] = '';
$db['world']['autoinit'] = TRUE;
$db['world']['stricton'] = FALSE;

$db['test']['hostname'] = 'localhost';
$db['test']['username'] = 'root';
$db['test']['password'] = 'jincheol';
$db['test']['database'] = 'test';
$db['test']['dbdriver'] = 'mysql';
$db['test']['dbprefix'] = '';
$db['test']['pconnect'] = TRUE;
$db['test']['db_debug'] = TRUE;
$db['test']['cache_on'] = FALSE;
$db['test']['cachedir'] = '';
$db['test']['char_set'] = 'utf8';
$db['test']['dbcollat'] = 'utf8_general_ci';
$db['test']['swap_pre'] = '';
$db['test']['autoinit'] = TRUE;
$db['test']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */