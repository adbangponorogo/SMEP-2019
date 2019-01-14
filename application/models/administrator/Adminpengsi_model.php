<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpengsi_model extends CI_Model {

	public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
        $this->old_db = $this->load->database('smep_old', TRUE);
    }

    public function getDataSKPD($token){
        $this->db->select("a.id, a.kd_skpd, a.nama_skpd");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        if ($token != 'all') {
            $this->db->where("a.id", $token);
        }
        $this->db->where("b.urutan >", 0);
        $this->db->order_by("b.urutan", "ASC");
        $data = $this->db->get();
        return $data;
    }


    public function getMainData(){
        $this->db->select("a.id, a.kd_skpd, a.nama_skpd");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        $this->db->order_by("b.urutan");
        $this->db->where("b.urutan >", 0);
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
        $this->db->from("v_auth");
        $this->db->where("id_skpd", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataUser($id_skpd){
        $this->db->select("*");
        $this->db->from("v_auth");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->order_by("tanggal_buat", 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataUserUnique($username){
        $this->db->select("*");
        $this->db->from("v_auth");
        $this->db->where("username", $username);
        $data = $this->db->get();
        return $data;
    }

    public function getDataUserPPK($id){
        $this->db->select("*");
        $this->db->from("v_auth");
        if ($id != "all") {
            $this->db->where("id", $id);
        }
        else{
            $this->db->where("id_skpd", "-");
        }
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getDataUserId($id){
        $this->db->select("status");
        $this->db->from("v_auth");
        $this->db->where("id", $id);
        $data = $this->db->get();
        return $data;
    }

    public function getData($token){
        $this->db->select("*");
        $this->db->from("v_auth");
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




    // Generate

    public function selectSKPD(){
        $this->db->select("a.*");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        $this->db->where("b.urutan >", 0);
        $this->db->order_by("b.urutan", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function selectUsersOld($kd_skpd){
        $this->old_db->select("*");
        $this->old_db->from("ta_user");
        $this->old_db->where("kd_skpd", $kd_skpd);
        $this->old_db->where("jabatan", 2);
        $data = $this->old_db->get();
        return $data;
    }

    public function selectSKPDWithKD($kd_skpd){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function selectUsers($id_skpd, $username){
        $this->db->select("*");
        $this->db->from("tb_users");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("username", $username);
        $data = $this->db->get();
        return $data;
    }

    public function insertUsers($data){
        $this->db->insert("tb_users", $data);
    }
}