<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appdashboard_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }
}