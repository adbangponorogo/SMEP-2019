<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applogin_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataUsers($username, $password){
    	$this->db->select("*");
    	$this->db->from("v_auth");
        $this->db->where("username", $username);
    	$this->db->where("password", $password);
    	$data = $this->db->get();
    	return $data;
    }
}