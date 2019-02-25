<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apisirup_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
	}

	public function getProgram($tahun, $date, $dateEnd){
		$this->db->select('a.*, UNIX_TIMESTAMP(a.updated) updated, SUM(b.jumlah) jumlah');
		$this->db->from('simda_program a');
		$this->db->join("simda_rincian_obyek b", "a.id=b.id_parent_prog");
		$this->db->where("a.tahun", $tahun);
		if ($dateEnd == NULL) {
			$this->db->where("date_format(a.updated, '%Y-%m-%d') = ", date("Y-m-d", $date));
		}
		if ($dateEnd != NULL) {
			$this->db->where("a.updated BETWEEN '".date("Y-m-d", $date)." 00:00:01' AND '".date("Y-m-d", $dateEnd)." 23:59:59'");
		}
		$this->db->group_by('a.id');
		return $this->db->get();
	}

	public function getKegiatan($tahun, $date, $dateEnd){
		$this->db->select('a.*, b.id_sirup as id_sirup_program, UNIX_TIMESTAMP(a.updated) as updated, SUM(c.jumlah) as jumlah');
		$this->db->from('simda_kegiatan a');
		$this->db->join('simda_program b', 'a.id_parent_prog = b.id');
		$this->db->join('simda_rincian_obyek c', 'a.id = c.id_parent_keg');
		$this->db->where("a.tahun", $tahun);
		if ($dateEnd == NULL) {
			$this->db->where("date_format(a.updated, '%Y-%m-%d') = ", date("Y-m-d", $date));
		}
		if ($dateEnd != NULL) {
			$this->db->where("a.updated BETWEEN '".date("Y-m-d", $date)." 00:00:01' AND '".date("Y-m-d", $dateEnd)." 23:59:59'");
		}
		$this->db->group_by('a.id');
		return $this->db->get();
	}

	public function getObjekAkun($tahun, $date, $dateEnd){
		$this->db->select("a.*, b.id_sirup as id_sirup_program, c.id_sirup as id_sirup_kegiatan, UNIX_TIMESTAMP(a.updated) as updated");
		$this->db->from("simda_rincian_obyek a");
		$this->db->join("simda_program b", "a.id_parent_prog = b.id");
		$this->db->join("simda_kegiatan c", "a.id_parent_keg = c.id");
		$this->db->where("a.tahun", $tahun);
		if ($dateEnd == NULL) {
			$this->db->where("date_format(a.updated, '%Y-%m-%d') = ", date("Y-m-d", $date));
		}
		if ($dateEnd != NULL) {
			$this->db->where("a.updated BETWEEN '".date("Y-m-d", $date)." 00:00:01' AND '".date("Y-m-d", $dateEnd)." 23:59:59'");
		}
		$this->db->group_by("a.id");
		return $this->db->get();
	}

	public function getRincianObjekAkun($tahun, $date, $dateEnd){
		$this->db->select("a.*, d.kd_rekening as kd_rekening, b.id_sirup as id_sirup_program, c.id_sirup as id_sirup_kegiatan, d.id_sirup as id_sirup_ro, UNIX_TIMESTAMP(a.tanggal_update) as updated");
		$this->db->from("tb_rup a");
		$this->db->join("simda_program b", "b.id = a.id_program");
		$this->db->join("simda_kegiatan c", "c.id = a.id_kegiatan");
		$this->db->join("simda_rincian_obyek d", "d.id = a.id_rincian_obyek");
		$this->db->where("a.tahun", $tahun);
		if ($dateEnd == NULL) {
			$this->db->where("date_format(a.tanggal_update, '%Y-%m-%d') = ", date("Y-m-d", $date));
		}
		if ($dateEnd != NULL) {
			$this->db->where("a.tanggal_update BETWEEN '".date("Y-m-d", $date)." 00:00:01' AND '".date("Y-m-d", $dateEnd)." 23:59:59'");
		}
		$this->db->group_by("a.id");
		return $this->db->get();
	}

	public function getPenyedia($tahun, $date, $dateEnd){
		$this->db->select("a.*, b.id_sirup as id_sirup_kegiatan, c.id_sirup as id_sirup_ro, UNIX_TIMESTAMP(a.pelaksanaan_pengadaan_awal) as pelaksanaan_pengadaan_awal, UNIX_TIMESTAMP(a.pelaksanaan_pengadaan_akhir) as pelaksanaan_pengadaan_akhir, UNIX_TIMESTAMP(a.pelaksanaan_kontrak_awal) as pelaksanaan_kontrak_awal, UNIX_TIMESTAMP(a.pelaksanaan_kontrak_akhir) as pelaksanaan_kontrak_akhir, UNIX_TIMESTAMP(a.pelaksanaan_pemanfaatan) as pelaksanaan_pemanfaatan, UNIX_TIMESTAMP(a.tanggal_update) as updated");
		$this->db->from("tb_rup a");
		$this->db->join("simda_kegiatan b", "a.id_kegiatan = b.id");
		$this->db->join("simda_rincian_obyek c", "a.id_rincian_obyek = c.id");
		$this->db->where("a.cara_pengadaan", 1);
		$this->db->where("a.tahun", $tahun);
		if ($dateEnd == NULL) {
			$this->db->where("date_format(a.tanggal_update, '%Y-%m-%d') = ", date("Y-m-d", $date));
		}
		if ($dateEnd != NULL) {
			$this->db->where("a.tanggal_update BETWEEN '".date("Y-m-d", $date)." 00:00:01' AND '".date("Y-m-d", $dateEnd)." 23:59:59'");
		}
		$this->db->group_by("b.id");
		return $this->db->get();
	}

	public function getSwakelola($tahun, $date, $dateEnd){
		$this->db->select("a.*, b.id_sirup as id_sirup_kegiatan, c.id_sirup as id_sirup_ro, UNIX_TIMESTAMP(a.pelaksanaan_pekerjaan_awal) as pelaksanaan_pekerjaan_awal, UNIX_TIMESTAMP(a.pelaksanaan_pekerjaan_akhir) as pelaksanaan_pekerjaan_akhir, UNIX_TIMESTAMP(a.tanggal_update) as updated");
		$this->db->from("tb_rup a");
		$this->db->join("simda_kegiatan b", "a.id_kegiatan = b.id");
		$this->db->join("simda_rincian_obyek c", "a.id_rincian_obyek = c.id");
		$this->db->where("a.cara_pengadaan", 2);
		$this->db->where("a.tahun", $tahun);
		if ($dateEnd == NULL) {
			$this->db->where("date_format(a.tanggal_update, '%Y-%m-%d') = ", date("Y-m-d", $date));
		}
		if ($dateEnd != NULL) {
			$this->db->where("a.tanggal_update BETWEEN '".date("Y-m-d", $date)." 00:00:01' AND '".date("Y-m-d", $dateEnd)." 23:59:59'");
		}
		$this->db->group_by("b.id");
		return $this->db->get();
	}

	public function getPPK($tahun, $date, $dateEnd){
		$this->db->select("b.*, a.id_skpd as id_skpd, UNIX_TIMESTAMP(b.tanggal_buat) as updated");
		$this->db->from("v_auth a");
		$this->db->join("spse_pegawai b", "a.id = b.id");
		$this->db->where("a.status", 3);
		$this->db->where("date_format(b.tanggal_buat, '%Y') = ", $tahun);
		if ($dateEnd == NULL) {
			$this->db->where("date_format(b.tanggal_buat, '%Y-%m-%d') = ", date("Y-m-d", $date));
		}
		if ($dateEnd != NULL) {
			$this->db->where("b.tanggal_buat BETWEEN '".date("Y-m-d", $date)." 00:00:01' AND '".date("Y-m-d", $dateEnd)." 23:59:59'");
		}
		$this->db->group_by("b.id");
		return $this->db->get();
	}

	public function getStrukturAnggaran($tahun, $date, $dateEnd){
		$this->db->select("*");
		$this->db->from("simda_ref_master_rup");
		$this->db->where("tahun", $tahun);
		if ($dateEnd == NULL) {
			$this->db->where("date_format(updated, '%Y-%m-%d') = ", date("Y-m-d", $date));
		}
		if ($dateEnd != NULL) {
			$this->db->where("updated BETWEEN '".date("Y-m-d", $date)." 00:00:01' AND '".date("Y-m-d", $dateEnd)." 23:59:59'");
		}
		$this->db->group_by("id");
		return $this->db->get();
	}



	// ============ MISC ============ //
	public function getSKPD($id){
		$this->db->select("*");
		$this->db->from("simda_skpd");
		$this->db->where("id", $id);
		$this->db->group_by("id");
		return $this->db->get();	
	}

	public function getPPKByID($id){
		$this->db->select("*");
		$this->db->from("v_auth");
		$this->db->where("id", $id);
		$this->db->group_by("id");
		return $this->db->get();
	}
}