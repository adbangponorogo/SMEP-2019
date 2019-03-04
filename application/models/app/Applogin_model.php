<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applogin_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataUsers($username, $password){
    	$this->db->select("a.*, b.kd_skpd as kd_skpd");
    	$this->db->from("v_auth a");
			$this->db->join("simda_skpd b", "a.id_skpd = b.id");
			$this->db->where("a.username", $username);
    	$this->db->where("a.password", $password);
    	$data = $this->db->get();
    	return $data;
    }

    public function insertTemporary($data){
        $this->db->insert("tb_temporary", $data);
    }
}