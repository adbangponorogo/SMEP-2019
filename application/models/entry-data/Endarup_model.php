<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endarup_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }



    // *
    // =================================== //
    // ************ Main Data ************ //
    // =================================== //
    // *

    public function getAllDataSKPD(){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataSKPDByID($id_skpd){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where("id", $id_skpd);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }






    // *
    // =================================== //
    // ********* Categories Data ********* //
    // =================================== //
    // *

    public function getAllDataProgram($kd_skpd){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;   
    }

    public function getAllDataKegiatan($kd_skpd, $id_program){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("id_parent_prog", $id_program);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;   
    }

    public function getAllDataRincianObyek($kd_skpd, $id_program, $id_kegiatan){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("id_parent_prog", $id_program);
        $this->db->where("id_parent_keg", $id_kegiatan);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;   
    }

    public function getPaguRincianObyekByID($id_rincian_obyek){
        $this->db->select("kd_skpd, id, SUM(jumlah) as jumlah");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("id", $id_rincian_obyek);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;   
    }

    public function getPaguDataRUPByIDRO($kd_skpd, $id_ro){
        $this->db->select("SUM(pagu_paket) AS pagu_paket");
        $this->db->from("tb_rup");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("id_rincian_obyek", $id_ro);
        $this->db->where("is_aktif", 1);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;   
    }





    // *
    // =================================== //
    // ************* RUP Data ************ //
    // =================================== //
    // *

    public function getDataAllRUPCount($parameter){   
        $this->db->select("*");
        $this->db->from("tb_rup");
        if ($parameter["id_program"] != "all") {
            $this->db->where("id_program", $parameter["id_program"]);
        }
        if ($parameter["id_kegiatan"] != "all") {
            $this->db->where("id_kegiatan", $parameter["id_kegiatan"]);
        }
        if ($parameter["id_ro"] != "all") {
            $this->db->where("id_rincian_obyek", $parameter["id_ro"]);
        }
        $this->db->where("kd_skpd", $parameter["kd_skpd"]);
        $this->db->where("is_deleted", 0);
        $this->db->where("is_last_paket", 1);
        $data = $this->db->get();
        return $data->num_rows();
    }
    
    public function getDataAllRUP($limit, $start, $order, $dir, $parameter){
        $this->db->select("*");
        $this->db->from("tb_rup");
        if ($parameter["id_program"] != "all") {
            $this->db->where("id_program", $parameter["id_program"]);
        }
        if ($parameter["id_kegiatan"] != "all") {
            $this->db->where("id_kegiatan", $parameter["id_kegiatan"]);
        }
        if ($parameter["id_ro"] != "all") {
            $this->db->where("id_rincian_obyek", $parameter["id_ro"]);
        }
        $this->db->where("kd_skpd", $parameter["kd_skpd"]);
        $this->db->where("is_deleted", 0);
        $this->db->where("is_last_paket", 1);
        $this->db->limit($limit, $start);
        $this->db->order_by($order, $dir);
        $data = $this->db->get();
        if($data->num_rows()>0)
        {
            return $data->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    public function getDataAllRUPSearch($limit, $start, $search, $order, $dir, $parameter)
    {
        $this->db->select("*");
        $this->db->from("tb_rup");
        if ($parameter["id_program"] != "all") {
            $this->db->where("id_program", $parameter["id_program"]);
        }
        if ($parameter["id_kegiatan"] != "all") {
            $this->db->where("id_kegiatan", $parameter["id_kegiatan"]);
        }
        if ($parameter["id_ro"] != "all") {
            $this->db->where("id_rincian_obyek", $parameter["id_ro"]);
        }
        $this->db->where("kd_skpd", $parameter["kd_skpd"]);
        $this->db->where("is_deleted", 0);
        $this->db->where("is_last_paket", 1);
        $this->db->like('CONCAT(2019, id)', $search);
        $this->db->or_like('nama_paket', $search);
        $this->db->or_like('pagu_paket', $search);
        $this->db->or_like('jenis_belanja', $search);
        $this->db->limit($limit, $start);
        $this->db->order_by($order, $dir);
        $data = $this->db->get();
        if($data->num_rows()>0)
        {
            return $data->result(); 
        }
        else
        {
            return null;
        }
    }

    public function getDataAllRUPSearchCount($search,$parameter)
    {
        $this->db->select("*");
        $this->db->from("tb_rup");
        if ($parameter["id_program"] != "all") {
            $this->db->where("id_program", $parameter["id_program"]);
        }
        if ($parameter["id_kegiatan"] != "all") {
            $this->db->where("id_kegiatan", $parameter["id_kegiatan"]);
        }
        if ($parameter["id_ro"] != "all") {
            $this->db->where("id_rincian_obyek", $parameter["id_ro"]);
        }
        $this->db->where("kd_skpd", $parameter["kd_skpd"]);
        $this->db->where("is_deleted", 0);
        $this->db->where("is_last_paket", 1);
        $this->db->like('CONCAT(2019, id)', $search);
        $this->db->or_like('nama_paket',$search);
        $this->db->or_like('pagu_paket',$search);
        $this->db->or_like('jenis_belanja',$search);
        $data = $this->db->get();
        return $data->num_rows();
    }

    public function getDataRUPByID($id_rup){
        $this->db->select("a.*, b.kd_gabungan as kd_program, b.keterangan_program, c.kd_gabungan as kd_kegiatan, c.keterangan_kegiatan, d.kd_rekening, d.nama_rekening");
        $this->db->from("tb_rup a");
        $this->db->join("simda_program b", "a.id_program = b.id");
        $this->db->join("simda_kegiatan c", "a.id_kegiatan = c.id");
        $this->db->join("simda_rincian_obyek d", "a.id_rincian_obyek = d.id");
        $this->db->where("a.id", $id_rup);
        $data = $this->db->get();
        return $data;
    }

    public function getDataAllRUPDistinctIDRUPAwal(){
        $this->db->select("DISTINCT(id_rup_awal) AS id_rup_awal");
        $this->db->from("tb_rup");
        $this->db->where("id_rup_awal >", 0);
        $data = $this->db->get();
        return $data;
    }

    public function getDataAllRUPDistinctID($id_rup_awal){
        $this->db->select("DISTINCT(id) AS id");
        $this->db->from("tb_rup");
        $this->db->where("id_rup_awal >", 0);
        $this->db->where("id_rup_awal", $id_rup_awal);
        $data = $this->db->get();
        return $data;
    }

    public function getDataIDRUPByIDRUPAwal($id_rup_awal){
        $this->db->select("id");
        $this->db->from("tb_rup");
        $this->db->where("id_rup_awal", $id_rup_awal);
        $data = $this->db->get();
        return $data;
    }




    // *
    // =================================== //
    // ************* PPK Data ************ //
    // =================================== //
    // *

    public function getDataPPKByIDSKPD($id_skpd){
        $this->db->select("a.*");
        $this->db->from("v_auth a");
        $this->db->join("simda_skpd b", "a.id_skpd = b.id");
        $this->db->where("a.status", 3);
        $this->db->where("b.id", $id_skpd);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPPKByID($id_ppk){
        $this->db->select("a.*");
        $this->db->from("v_auth a");
        $this->db->join("simda_skpd b", "a.id_skpd = b.id");
        $this->db->where("a.status", 3);
        $this->db->where("a.id", $id_ppk);
        $data = $this->db->get();
        return $data;
    }





    // *
    // =================================== //
    // ******* SKPD Swakelola Data ******* //
    // =================================== //
    // *

    public function getDataSKPDSwakelola($id_skpd){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where_not_in("id", $id_skpd);
        $data = $this->db->get();
        return $data;
    }





    // *
    // =================================== //
    // ***** Struktur Anggaran Data ****** //
    // =================================== //
    // *

    public function getDataStrukturAnggaran($kd_skpd){
        $this->db->select("*");
        $this->db->from("sirup_struktur_anggaran");
        $this->db->where("kd_skpd", $kd_skpd);
        $data = $this->db->get();
        return $data;
    }





    // *
    // =================================== //
    // ********* Insert RUP Data ********* //
    // =================================== //
    // *

    public function insertRUPData($data){
        $this->db->insert('tb_rup', $data);
        return TRUE;
    }





    // *
    // =================================== //
    // ********* Revisi RUP Data ********* //
    // =================================== //
    // *

    public function insertRevisiRUPData($data){
        $this->db->insert('tb_rup', $data);
        $last_id = $this->db->insert_id();
        return array("status" => TRUE, "id_rup_baru" => $last_id);
    }

    public function updateRUPData($id, $data){
        $this->db->where("id", $id);
        $this->db->update('tb_rup', $data);
        return TRUE;
    }

    public function updateRealisasiRUPData($id, $data){
        $this->db->where("id_rup", $id);
        $this->db->update('tb_realisasi_rup', $data);
        return TRUE;
    }

    public function getAllDataHistoryRevisiRUP($id_rup_awal){
        $this->db->select("*");
        $this->db->from("tb_rup_revisi");
        $this->db->where("id_rup_awal", $id_rup_awal);
        $data = $this->db->get();
        return $data;
    }

    public function insertHistoryRUPData($data){
        $this->db->insert('tb_rup_revisi', $data);
        return TRUE;
    }




    // *
    // =================================== //
    // *********** Delete Junks ********** //
    // =================================== //
    // *

    public function deleteJunks(){
        $result_rup = $this->getAllDataRUPIDProgramZero();
        foreach ($result_rup->result() as $rows_rup) {
            $delete_history = $this->deleteHistoryRevisiByIDBaru($rows_rup->id);
            if ($delete_history == TRUE) {
                $delete_rup = $this->deleteRUPByID($rows_rup->id);
                if ($delete_rup == TRUE) {
                    $result_rup_distinct = $this->getDataAllRUPDistinctIDRUPAwal();
                    foreach ($result_rup_distinct->result() as $rows_rup_distinct) {
                        $result_rup_distinct_id = $this->getDataAllRUPDistinctID($rows_rup_distinct->id_rup_awal);
                        foreach ($result_rup_distinct_id->result() as $rows_rup_distinct_id) {
                            $result_history = $this->getAllDataHistoryRevisiRUP($rows_rup_distinct->id_rup_awal);
                            $number = 1;
                            foreach ($result_history->result() as $rows_history) {
                                if ($rows_history->id_rup_sebelumnya == $rows_rup_distinct_id->id) {
                                    if ($rows_history->id_rup_sebelumnya > $rows_rup_distinct_id->id) {
                                        $id_rup_sebelumnya = $rows_rup_distinct_id->id;
                                    }
                                    else{
                                        $id_rup_sebelumnya = $rows_history->id_rup_sebelumnya;
                                    }
                                }
                                else{
                                    $this->deleteHistoryRevisiByIDSebelumnya($rows_history->id_rup_sebelumnya);
                                }
                                $this->updateHistoryRevisi($rows_history->id, array("id_rup_sebelumnya" => $id_rup_sebelumnya, "revisi_ke" => $number++));
                            }
                        }
                    }
                }
            }
        }
        return TRUE;
    }

    public function getAllDataRUPIDProgramZero(){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_program", 0);
        $data = $this->db->get();
        return $data;
    }

    public function deleteHistoryRevisiByIDSebelumnya($id_rup_sebelumnya){
        $this->db->where("id_rup_sebelumnya", $id_rup_sebelumnya);
        $this->db->delete("tb_rup_revisi");
        return TRUE;
    }

    public function deleteHistoryRevisiByIDBaru($id_rup_baru){
        $this->db->where("id_rup_baru", $id_rup_baru);
        $this->db->delete("tb_rup_revisi");
        return TRUE;
    }

    public function deleteRUPByID($id_rup){
        $this->db->where("id", $id_rup);
        $this->db->delete("tb_rup");
        return TRUE;
    }

    public function updateHistoryRevisi($id, $data){
        $this->db->where("id", $id);
        $this->db->update("tb_rup_revisi", $data);
        return TRUE;
    }
}