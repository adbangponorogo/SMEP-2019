<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applogin_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataUsers($username){
    	$this->db->select("*");
    	$this->db->from("tb_users");
    	$this->db->where("username", $username);
    	$data = $this->db->get();
    	return $data;
    } 
}