<?php
/**
 * Definisikan constant / global variabel disini
 *
 * @author	Danang Ponorogo @260119
 *
 */

// -----/ akun database /----- //
define('HOSTNAME', 'localhost');
define('USERNAME', 'lpsepono_smep');
define('PASSWORD', 'lpseappnew123');
define('DATABASE', 'lpsepono_smep_2019');

// -----/ akun database lama - Kepentingan Generate! /----- //
define('HOSTNAME_DB_OLD', 'localhost');
define('USERNAME_DB_OLD', '');
define('PASSWORD_DB_OLD', '');
define('DATABASE_DB_OLD', '');

// -----/ path template laporan /----- //
define('TPLPATH', 'custom' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR);

// -----/ object variabel /----- //
$smep = new stdClass();