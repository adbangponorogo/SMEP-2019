<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tepraperencanaan_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function getBelanja($kd_skpd){
		$this->db->select('sum(btl1) as btl1, sum(btl2) as btl2, sum(bl1) as bl1, sum(bl2) as bl2, sum(bl3) as bl3');
		$this->db->from('sirup_struktur_anggaran');
		if ($kd_skpd != 'all') {
			$this->db->where('kd_skpd', $kd_skpd);
		}
		return $this->db->get();
	}

	public function getBM($kd_skpd){
		$this->db->select('COUNT(*) jml');
		$this->db->from('tb_rup');
		if ($kd_skpd != 'all') {
			$this->db->where('kd_skpd', $kd_skpd);
		}
		$this->db->where('jenis_belanja', 3);// 3 = Belanja Modal
		$this->db->where('is_aktif', 1);// 3 = Belanja Modal
		return $this->db->get();
	}

	public function getBP($kd_skpd){
		$this->db->select('COUNT(*) jml');
		$this->db->from('tb_rup');
		if ($kd_skpd != 'all') {
			$this->db->where('kd_skpd', $kd_skpd);
		}
		$this->db->where('jenis_belanja', 2);// 1 = Belanja Pegawai
		$this->db->where('is_aktif', 1);// 1 = Belanja Pegawai
		return $this->db->get();
	}

	public function getPaket($kd_skpd, $cara_pengadaan, $range_pagu_atas, $range_pagu_bawah=0){
		$this->db->select('jenis_pengadaan, COUNT(*) jml, SUM(pagu_paket) pagu');
		$this->db->from('tb_rup');
		if ($kd_skpd != 'all') {
            $this->db->where('kd_skpd', $kd_skpd);
        }
        $get_cara_pengadaan = 2;
		if (strtolower($cara_pengadaan) == 'penyedia'){
			$get_cara_pengadaan = 1;
        }
		if ($range_pagu_atas > 0){
			$this->db->where('pagu_paket<=', $range_pagu_atas);
        }
		$this->db->where('pagu_paket>', $range_pagu_bawah);
		$this->db->where('cara_pengadaan', $get_cara_pengadaan);
        $this->db->where('is_aktif', 1);
		$this->db->group_by('jenis_pengadaan');
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

    public function getDataSKPDUnique($id_skpd){
        $this->db->select("a.*");
        $this->db->from("simda_skpd a");
        $this->db->join("tb_skpd_urutan b", "a.kd_skpd = b.kd_skpd");
        if ($id_skpd != "all") {
            $this->db->where("a.id", $id_skpd);
        }
        $this->db->where("b.urutan >", 0);
        $this->db->order_by('b.urutan', 'ASC');
        $data = $this->db->get();
        return $data;
    }

    public function getDataProgramUnique($token){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataKegiatanUnique($token){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("id", $token);
        $data = $this->db->get();
        return $data;
    }

    public function getDataRefRUP($kd_skpd){
        $this->db->select("sum(btl1) as btl1, sum(btl2) as btl2, sum(bl1) as bl1, sum(bl2) as bl2, sum(bl3) as bl3");
        $this->db->from("sirup_struktur_anggaran");
        if ($kd_skpd != 'all') {
            $this->db->where("kd_skpd", $kd_skpd);
        }
        $data = $this->db->get();
        return $data;
    }

    public function getDataRUP($id_skpd, $metode_pemilihan){
        $this->db->select("*");
        $this->db->from("tb_rup");
        if ($id_skpd != 'all') {
            $this->db->where("id_skpd", $id_skpd);
        }
        $this->db->where("metode_pemilihan", $metode_pemilihan);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }

    public function getDataPaguRUP($id_skpd, $metode_pemilihan){
        $this->db->select("sum(pagu_paket) as pagu_paket");
        $this->db->from("tb_rup");
        if ($id_skpd != 'all') {
            $this->db->where("id_skpd", $id_skpd);
        }
        $this->db->where("metode_pemilihan", $metode_pemilihan);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }

    public function getDataBelanjaRUP($id_skpd, $jenis_belanja){
        $this->db->select("count(id) as jumlah, sum(pagu_paket) as pagu_paket");
        $this->db->from("tb_rup");
        if ($id_skpd != 'all') {
            $this->db->where("id_skpd", $id_skpd);
        }
        $this->db->where("jenis_belanja", $jenis_belanja);
        $this->db->where("is_aktif", 1);
        $data = $this->db->get();
        return $data;
    }


    public function getDataRekapRUP($kd_skpd, $cara_pengadaan, $jenis_pengadaan, $range_pagu_atas, $range_pagu_bawah = 0){
        // if ($id_skpd != 'all') {
        //     $skpd = 'id_skpd = '.$id_skpd." and ";
        // }
        // else{
        //     $skpd = '';
        // }
        // $data = $this->db->query("select count(id) as jumlah, sum(pagu_paket) as pagu_paket from tb_rup where ".$skpd."cara_pengadaan = ".$cara_pengadaan." ".$max_pagu." and jenis_pengadaan = ".$jenis_pengadaan." AND is_aktif = 1");
        // return $data;

        $this->db->select('COUNT(*) AS jumlah, SUM(pagu_paket) AS pagu_paket');
        $this->db->from('tb_rup');
        if ($kd_skpd != 'all') {
            $this->db->where('kd_skpd', $kd_skpd);
        }
        if ($range_pagu_atas != 0 && $range_pagu_atas > 0){
            $this->db->where('pagu_paket <=', $range_pagu_atas);
        }
        $this->db->where('pagu_paket >', $range_pagu_bawah);
        $this->db->where('cara_pengadaan', $cara_pengadaan);
        $this->db->where('jenis_pengadaan', $jenis_pengadaan);
        $this->db->where('is_aktif', 1);
        $this->db->group_by('jenis_pengadaan');
        return $this->db->get();
    }

    public function getDataRekapTotalRUP($kd_skpd, $jenis_pengadaan){
        $this->db->select('COUNT(*) AS jumlah, SUM(pagu_paket) AS pagu_paket');
        $this->db->from('tb_rup');
        if ($kd_skpd != 'all') {
            $this->db->where('kd_skpd', $kd_skpd);
        }
        $this->db->where('jenis_pengadaan', $jenis_pengadaan);
        $this->db->where('is_aktif', 1);
        $this->db->group_by('jenis_pengadaan');
        return $this->db->get();
    }
}