<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datumpenja_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getAllData($id_skpd){
    	$this->db->select("*");
    	$this->db->from("tb_pptk");
    	$this->db->where("id_skpd", $id_skpd);
    	$this->db->where("status!=", 3);
    	$data = $this->db->get();
    	return $data;
    }

    public function getData($token){
    	$this->db->select("*");
    	$this->db->from("tb_pptk");
    	$this->db->where("id", $token);
    	$data = $this->db->get();
    	return $data;
    }
    
    public function insertData($data){
    	$this->db->insert("tb_pptk", $data);
    }

    public function updateData($token, $data){
    	$this->db->where("id", $token);
    	$this->db->update("tb_pptk", $data);
    }

    public function deleteData($token){
    	$this->db->where("id", $token);
    	$this->db->delete("tb_pptk");
    }
}