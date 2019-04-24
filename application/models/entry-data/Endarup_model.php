<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endarup_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

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

    public function getAllDataSKPDByNotIn($id_skpd){
        $this->db->select("*");
        $this->db->from("simda_skpd");
        $this->db->where_not_in("id", $id_skpd);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataProgram($kd_skpd){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataProgramByID($id_program){
        $this->db->select("*");
        $this->db->from("simda_program");
        $this->db->where("id", $id_program);
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

    public function getAllDataKegiatanByID($id_kegiatan){
        $this->db->select("*");
        $this->db->from("simda_kegiatan");
        $this->db->where("id", $id_kegiatan);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataRO($kd_skpd, $id_program, $id_kegiatan){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->where("id_parent_prog", $id_program);
        $this->db->where("id_parent_keg", $id_kegiatan);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }


    public function getAllDataROByID($id_ro){
        $this->db->select("*");
        $this->db->from("simda_rincian_obyek");
        $this->db->where("id", $id_ro);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataRUP($kd_skpd, $id_program, $id_kegiatan, $id_ro){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("kd_skpd", $kd_skpd);
        if ($id_program != 'all') {
            $this->db->where("id_program", $id_program);
        }
        if ($id_kegiatan != 'all') {
            $this->db->where("id_kegiatan", $id_kegiatan);
        }
        if ($id_ro != 'all') {
            $this->db->where("id_rincian_obyek", $id_ro);
        }
        $this->db->where("is_deleted", 0);
        $this->db->where("is_last_paket", 1);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataRUPByID($id_rup){
        $this->db->select("a.*, b.kd_gabungan as kd_program, b.keterangan_program as nama_program, c.kd_gabungan as kd_kegiatan, c.keterangan_kegiatan as nama_kegiatan, d.kd_rekening as kd_rekening, d.nama_rekening as nama_rekening, d.jumlah as pagu_jumlah");
        $this->db->from("tb_rup a");
        $this->db->join("simda_program b", "a.id_program = b.id");
        $this->db->join("simda_kegiatan c", "a.id_kegiatan = c.id");
        $this->db->join("simda_rincian_obyek d", "a.id_rincian_obyek = d.id");
        $this->db->where("a.id", $id_rup);
        $this->db->order_by("a.id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataRUPByIDRO($id_ro){
        $this->db->select("sum(pagu_paket) as total_paket");
        $this->db->from("tb_rup");
        $this->db->where("id_rincian_obyek", $id_ro);
        $this->db->where("is_aktif", 1);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataPPK($id, $id_skpd){
        $this->db->select("*");
        $this->db->from("v_auth");
        if ($id != '') {
            $this->db->where("id", $id);
        }
        $this->db->where("id_skpd", $id_skpd);
        $this->db->where("status", 3);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataRefMasterRUP($kd_skpd){
        $this->db->select("*");
        $this->db->from("sirup_struktur_anggaran");
        $this->db->where("kd_skpd", $kd_skpd);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataHistoryRevisiRUP($id_rup_awal){
        $this->db->select("*");
        $this->db->from("tb_rup_revisi");
        $this->db->where("id_rup_awal", $id_rup_awal);
        $this->db->order_by("id", "DESC");
        $this->db->limit(1);
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataHistoryRevisiEditRUP($id_rup_awal){
        $this->db->select("*");
        $this->db->from("tb_rup_revisi");
        $this->db->where("id_rup_awal", $id_rup_awal);
        $this->db->where("id_rup_awal", $id_rup_awal);
        $this->db->order_by("tipe", "SATUKESATU");
        $data = $this->db->get();
        return $data;
    }

    public function getAllDataRealisasiRUPByIDRUP($id_rup){
        $this->db->select("*");
        $this->db->from("tb_realisasi_rup");
        $this->db->where("id_rup", $id_rup);
        $this->db->order_by("id", "ASC");
        $data = $this->db->get();
        return $data;
    }




    public function insertData($data){
        $this->db->insert("tb_rup", $data);
    }

    public function insertDataHistoryPaket($data){
        $this->db->insert("tb_rup_revisi", $data);
    }

    public function updateData($data){
        
        // Get Data
        $get_data = array(
                        "tahun"                       => $data["tahun"],
                        "id_skpd"                     => $data["id_skpd"],
                        "kd_skpd"                     => $data["kd_skpd"],
                        "id_program"                  => $data["id_program"],
                        "id_kegiatan"                 => $data["id_kegiatan"],
                        "id_rincian_obyek"            => $data["id_rincian_obyek"],
                        "id_user_ppk"                 => $data["id_user_ppk"],
                        "nama_paket"                  => $data["nama_paket"],
                        "volume_pekerjaan"            => $data["volume_pekerjaan"],
                        "jumlah_paket"                => $data["jumlah_paket"],
                        "uraian_pekerjaan"            => $data["uraian_pekerjaan"],
                        "spesifikasi_pekerjaan"       => $data["spesifikasi_pekerjaan"],
                        "lokasi_pekerjaan"            => $data["lokasi_pekerjaan"],
                        "produk_dalam_negeri"         => $data["produk_dalam_negeri"],
                        "usaha_kecil"                 => $data["usaha_kecil"],
                        "sumber_dana"                 => $data["sumber_dana"],
                        "pra_dipa"                    => $data["pra_dipa"],
                        "nomor_renja"                 => $data["nomor_renja"],
                        "no_izin_tahun_jamak"         => $data["no_izin_tahun_jamak"],
                        "kd_mak"                      => $data["kd_mak"],
                        "pagu_paket"                  => $data["pagu_paket"],
                        "cara_pengadaan"              => $data["cara_pengadaan"],
                        "tipe_swakelola"              => $data["tipe_swakelola"],
                        "id_skpd_swakelola"           => $data["id_skpd_swakelola"],
                        "jenis_belanja"               => $data["jenis_belanja"],
                        "jenis_pengadaan"             => $data["jenis_pengadaan"],
                        "metode_pemilihan"            => $data["metode_pemilihan"],
                        "umumkan_paket"               => $data["umumkan_paket"],
                        "pelaksanaan_pengadaan_awal"  => $data["pelaksanaan_pengadaan_awal"],
                        "pelaksanaan_pengadaan_akhir" => $data["pelaksanaan_pengadaan_akhir"],
                        "pelaksanaan_kontrak_awal"    => $data["pelaksanaan_kontrak_awal"],
                        "pelaksanaan_kontrak_akhir"   => $data["pelaksanaan_kontrak_akhir"],
                        "pelaksanaan_pemanfaatan"     => $data["pelaksanaan_pemanfaatan"],
                        "pelaksanaan_pekerjaan_awal"  => $data["pelaksanaan_pekerjaan_awal"],
                        "pelaksanaan_pekerjaan_akhir" => $data["pelaksanaan_pekerjaan_akhir"],
                        "id_rup_awal"                 => $data["id_rup_awal"],
                        "id_rup_sebelumnya"           => $data["id_rup_sebelumnya"],
                        "is_aktif"                    => $data["is_aktif"],
                        "is_last_paket"               => $data["is_last_paket"],
                        "alasan_revisi"               => $data["alasan_revisi"]
                    );


        // Create Data
        $create_data = array(
                        "tahun"                       => $get_data["tahun"],
                        "id_skpd"                     => $get_data["id_skpd"],
                        "kd_skpd"                     => $get_data["kd_skpd"],
                        "id_program"                  => $get_data["id_program"],
                        "id_kegiatan"                 => $get_data["id_kegiatan"],
                        "id_rincian_obyek"            => $get_data["id_rincian_obyek"],
                        "id_user_ppk"                 => $get_data["id_user_ppk"],
                        "nama_paket"                  => $get_data["nama_paket"],
                        "volume_pekerjaan"            => $get_data["volume_pekerjaan"],
                        "jumlah_paket"                => $get_data["jumlah_paket"],
                        "uraian_pekerjaan"            => $get_data["uraian_pekerjaan"],
                        "spesifikasi_pekerjaan"       => $get_data["spesifikasi_pekerjaan"],
                        "lokasi_pekerjaan"            => $get_data["lokasi_pekerjaan"],
                        "produk_dalam_negeri"         => $get_data["produk_dalam_negeri"],
                        "usaha_kecil"                 => $get_data["usaha_kecil"],
                        "sumber_dana"                 => $get_data["sumber_dana"],
                        "pra_dipa"                    => $get_data["pra_dipa"],
                        "nomor_renja"                 => $get_data["nomor_renja"],
                        "no_izin_tahun_jamak"         => $get_data["no_izin_tahun_jamak"],
                        "kd_mak"                      => $get_data["kd_mak"],
                        "pagu_paket"                  => $get_data["pagu_paket"],
                        "cara_pengadaan"              => $get_data["cara_pengadaan"],
                        "tipe_swakelola"              => $get_data["tipe_swakelola"],
                        "id_skpd_swakelola"           => $get_data["id_skpd_swakelola"],
                        "jenis_belanja"               => $get_data["jenis_belanja"],
                        "jenis_pengadaan"             => $get_data["jenis_pengadaan"],
                        "metode_pemilihan"            => $get_data["metode_pemilihan"],
                        "umumkan_paket"               => $get_data["umumkan_paket"],
                        "pelaksanaan_pengadaan_awal"  => $get_data["pelaksanaan_pengadaan_awal"],
                        "pelaksanaan_pengadaan_akhir" => $get_data["pelaksanaan_pengadaan_akhir"],
                        "pelaksanaan_kontrak_awal"    => $get_data["pelaksanaan_kontrak_awal"],
                        "pelaksanaan_kontrak_akhir"   => $get_data["pelaksanaan_kontrak_akhir"],
                        "pelaksanaan_pemanfaatan"     => $get_data["pelaksanaan_pemanfaatan"],
                        "pelaksanaan_pekerjaan_awal"  => $get_data["pelaksanaan_pekerjaan_awal"],
                        "pelaksanaan_pekerjaan_akhir" => $get_data["pelaksanaan_pekerjaan_akhir"],
                        "id_rup_awal"                 => $get_data["id_rup_awal"]
                    );
        $this->db->insert("tb_rup", $create_data);
        $rup_last_id    = $this->db->insert_id();


        // Update RUP Sebelumnya
        $this->updateDataRUPByID(
                                    $get_data["id_rup_sebelumnya"],
                                    array(
                                            "is_aktif"      => 0,
                                            "id_rup_awal"   => $get_data["id_rup_awal"],
                                            "is_last_paket" => 0
                                        )
                                );

        // Update Realisasi RUP
        $this->updateDataRealisasiRUPByID(
                                            $get_data["id_rup_sebelumnya"],
                                            array(
                                                    "id_rup"        => $rup_last_id
                                                )
                                        );


        // Create Histoy Revisi
        $check_revisi_ke = $this->getAllDataHistoryRevisiRUP($get_data["id_rup_awal"])->num_rows();
        $get_revisi = intval($check_revisi_ke) + 1;
        $cara_pengadaan = ["-", "PENYEDIA", "SWAKELOLA", "PENYEDIADALAMSWAKELOLA"];

        if ($get_data["alasan_revisi"] != "") {
            $alasan_revisi = $get_data["alasan_revisi"];
        }
        if ($get_data["alasan_revisi"] == "") {
            $alasan_revisi = "-";
        }
        $this->insertDataHistoryPaket(
                                        array(
                                            "tahun"               => $get_data["tahun"],
                                            "id_rup_awal"         => $get_data["id_rup_awal"],
                                            "id_rup_sebelumnya"   => $get_data["id_rup_sebelumnya"],
                                            "id_rup_baru"         => $rup_last_id,
                                            "revisi_ke"           => $get_revisi,
                                            "jenis"               => $cara_pengadaan[$get_data["cara_pengadaan"]],
                                            "tipe"                => "SATUKESATU",
                                            "alasan_revisi"       => $alasan_revisi,
                                        )
                                    );
    }

    public function updateDataRUPByID($id, $data){
        $this->db->where("id", $id);
        $this->db->update("tb_rup", $data);
    }


    public function updateDataRealisasiRUPByID($id_rup, $data){
        $this->db->where("id_rup", $id_rup);
        $this->db->update("tb_realisasi_rup", $data);
    }

    public function updateRealisasiRUPTepraByIDRUPAwal(){
        $result_rup_distinct = $this->getDataRUPDistinctIDRUPAwal();

        // Check Data RUP
        foreach ($result_rup_distinct->result() as $rows_rup_distinct) {
            $result_rup = $this->getDataRUPDescID($rows_rup_distinct->id_rup_awal);
            foreach ($result_rup->result() as $rows_rup) {
                $data_revisi_rup = array("id_rup" => $rows_rup->id);
                $this->updateIDRUPRealisasiRUP($rows_rup->id_rup_awal, $data_revisi_rup);
                $this->updateIDRUPRealisasiTEPRA($rows_rup->id_rup_awal, $data_revisi_rup);
            }
        }

        return TRUE;
    }

    public function updateHistoryRUPRevisiKe(){
        $result_rup_distinct = $this->getDataRUPDistinctIDRUPAwal();

        // Check Data RUP
        foreach ($result_rup_distinct->result() as $rows_rup_distinct) {
            $number = 1;
            $result_history_rup = $this->getDataHistoryRUPByIDRUPAwal($rows_rup_distinct->id_rup_awal);
            foreach ($result_history_rup->result() as $rows_history_rup) {
                $data_revisi_history = array("revisi_ke" => $number++);
                $this->updateRevisiKeHistoryRUP($rows_history_rup->id, $data_revisi_history);
            }
        }

        return TRUE;
    }


    public function getDataRUPDistinctIDRUPAwal(){
        $this->db->select("DISTINCT(id_rup_awal) as id_rup_awal");
        $this->db->from("tb_rup");
        $this->db->where("id_rup_awal >", 0);
        $this->db->order_by("id", "ASC");
        return $this->db->get();
    }

    public function getDataRUPDescID($id_rup_awal){
        $this->db->select("*");
        $this->db->from("tb_rup");
        $this->db->where("id_rup_awal", $id_rup_awal);
        $this->db->order_by("id", "DESC");
        $this->db->limit(1);
        return $this->db->get();
    }

    public function getDataHistoryRUPByIDRUPAwal($id_rup_awal){
        $this->db->select("*");
        $this->db->from("tb_rup_revisi");
        $this->db->where("id_rup_awal", $id_rup_awal);
        $this->db->order_by("id", "ASC");
        return $this->db->get();
    }


    public function updateIDRUPRealisasiRUP($id_rup_awal, $data){
        $this->db->where("id_rup_awal", $id_rup_awal);
        $this->db->update("tb_realisasi_rup", $data);
    }

    public function updateIDRUPRealisasiTEPRA($id_rup_awal, $data){
        $this->db->where("id_rup_awal", $id_rup_awal);
        $this->db->update("tb_realisasi_tepra", $data);
    }

    public function updateRevisiKeHistoryRUP($id, $data){
        $this->db->where("id", $id);
        $this->db->update("tb_rup_revisi", $data);
    }

    public function deleteRUPIDZero(){
        $this->db->where("id_program", 0);
        $this->db->delete("tb_rup");
        return TRUE;
    }

    public function deleteHistoryRevisiByIDRUPBaru(){
        $ref = '(SELECT id FROM tb_rup)';
        // $this->db->where("id_rup_sebelumnya NOT IN".$ref);
        // $this->db->where("id_rup_baru NOT IN".$ref);
        $this->db->where("alasan_revisi", "'-'");
        $this->db->delete("tb_rup_revisi");
        return TRUE;
    }

    // public function getData($token){
    //     $this->db->select("*");
    //     $this->db->from("tb_rup");
    //     $this->db->where("id", $token);
    //     $data = $this->db->get();
    //     return $data;
    // }


}