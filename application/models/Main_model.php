<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

	public function __construct()
    {
    	parent::__construct();
    }

    public function getDataUser($token){
        $this->db->select("*");
        $this->db->from("tb_users");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataUserPPKAll(){
        $this->db->select('id, kd_skpd, nama_skpd');
        $this->db->from("simda_skpd");
        $data = $this->db->get();
        return $data;
    }

    public function getDataUserPPKUnique($token){
        $this->db->select('id, kd_skpd, nama_skpd');
        $this->db->from("simda_skpd");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

}