<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpengsi_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getDataSKPD($token){
        $this->db->select("id, kd_skpd, nama_skpd");
        $this->db->from("simda_skpd");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }


    public function getMainData(){
        $this->db->select("id, kd_skpd, nama_skpd");
        $this->db->from("simda_skpd");
        $result = $this->db->get();
        $data = array();
        $no = 1;
        foreach ($result->result() as $rows) {
            $resultCount = $this->getCountUserSKPD($rows->id);
            if ($resultCount->num_rows() > 0) {
                $count = $resultCount->num_rows()."&nbsp;Users";
            }
            else{
                $count = "Tidak ada User";
            }
            $data[] = array(
                        $no++,
                        $rows->kd_skpd,
                        $rows->nama_skpd,
                        $count,
                        "<button class='btn btn-info btn-sm smep-pengsiadmin-user-data-page' data-id='".$rows->id."'>".
                        "<i class='fa fa-eye'></i>&nbsp;Lihat Detail</button>"
                    );
        }
        return $data;
    }

    public function getCountUserSKPD($id_skpd){
        $this->db->select("username");
        $this->db->from("tb_users");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataUser($id_skpd){
        $this->db->select("*");
        $this->db->from("tb_users");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->order_by("tanggal_buat", 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataUserUnique($username){
        $this->db->select("*");
        $this->db->from("tb_users");
        $this->db->where("username", $username);
        $data = $this->db->get();
        return $data;
    }

    public function getDataUserId($id){
        $this->db->select("status");
        $this->db->from("tb_users");
        $this->db->where("id", $id);
        $data = $this->db->get();
        return $data;
    }

    public function getData($token){
        $this->db->select("*");
        $this->db->from("tb_users");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function insertData($data){
        $this->db->insert("tb_users", $data);
    }

    public function updateDataUsers($token, $data){
        $this->db->where("id", $token);
        $this->db->update("tb_users", $data);
    }

    public function deleteDataUsers($token){
        $this->db->where("id", $token);
        $this->db->delete("tb_users");
    }
}