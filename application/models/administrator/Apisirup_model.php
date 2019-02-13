<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apisirup_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', TRUE);
	}

	public function getProgram(){
		$this->db->select('a.*, UNIX_TIMESTAMP(a.updated) updated, SUM(b.jumlah) jumlah');
		$this->db->from('simda_program a');
		$this->db->join("simda_rincian_obyek b", "a.id=b.id_parent_prog");
		$this->db->group_by('a.id');
		return $this->db->get();
	}
}