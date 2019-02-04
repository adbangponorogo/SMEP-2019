jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	jQuery(document).on("click", ".smep-rupenda-register-btn", function(){
		jQuery(".smep-content-page").load("entry-data/data-rup/register-page");
		return false;
	});

	// ---------------- Modal ---------------- //
	function endaRUPRegisterModal(type_alert, notif=""){
		if (type_alert == 1) {
			var notification = "Harap inputan <b>"+notif+"</b> tidak dikosongi";
			var save_button = "";
			jQuery(".smep-rupenda-register-save-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-register-body-modal").children("h4").append().html(notification);
		}
		if (type_alert == 2) {	
			var notification = "Mohon maaf Bro<b>"+notif+"</b> anda salah";
			var save_button = "";
			jQuery(".smep-rupenda-register-save-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-register-body-modal").children("h4").append().html(notification);
		}
		if (type_alert == 3) {	
			var notification = "Mohon maaf tanggal <b>"+notif+"</b> tidak boleh sama dengan bulan ini/menggunakan tanggal sebelum bulan ini/menggunakan tanggal sebelum pelaksanaan awal";
			var save_button = "";
			jQuery(".smep-rupenda-register-save-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-register-body-modal").children("h4").append().html(notification);
		}
		if (type_alert == 4) {	
			var notification = "Mohon maaf <b>"+notif+"</b> tidak boleh melebihi <b>Sisa Pagu Rincian Obyek</b>";
			var save_button = "";
			jQuery(".smep-rupenda-register-save-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-register-body-modal").children("h4").append().html(notification);
		}
		if (type_alert == 5) {	
			notification = "Apakah anda yakin ingin menyimpan data ini?";
			save_button = "<button class='btn btn-primary smep-rupenda-register-save-modal-btn'><i class='fa fa-save'></i> Simpan</button>";
			jQuery(".smep-rupenda-register-save-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-register-body-modal").children("h4").append().html(notification);
		}
		jQuery(".smep-rupenda-register-modal").modal("show");
	}

	function endaRUPEditModal(type_alert, notif=""){
		if (type_alert == 1) {
			var notification = "Harap inputan <b>"+notif+"</b> tidak dikosongi";
			var save_button = "";
			jQuery(".smep-rupenda-edit-change-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-edit-body-modal").children("h4").append().html(notification);
		}
		if (type_alert == 2) {	
			var notification = "Mohon maaf gaes<b>"+notif+"</b> anda salah";
			var save_button = "";
			jQuery(".smep-rupenda-edit-change-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-edit-body-modal").children("h4").append().html(notification);
		}
		if (type_alert == 3) {	
			var notification = "Mohon maaf tanggal <b>"+notif+"</b> tidak boleh sama dengan bulan ini/menggunakan tanggal sebelum bulan ini/menggunakan tanggal sebelum pelaksanaan awal/menggunakan tanggal sebelum value pelaksanaan awal yang sebelumnya";
			var save_button = "";
			jQuery(".smep-rupenda-edit-change-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-edit-body-modal").children("h4").append().html(notification);
		}
		if (type_alert == 4) {	
			var notification = "Mohon maaf <b>"+notif+"</b> tidak boleh melebihi <b>Sisa Pagu Rincian Obyek</b>";
			var save_button = "";
			jQuery(".smep-rupenda-edit-change-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-edit-body-modal").children("h4").append().html(notification);
		}
		if (type_alert == 5) {	
			notification = "Apakah anda yakin ingin mengubah data ini?";
			save_button = "<button class='btn btn-primary smep-rupenda-edit-update-modal-btn'><i class='fa fa-save'></i> Ubah</button>";
			jQuery(".smep-rupenda-edit-change-area-modal").append().html(save_button);
			jQuery(".smep-rupenda-edit-body-modal").children("h4").append().html(notification);
		}
		jQuery(".smep-rupenda-edit-modal").modal("show");
	}

	// ---------------- Register ---------------- //
	jQuery(document).on("click", ".smep-rupenda-register-send-btn", function(){
		if (jQuery(".rupenda-nama-paket-reg").val() != "") {
			if (jQuery(".rupenda-volume-pekerjaan-reg").val() != "") {
				if (jQuery(".rupenda-pagu-pekerjaan-reg").val() != "") {
					if (jQuery(".rupenda-pagu-pekerjaan-reg").val() > 0) {
						if (parseInt(jQuery(".rupenda-pagu-pekerjaan-reg").val()) <= parseInt(jQuery(".rupenda-max-pagu-reg").val())) {
							if (jQuery(".rupenda-jumlah-paket-reg").val() != "") {
								if (jQuery(".rupenda-jumlah-paket-reg").val() > 0) {
									if (jQuery(".rupenda-pra-dipa-reg").val() == 0) {
										endaRUPJadwalRegister();
									}
									else{
										endaRUPJadwalRegister();
									}
									
								}
								else{
									endaRUPRegisterModal(2, "Jumlah Paket");
									jQuery(".rupenda-jumlah-paket-reg").focus();
								}	
							}
							else{
								endaRUPRegisterModal(1, "Jumlah Paket");
								jQuery(".rupenda-jumlah-paket-reg").focus();
							}
						}
						else{
							endaRUPRegisterModal(4, "Pagu Pekerjaan");
							jQuery(".rupenda-pagu-pekerjaan-reg").focus();
						}
					}
					else{
						endaRUPRegisterModal(2, "Pagu Pekerjaan");
						jQuery(".rupenda-pagu-pekerjaan-reg").focus();
					}	
				}
				else{
					endaRUPRegisterModal(1, "Pagu Pekerjaan");
					jQuery(".rupenda-pagu-pekerjaan-reg").focus();
				}	
			}
			else{
				endaRUPRegisterModal(1, "Volume Pekerjaan");
				jQuery(".rupenda-volume-pekerjaan-reg").focus();
			}
		}
		else{
			endaRUPRegisterModal(1, "Nama Paket");
			jQuery(".rupenda-nama-paket-reg").focus();
		}
		return false;
	});

	function endaRUPJadwalRegister(){
		if (jQuery(".rupenda-cara-pengadaan-reg").val() == 1) {
			var pengadaan_awal = jQuery(".rupenda-pelaksanaan-pengadaan-awal-reg");
			var pengadaan_akhir = jQuery(".rupenda-pelaksanaan-pengadaan-akhir-reg");
			var kontrak_awal = jQuery(".rupenda-pelaksanaan-kontrak-awal-reg");
			var kontrak_akhir = jQuery(".rupenda-pelaksanaan-kontrak-akhir-reg");
			var mulai_penggunaan = jQuery(".rupenda-mulai-penggunaan-reg");
				if (pengadaan_awal.val() != "") {
					if (pengadaan_akhir.val() != "") {
						if (kontrak_awal.val() != "") {
							if (kontrak_akhir.val() != "") {
								if (mulai_penggunaan.val() != "") {

									// Date Now 
									var date = new Date();
									var month = date.getMonth();
									var nextMonth = parseInt(month)+1;
										if (nextMonth.toString().length == 1) {
											nextMonth = "0"+nextMonth;
										}
									var dateNow = nextMonth+"-"+date.getFullYear();

									// Declaration of Date
									var split_date_now = dateNow.split("-");
									var split_pengadaan_awal = pengadaan_awal.val().split("-");
									var split_pengadaan_akhir = pengadaan_akhir.val().split("-");
									var split_kontrak_awal = kontrak_awal.val().split("-");
									var split_kontrak_akhir = kontrak_akhir.val().split("-");
									var split_mulai_penggunaan = mulai_penggunaan.val().split("-");

									var get_date_now = parseInt(split_date_now[1]+""+split_date_now[0]);
									var get_pengadaan_awal = parseInt(split_pengadaan_awal[1]+""+split_pengadaan_awal[0]);
									var get_pengadaan_akhir = parseInt(split_pengadaan_akhir[1]+""+split_pengadaan_akhir[0]);
									var get_kontrak_awal = parseInt(split_kontrak_awal[1]+""+split_kontrak_awal[0]);
									var get_kontrak_akhir = parseInt(split_kontrak_akhir[1]+""+split_kontrak_akhir[0]);
									var get_mulai_penggunaan = parseInt(split_mulai_penggunaan[1]+""+split_mulai_penggunaan[0]);

									// Logic Security Date
									if (get_pengadaan_awal >= get_date_now) {
										if (get_pengadaan_akhir >= get_pengadaan_awal) {
											if (get_kontrak_awal >= get_pengadaan_akhir) {
												if (get_kontrak_akhir >= get_pengadaan_awal) {
													if (get_mulai_penggunaan >= get_kontrak_akhir) {
														if (jQuery(".rupenda-pra-dipa-reg").val() == 1) {
															jQuery(".rupenda-nomor-renja-reg").val("");
														}
														endaRUPRegisterModal(5);
													}
													else{
														endaRUPRegisterModal(3, "Pelaksanaan Mulai Penggunaan");
														mulai_penggunaan.focus();
													}
												}
												else{
													endaRUPRegisterModal(3, "Pelaksanaan Kontrak Akhir");
													kontrak_akhir.focus();
												}
											}
											else{
												endaRUPRegisterModal(3, "Pelaksanaan Kontrak Awal");
												kontrak_awal.focus();
											}
										}
										else{
											endaRUPRegisterModal(3, "Pelaksanaan Pengadaan Akhir");
											pengadaan_akhir.focus();
										}	
									}
									else{
										endaRUPRegisterModal(3, "Pelaksanaan Pengadaan Awal");
										pengadaan_awal.focus();
									}
									
									// ----- End -----

								}
								else{
									endaRUPRegisterModal(1, "Jadwal Pelaksanaan Mulai Penggunaan");
									mulai_penggunaan.focus();
								}
							}
							else{
								endaRUPRegisterModal(1, "Jadwal Pelaksanaan Kontrak Akhir");
								kontrak_akhir.focus();
							}
						}
						else{
							endaRUPRegisterModal(1, "Jadwal Pelaksanaan Kontrak Awal");
							kontrak_awal.focus();
						}
					}
					else{
						endaRUPRegisterModal(1, "Jadwal Pelaksanaan Pengadaan Akhir");
						pengadaan_akhir.focus();
					}
				}
				else{
					endaRUPRegisterModal(1, "Jadwal Pelaksanaan Pengadaan Awal");
					pengadaan_awal.focus();
				}
			}
			if (jQuery(".rupenda-cara-pengadaan-reg").val() == 2) {
				var pekerjaan_awal = jQuery(".rupenda-pelaksanaan-pekerjaan-awal-reg");
				var pekerjaan_akhir = jQuery(".rupenda-pelaksanaan-pekerjaan-akhir-reg");
				if (pekerjaan_awal.val() != '') {
					if (pekerjaan_akhir.val() != '') {

						// Date Now 
						var date = new Date();
						var month = date.getMonth();
						var nextMonth = parseInt(month)+1;
							if (nextMonth.toString().length == 1) {
								nextMonth = "0"+nextMonth;
							}
						var dateNow = nextMonth+"-"+date.getFullYear();

						// Declaration of Date
						var split_date_now = dateNow.split("-");
						var split_pekerjaan_awal = pekerjaan_awal.val().split("-");
						var split_pekerjaan_akhir = pekerjaan_akhir.val().split("-");


						var get_date_now = parseInt(split_date_now[1]+""+split_date_now[0]);
						var get_pekerjaan_awal = parseInt(split_pekerjaan_awal[1]+""+split_pekerjaan_awal[0]);
						var get_pekerjaan_akhir = parseInt(split_pekerjaan_akhir[1]+""+split_pekerjaan_akhir[0]);
						// Logic Security Date
						if (get_pekerjaan_awal >= get_date_now) {
							if (get_pekerjaan_akhir >= get_pekerjaan_awal) {
								if (jQuery(".rupenda-pra-dipa-reg").val() == 1) {
									jQuery(".rupenda-nomor-renja-reg").val("");
								}
								endaRUPRegisterModal(5);
							}
							else{
								endaRUPRegisterModal(3, "Pelaksanaan Pelaksanaan Pekerjaan Akhir");
								pekerjaan_akhir.focus();
							}	
						}
						else{
							endaRUPRegisterModal(3, "Pelaksanaan Pelaksanaan Pekerjaan Awal");
							pekerjaan_awal.focus();
						}
						// ----- End -----

					}
					else{
						endaRUPRegisterModal(1, "Jadwal Pelaksanaan Pekerjaan Akhir");
						pekerjaan_akhir.focus();
					}
				}
				else{
					endaRUPRegisterModal(1, "Jadwal Pelaksanaan Pekerjaan Awal");
					pekerjaan_awal.focus();
				}
			}
	}

	jQuery(document).on("click", ".smep-rupenda-register-save-modal-btn", function(){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'POST',
			url 		: 'entry-data/data-rup/upload-data',
			async		: true,
			data 		: jQuery(".rupenda-register-form").serialize(),
			dataType 	: 'JSON',
			success 	: function(JSON){
				jQuery(".rupenda-register-form")[0].reset();
				jQuery(".smep-rupenda-register-modal").modal("hide");
				jQuery(".smep-content-page").load("entry-data/data-rup/main-page");
			},
			error 		: function(jqXHR, textStatus, errorThrown){
				location.href = window.location.href+"app/auth/sessionPage";
			}
		});
		return false;
	});

	jQuery(document).on("click", ".smep-rupenda-register-close-modal-btn", function(){
		jQuery(".smep-rupenda-register-modal").modal("hide");
		return false;
	});

	jQuery(document).on("click", ".smep-rupenda-register-cancel-btn", function(){
		jQuery(".smep-content-page").load("entry-data/data-rup/main-page");
		return false;
	});


	// ---------------- Update ---------------- //
	jQuery(document).on("click", ".smep-rupenda-edit-btn", function(){
		jQuery(".smep-unique-token").val(jQuery(this).data("id"));
		jQuery(".smep-content-page").load("entry-data/data-rup/edit-page");
		return false;
	});

	jQuery(document).on("click", ".smep-rupenda-edit-change-btn", function(){
		if (jQuery(".rupenda-nama-paket-edit").val() != "") {
			if (jQuery(".rupenda-volume-pekerjaan-edit").val() != "") {
				if (jQuery(".rupenda-pagu-pekerjaan-edit").val() != "") {
					if (jQuery(".rupenda-pagu-pekerjaan-edit").val() > 0) {
						var current_pagu = parseInt(jQuery(".rupenda-pagu-pekerjaan-edit").val());
						var max_pagu = parseInt(jQuery(".rupenda-max-pagu-edit").val()) + parseInt(jQuery(".rupenda-real-pagu-pekerjaan-edit").val());
						if (current_pagu <= max_pagu) {
							if (jQuery(".rupenda-jumlah-paket-edit").val() != "") {
								if (jQuery(".rupenda-jumlah-paket-edit").val() > 0) {
									if (jQuery(".rupenda-pra-dipa-edit").val() == 0) {
										endaRUPJadwalEdit();
									}
									else{
										endaRUPJadwalEdit();
									}
								}
								else{
									endaRUPEditModal(2, "Jumlah Paket");
									jQuery(".rupenda-jumlah-paket-edit").focus();
								}
							}
							else{
								endaRUPEditModal(1, "Jumlah Paket");
								jQuery(".rupenda-jumlah-paket-edit").focus();
							}
						}
						else{
							endaRUPEditModal(4, "Pagu Pekerjaan");
							jQuery(".rupenda-pagu-pekerjaan-edit").focus();
						}
					}
					else{
						endaRUPEditModal(2, "Pagu Pekerjaan");
						jQuery(".rupenda-pagu-pekerjaan-edit").focus();
					}
				}
				else{
					endaRUPEditModal(1, "Pagu Pekerjaan");
					jQuery(".rupenda-pagu-pekerjaan-edit").focus();
				}
			}
			else{
				endaRUPEditModal(1, "Volume Pekerjaan");
				jQuery(".rupenda-volume-paket-edit").focus();
			}
		}
		else{
			endaRUPEditModal(1, "Nama Paket");
			jQuery(".rupenda-nama-paket-edit").focus();
		}
		return false;
	});

	function endaRUPJadwalEdit(){
		if (jQuery(".rupenda-real-cara-pengadaan-edit").val() == 1) {
			var real_pengadaan_awal = jQuery(".rupenda-real-pelaksanaan-pengadaan-awal-edit");
			var pengadaan_awal = jQuery(".rupenda-pelaksanaan-pengadaan-awal-edit");
			var pengadaan_akhir = jQuery(".rupenda-pelaksanaan-pengadaan-akhir-edit");
			var kontrak_awal = jQuery(".rupenda-pelaksanaan-kontrak-awal-edit");
			var kontrak_akhir = jQuery(".rupenda-pelaksanaan-kontrak-akhir-edit");
			var mulai_penggunaan = jQuery(".rupenda-mulai-penggunaan-edit");
				if (pengadaan_awal.val() != "") {
					if (pengadaan_akhir.val() != "") {
						if (kontrak_awal.val() != "") {
							if (kontrak_akhir.val() != "") {
								if (mulai_penggunaan.val() != "") {

									// Date Now 
									var date = new Date();
									var month = date.getMonth();
									var nextMonth = parseInt(month)+1;
										if (nextMonth.toString().length == 1) {
											nextMonth = "0"+nextMonth;
										}
									var dateNow = nextMonth+"-"+date.getFullYear();

									// Declaration of Date
									var split_date_now = dateNow.split("-");
									var split_real_pengadaan_awal = real_pengadaan_awal.val().split("-");
									var split_pengadaan_awal = pengadaan_awal.val().split("-");
									var split_pengadaan_akhir = pengadaan_akhir.val().split("-");
									var split_kontrak_awal = kontrak_awal.val().split("-");
									var split_kontrak_akhir = kontrak_akhir.val().split("-");
									var split_mulai_penggunaan = mulai_penggunaan.val().split("-");

									var get_date_now = parseInt(split_date_now[1]+""+split_date_now[0]);
									var get_pengadaan_awal = parseInt(split_pengadaan_awal[1]+""+split_pengadaan_awal[0]);
									var get_real_pengadaan_awal = parseInt(split_real_pengadaan_awal[1]+""+split_real_pengadaan_awal[0]);
									var get_pengadaan_akhir = parseInt(split_pengadaan_akhir[1]+""+split_pengadaan_akhir[0]);
									var get_kontrak_awal = parseInt(split_kontrak_awal[1]+""+split_kontrak_awal[0]);
									var get_kontrak_akhir = parseInt(split_kontrak_akhir[1]+""+split_kontrak_akhir[0]);
									var get_mulai_penggunaan = parseInt(split_mulai_penggunaan[1]+""+split_mulai_penggunaan[0]);

									// Logic Security Date
									if (get_pengadaan_awal >= get_date_now ||  get_pengadaan_awal >= get_real_pengadaan_awal) {
										if (get_pengadaan_akhir >= get_pengadaan_awal) {
											if (get_kontrak_awal >= get_pengadaan_akhir) {
												if (get_kontrak_akhir >= get_kontrak_awal) {
													if (get_mulai_penggunaan >= get_kontrak_akhir) {
														if (jQuery(".rupenda-pra-dipa-edit").val() == 1) {
															jQuery(".rupenda-nomor-renja-edit").val("");
														}
														endaRUPEditModal(5);
													}
													else{
														endaRUPEditModal(3, "Pelaksanaan Mulai Penggunaan");
														mulai_penggunaan.focus();
													}
												}
												else{
													endaRUPEditModal(3, "Pelaksanaan Kontrak Akhir");
													kontrak_akhir.focus();
												}
											}
											else{
												endaRUPEditModal(3, "Pelaksanaan Kontrak Awal");
												kontrak_awal.focus();
											}
										}
										else{
											endaRUPEditModal(3, "Pelaksanaan Pengadaan Akhir");
											pengadaan_akhir.focus();
										}	
									}
									else{
										endaRUPEditModal(3, "Pelaksanaan Pengadaan Awal");
										pengadaan_awal.focus();
									}
									
									// ----- End -----

								}
								else{
									endaRUPEditModal(1, "Jadwal Pelaksanaan Mulai Penggunaan");
									mulai_penggunaan.focus();
								}
							}
							else{
								endaRUPEditModal(1, "Jadwal Pelaksanaan Kontrak Akhir");
								kontrak_akhir.focus();
							}
						}
						else{
							endaRUPEditModal(1, "Jadwal Pelaksanaan Kontrak Awal");
							kontrak_awal.focus();
						}
					}
					else{
						endaRUPEditModal(1, "Jadwal Pelaksanaan Pengadaan Akhir");
						pengadaan_akhir.focus();
					}
				}
				else{
					endaRUPEditModal(1, "Jadwal Pelaksanaan Pengadaan Awal");
					pengadaan_awal.focus();
				}
			}
			if (jQuery(".rupenda-real-cara-pengadaan-edit").val() == 2) {
				var real_pekerjaan_awal = jQuery(".rupenda-real-pelaksanaan-pekerjaan-awal-edit");
				var pekerjaan_awal = jQuery(".rupenda-pelaksanaan-pekerjaan-awal-edit");
				var pekerjaan_akhir = jQuery(".rupenda-pelaksanaan-pekerjaan-akhir-edit");
				if (pekerjaan_awal.val() != '') {
					if (pekerjaan_akhir.val() != '') {

						// Date Now 
						var date = new Date();
						var month = date.getMonth();
						var nextMonth = parseInt(month)+1;
						if (nextMonth.toString().length == 1) {
							nextMonth = "0"+nextMonth;
						}
						var dateNow = nextMonth+"-"+date.getFullYear();

						// Declaration of Date
						var split_date_now = dateNow.split("-");
						var split_real_pekerjaan_awal = real_pekerjaan_awal.val().split("-");
						var split_pekerjaan_awal = pekerjaan_awal.val().split("-");
						var split_pekerjaan_akhir = pekerjaan_akhir.val().split("-");


						var get_date_now = parseInt(split_date_now[1]+""+split_date_now[0]);
						var get_real_pekerjaan_awal = parseInt(split_real_pekerjaan_awal[1]+""+split_real_pekerjaan_awal[0]);
						var get_pekerjaan_awal = parseInt(split_pekerjaan_awal[1]+""+split_pekerjaan_akhir[0]);
						var get_pekerjaan_akhir = parseInt(split_pekerjaan_akhir[1]+""+split_pekerjaan_akhir[0]);

						// Logic Security Date
						if (get_pekerjaan_awal >= get_date_now  || get_pekerjaan_awal >= get_real_pekerjaan_awal) {
							if (get_pekerjaan_akhir >= get_pekerjaan_awal || get_pekerjaan_akhir >= get_pekerjaan_awal) {
								if (jQuery(".rupenda-pra-dipa-edit").val() == 1) {
									jQuery(".rupenda-nomor-renja-edit").val("");
								}
								endaRUPEditModal(5);
							}
							else{
								endaRUPEditModal(3, "Pelaksanaan Pelaksanaan Pekerjaan Akhir");
								pekerjaan_akhir.focus();
							}	
						}
						else{
							endaRUPEditModal(3, "Pelaksanaan Pelaksanaan Pekerjaan Awal");
							pekerjaan_awal.focus();
						}
						// ----- End -----

					}
					else{
						endaRUPEditModal(1, "Jadwal Pelaksanaan Pekerjaan Akhir");
						pekerjaan_akhir.focus();
					}
				}
				else{
					endaRUPEditModal(1, "Jadwal Pelaksanaan Pekerjaan Awal");
					pekerjaan_awal.focus();
				}
			}
	}



	jQuery(document).on("click", ".smep-rupenda-edit-update-modal-btn", function(){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'POST',
			url 		: 'entry-data/data-rup/update-data',
			async		: true,
			data 		: jQuery(".rupenda-edit-form").serialize(),
			dataType 	: 'JSON',
			success 	: function(JSON){
				jQuery(".rupenda-edit-form")[0].reset();
				jQuery(".smep-rupenda-edit-modal").modal("hide");
				jQuery(".smep-content-page").load("entry-data/data-rup/main-page");
			},
			error 		: function(jqXHR, textStatus, errorThrown){
				location.href = window.location.href+"app/auth/sessionPage";
			}
		});
		return false;
	});

	jQuery(document).on("click", ".smep-rupenda-edit-close-modal-btn", function(){
		jQuery(".smep-rupenda-edit-modal").modal("hide");
		return false;
	});

	jQuery(document).on("click", ".smep-rupenda-edit-cancel-btn", function(){
		jQuery(".smep-content-page").load("entry-data/data-rup/main-page");
		return false;
	});

});