<?php
/**
 * Definisikan constant / global variabel disini
 *
 * @author	Danang Ponorogo @260119
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

//-----/ akun database /-----//
define('HOSTNAME', 'localhost');
define('USERNAME', 'lpsepono_smep');
define('PASSWORD', 'lpseappnew123');
define('DATABASE', 'lpsepono_smep_2019');

//-----/ akun database lama - Kepentingan Generate! /-----//
define('HOSTNAME_DB_OLD', 'localhost');
define('USERNAME_DB_OLD', 'lpsepono_smep');
define('PASSWORD_DB_OLD', 'lpseappnew123');
define('DATABASE_DB_OLD', 'lpsepono_smep_2018');

//-----/ path logo /-----//
define('LOGOPATH', 'custom/');

//-----/ path template laporan /-----//
define('TPLPATH', 'custom/tpl/');

//-----/ object variabel /-----//
$smep = new stdClass();