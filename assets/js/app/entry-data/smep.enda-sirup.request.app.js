jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	jQuery(document).on("click", ".smep-sirupenda-ro-edit-btn", function(){
		jQuery(".smep-unique-token").val(jQuery(this).data("id"));
		jQuery(".smep-content-page").load("entry-data/realisasi-rup/realisasi-page");
		return false;
	});



	// ---------------- Modal ---------------- //
	function endaSiRUPRegisterModal(type_alert, notif=""){
		if (type_alert == 1) {
			notification = "Harap inputan <b>"+notif+"</b> tidak dikosongi";
			jQuery(".smep-sirupenda-register-body-modal").children("h4").append().html(notification);
			jQuery(".smep-sirupenda-register-save-area-modal").append().html("");
		}
		if (type_alert == 2) {	
			notification = "Mohon maaf <b>"+notif+"</b> anda salah";
			jQuery(".smep-sirupenda-register-body-modal").children("h4").append().html(notification);
			jQuery(".smep-sirupenda-register-save-area-modal").append().html("");
		}
		if (type_alert == 3) {	
			notification = "Mohon maaf tanggal <b>"+notif+"</b> tidak boleh sama dengan bulan ini/menggunakan tanggal sebelum bulan ini/menggunakan tanggal sebelum pelaksanaan awal";
			jQuery(".smep-sirupenda-register-body-modal").children("h4").append().html(notification);
			jQuery(".smep-sirupenda-register-save-area-modal").append().html("");
		}
		if (type_alert == 4) {	
			notification = "Mohon maaf <b>"+notif+"</b> melebihi pagu paket / sisa pagu yang tersedia";
			jQuery(".smep-sirupenda-register-body-modal").children("h4").append().html(notification);
			jQuery(".smep-sirupenda-register-save-area-modal").append().html("");
		}
		if (type_alert == 5) {	
			notification = "Apakah anda yakin ingin menyimpan data ini?";
			save_button = "<button class='btn btn-primary smep-sirupenda-register-save-modal-btn'><i class='fa fa-save'></i> Simpan</button>";
			jQuery(".smep-sirupenda-register-save-area-modal").append().html(save_button);
			jQuery(".smep-sirupenda-register-body-modal").children("h4").append().html(notification);
		}
		jQuery(".smep-sirupenda-register-modal").modal("show");
	}

	function endaSiRUPEditModal(type_alert, notif=""){
		if (type_alert == 1) {
			notification = "Harap inputan <b>"+notif+"</b> tidak dikosongi";
			jQuery(".smep-sirupenda-edit-body-modal").children("h4").append().html(notification);
			jQuery(".smep-sirupenda-edit-change-area-modal").append().html("");
		}
		if (type_alert == 2) {	
			notification = "Mohon maaf <b>"+notif+"</b> anda salah";
			jQuery(".smep-sirupenda-edit-body-modal").children("h4").append().html(notification);
			jQuery(".smep-sirupenda-edit-change-area-modal").append().html("");
		}
		if (type_alert == 3) {	
			notification = "Mohon maaf tanggal <b>"+notif+"</b> tidak boleh sama dengan bulan ini/menggunakan tanggal sebelum bulan ini/menggunakan tanggal sebelum pelaksanaan awal";
			jQuery(".smep-sirupenda-edit-body-modal").children("h4").append().html(notification);
			jQuery(".smep-sirupenda-edit-change-area-modal").append().html("");
		}
		if (type_alert == 4) {	
			notification = "Mohon maaf <b>"+notif+"</b> melebihi pagu paket / sisa pagu yang tersedia";
			jQuery(".smep-sirupenda-edit-body-modal").children("h4").append().html(notification);
			jQuery(".smep-sirupenda-edit-change-area-modal").append().html("");
		}
		if (type_alert == 5) {	
			notification = "Apakah anda yakin ingin menyimpan data ini?";
			save_button = "<button class='btn btn-primary smep-sirupenda-edit-update-modal-btn'><i class='fa fa-save'></i> Simpan</button>";
			jQuery(".smep-sirupenda-edit-change-area-modal").append().html(save_button);
			jQuery(".smep-sirupenda-edit-body-modal").children("h4").append().html(notification);
		}
		jQuery(".smep-sirupenda-edit-modal").modal("show");
	}

 
	// ---------------- Register ---------------- //
	jQuery(document).on("click", ".smep-sirupenda-ro-rup-register-btn", function(){
		jQuery(".smep-content-page").load("entry-data/realisasi-rup/register-page");
		return false;
	});

	jQuery(document).on("click", ".smep-sirupenda-register-send-btn", function(){
		if (jQuery(".sirupenda-tanggal-pencairan-reg").val() != "") {
			if (jQuery(".sirupenda-realisasi-keuangan-reg").val() != "") {
				if (jQuery(".sirupenda-realisasi-keuangan-reg").val() >= 0) {
					if (jQuery(".sirupenda-realisasi-fisik-reg").val() != "") {
						split_rf = jQuery(".sirupenda-realisasi-fisik-reg").val().split(".");
						parse_rf = parseInt(split_rf[0]+""+split_rf[1]);
						if (parse_rf >= 0 && parse_rf >= 100000) {
							if (parseInt(jQuery(".sirupenda-realisasi-keuangan-reg").val()) <= parseInt(jQuery(".sirupenda-sisa-pagu-paket-reg").val())) {
								if (jQuery(".sirupenda-nilai-kontrak-reg").val() != '') {
									if (jQuery(".sirupenda-nilai-kontrak-reg").val() >= 0) {
										endaSiRUPRegisterModal(5);
									}
									else{
										endaSiRUPEditModal(2, "Nilai Kontrak");
										jQuery(".sirupenda-nilai-kontrak-reg").focus();
									}
								}
								else{
									endaSiRUPEditModal(1, "Nilai Kontrak");
									jQuery(".sirupenda-nilai-kontrak-reg").focus();
								}
							}
							else{
								endaSiRUPRegisterModal(4, "Realisasi Keuangan");
								jQuery(".sirupenda-realisasi-keuangan-reg").focus();
							}
						}
						else{
							endaSiRUPRegisterModal(2, "Realisasi Fisik");
							jQuery(".sirupenda-realisasi-fisik-reg").focus();
						}
					}
					else{
						endaSiRUPRegisterModal(1, "Realisasi Fisik");
						jQuery(".sirupenda-realisasi-fisik-reg").focus();
					}
				}
				else{
					endaSiRUPRegisterModal(2, "Realisasi Keuangan");
					jQuery(".sirupenda-realisasi-keuangan-reg").focus();
				}
			}
			else{
				endaSiRUPRegisterModal(1, "Realisasi Keuangan");
				jQuery(".sirupenda-realisasi-keuangan-reg").focus();
			}
		}
		else{
			endaSiRUPRegisterModal(1, "Tanggal Pencairan");
			jQuery(".sirupenda-tanggal-pencairan-reg").focus();
		}
		return false;
	});

	jQuery(document).on("click", ".smep-sirupenda-register-save-modal-btn", function(){
		jQuery.ajax({
		    type      	: 'AJAX',
		    method    	: 'POST',
		    url       	: 'entry-data/realisasi-rup/upload-data',
		    async     	: true,
		    data 		: jQuery(".sirupenda-register-form").serialize(),
		    dataType  	: 'JSON',
		    success   	: function(JSON){
		    	jQuery(".smep-content-page").load("entry-data/realisasi-rup/realisasi-page");
		    },
		    error     	: function(jqXHR, textStatus, errorThrown){
		        location.href = window.location.href+"app/auth/sessionPage";
		    }
	    });
		return false;
	});

	jQuery(document).on("click", ".smep-sirupenda-register-close-modal-btn", function(){
		jQuery(".smep-sirupenda-register-modal").modal("hide");
		return false;
	});


	// ---------------- Edit ---------------- //
	jQuery(document).on("click", ".smep-sirupenda-edit-btn", function(){
		jQuery(".smep-unique-edit-token").val(jQuery(this).data("id"));
		jQuery(".smep-content-page").load("entry-data/realisasi-rup/edit-page");
		return false;
	});

	jQuery(document).on("click", ".smep-sirupenda-edit-change-btn", function(){
		if (jQuery(".sirupenda-tanggal-pencairan-edit").val() != "") {
			if (jQuery(".sirupenda-realisasi-keuangan-edit").val() != "") {
				if (jQuery(".sirupenda-realisasi-keuangan-edit").val() >= 0) {
					var count_max_realisasi = parseInt(jQuery(".sirupenda-real-realisasi-keuangan-edit").val()) + parseInt(jQuery(".sirupenda-sisa-pagu-paket-edit").val());;
					if (parseInt(jQuery(".sirupenda-realisasi-keuangan-edit").val()) <= count_max_realisasi) {
						if (jQuery(".sirupenda-nilai-kontrak-edit").val() != '') {
							if (jQuery(".sirupenda-nilai-kontrak-edit").val() >= 0) {
								endaSiRUPEditModal(5);
							}
							else{
								endaSiRUPEditModal(2, "Nilai Kontrak");
								jQuery(".sirupenda-nilai-kontrak-edit").focus();
							}
						}
						else{
							endaSiRUPEditModal(1, "Nilai Kontrak");
							jQuery(".sirupenda-nilai-kontrak-edit").focus();
						}
					}
					else{
						endaSiRUPEditModal(4, "Realisasi Keuangan");
						jQuery(".sirupenda-realisasi-keuangan-edit").focus();
					}
				}
				else{
					endaSiRUPEditModal(2, "Realisasi Keuangan");
					jQuery(".sirupenda-realisasi-keuangan-edit").focus();
				}
			}
			else{
				endaSiRUPEditModal(1, "Realisasi Keuangan");
				jQuery(".sirupenda-realisasi-keuangan-edit").focus();
			}
		}
		else{
			endaSiRUPEditModal(1, "Tanggal Pencairan");
			jQuery(".sirupenda-tanggal-pencairan-edit").focus();
		}
		return false;
	});

	jQuery(document).on("click", ".smep-sirupenda-edit-update-modal-btn", function(){
		jQuery.ajax({
		    type      	: 'AJAX',
		    method    	: 'POST',
		    url       	: 'entry-data/realisasi-rup/update-data',
		    async     	: true,
		    data 		: jQuery(".sirupenda-edit-form").serialize(),
		    dataType  	: 'JSON',
		    success   	: function(JSON){
		    	jQuery(".smep-content-page").load("entry-data/realisasi-rup/realisasi-page");
		    },
		    error     	: function(jqXHR, textStatus, errorThrown){
		        location.href = window.location.href+"app/auth/sessionPage";
		    }
	    });
		return false;
	});

	jQuery(document).on("click", ".smep-sirupenda-edit-close-modal-btn", function(){
		jQuery(".smep-sirupenda-edit-modal").modal("hide");
		return false;
	});


	// ---------------- Delete ---------------- //
	jQuery(document).on("click", ".smep-sirupenda-delete-btn", function(){
		jQuery.ajax({
		    type      	: 'AJAX',
		    method    	: 'GET',
		    url       	: 'entry-data/realisasi-rup/delete-data/'+jQuery(this).data("id"),
		    async     	: true,
		    dataType  	: 'JSON',
		    success   	: function(JSON){
		    	jQuery(".smep-content-page").load("entry-data/realisasi-rup/realisasi-page");
		    },
		    error     	: function(jqXHR, textStatus, errorThrown){
		        location.href = window.location.href+"app/auth/sessionPage";
		    }
	    });
		return false;
	});

	jQuery(document).on("click", ".smep-sirupenda-allcheck-delete", function(){
	    var status = this.checked;
	    jQuery(".sirupenda-delete-data").each(function(){
	      jQuery(this).prop("checked", status);
	    });
	});

	jQuery(document).on("click", ".smep-sirupenda-multidelete-btn", function(){
		jQuery.ajax({
		    type      	: 'AJAX',
		    method    	: 'POST',
		    url       	: 'entry-data/realisasi-rup/multi-delete-data',
		    async     	: true,
		    data 		: jQuery(".sirupenda-multidelete-form").serialize(),
		    dataType  	: 'JSON',
		    success   	: function(JSON){
		    	jQuery(".smep-content-page").load("entry-data/realisasi-rup/realisasi-page");
		    },
		    error     	: function(jqXHR, textStatus, errorThrown){
		        location.href = window.location.href+"app/auth/sessionPage";
		    }
	    });
		return false;
	});
});