<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminkonfigurasi_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataConfig($key){
    	$this->db->select("*");
    	$this->db->from("tb_config");
    	$this->db->where("key", $key);
    	$data = $this->db->get();
    	return $data;
    }

    public function uploadData($data){
    	$this->db->insert("tb_config", $data);
    }

    public function updateData($key, $data){
    	$this->db->where("key", $key);
    	$this->db->update("tb_config", $data);
    }

    public function deleteImage($data){
    	$this->db->where("key", "logo");
    	$this->db->update("tb_config", $data);
    }

}