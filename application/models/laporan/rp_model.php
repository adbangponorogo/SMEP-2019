<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class rp_model extends CI_Model {

	public function __construct()
    {
        $this->load->database();
    }

    public function getSKPD($id_skpd){
        $this->db->select('*');
        $this->db->from('simda_skpd');
        $this->db->where('id', $id_skpd);
        return $this->db->get();
    }

    public function getProg($kd_skpd, $jenis_pengadaan){
        $this->db->select('a.*');
        $this->db->from('simda_program a');
        $this->db->join("tb_rup b", "a.id = b.id_program");
        $this->db->group_by('a.id');
        $this->db->where('a.kd_skpd', $kd_skpd);
        $this->db->where('b.jenis_pengadaan', $jenis_pengadaan);
        return $this->db->get();
    }

    public function getKeg($kd_skpd, $id_program, $jenis_pengadaan){
        $this->db->select('a.*, c.nama');
        $this->db->from('simda_kegiatan a');
        $this->db->join("tb_rup b", "a.kd_skpd = b.kd_skpd AND a.id = b.id_kegiatan");
        $this->db->join("tb_pptk c", "a.id_skpd = c.id_skpd");
        $this->db->join("tb_pptk_kegiatan d", "c.id = d.id_pptk");
        $this->db->group_by('a.id');
        $this->db->where('a.kd_skpd', $kd_skpd);
        $this->db->where('b.id_program', $id_program);
        $this->db->where('b.jenis_pengadaan', $jenis_pengadaan);
        return $this->db->get();
    }

    public function getPaket($kd_skpd, $id_keg, $jenis_pengadaan){
        $this->db->select('*');
        $this->db->from('tb_rup');
        $this->db->where('kd_skpd', $kd_skpd);
        $this->db->where('id_kegiatan', $id_keg);
        $this->db->where('jenis_pengadaan', $jenis_pengadaan);
        return $this->db->get();
    }

    public function getKaSKPD($id_skpd){
        $this->db->select('*');
        $this->db->from('tb_pptk');
        $this->db->where('id_skpd', $id_skpd);
        return $this->db->get();
    }

    public function getDataUser($token){
        $this->db->select("*");
        $this->db->from("v_auth");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataSKPD(){
        $this->db->select("a.*");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        $this->db->where("b.urutan >", 0);
        $this->db->order_by("b.urutan", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getDataSKPDUnique($skpd){
        $this->db->select("a.*");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        if ($skpd != "all") {
            $this->db->where("a.id", $skpd);
        }
        $this->db->order_by('b.urutan', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataProgramUnique($kd_skpd, $jenis_pengadaan){
        $this->db->select("distinct(a.id), a.*");
        $this->db->from("simda_program a");
        $this->db->join("tb_rup b", "a.id = b.id_program");
        $this->db->where("a.kd_skpd", $kd_skpd);
        $this->db->where("b.jenis_pengadaan", $jenis_pengadaan);
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUnique($id_program, $kd_program){
        $this->db->select("distinct(a.id), a.*");
        $this->db->from("simda_kegiatan a");
        $this->db->join("tb_rup b", "a.id = b.id_kegiatan");
        $this->db->where("a.id_program", $id_program);
        $this->db->where("a.kd_program", $kd_program);
        $this->db->order_by('a.id', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUP($id_skpd, $id_program, $id_kegiatan, $jenis_pengadaan, $bulan){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("id_program", $id_program);
        $this->db->where("id_kegiatan", $id_kegiatan);
        $this->db->where("jenis_pengadaan", $jenis_pengadaan);
        if ($bulan != 'all') {
            $this->db->where("date_format(tanggal_buat, '%m') = ", $bulan);
        }
        $this->db->order_by("id");
        $data = $this->db->get();
        return $data;
    }

    public function getDataPPTKKegiatan($id_kegiatan){
        $this->db->select("b.nama as nama");
        $this->db->from("tb_pptk_kegiatan a");
        $this->db->join("tb_pptk b", "a.id_pptk=b.id");
        $this->db->where("a.id_kegiatan", $id_kegiatan);
        $data = $this->db->get();
        return $data;
    }
}