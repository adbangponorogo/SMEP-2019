jQuery(document).ready(function(){
	
	// ---------------- Default ---------------- //

	// jQuery(document).on("click", ".", function(){

	// 	return false;
	// });


	// --------------- Application --------------- //
	jQuery(document).on("click", ".smep-app-dashboard-page", function(){
		var url = "app/dashboard/main-page";
		appMainCheckSession(url);
		return false;
	});


	// --------------- Data Umum --------------- //
	jQuery(document).on("click", ".smep-datum-penja-page", function(){
		var url = "data-umum/penanggung-jawab/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-datum-daftan-page", function(){
		var url = "data-umum/kegiatan/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-datum-dator-page", function(){
		var url = "data-umum/data-organisasi/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-datum-patan-page", function(){
		var url = "data-umum/pagu-kegiatan/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-datum-dapan-page", function(){
		var url = "data-umum/pencairan-sppd/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-datum-tangan-page", function(){
		var url = "data-umum/target-keuangan/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-datum-tasik-page", function(){
		var url = "data-umum/target-fisik/main-page";
		appMainCheckSession(url);
		return false;
	});
 

	// --------------- Entry Data --------------- //
	jQuery(document).on("click", ".smep-enda-rup-page", function(){
		var url = "entry-data/data-rup/main-page";
		appMainCheckSession(url);
		return false;
	});
	
	jQuery(document).on("click", ".smep-enda-sirup-page", function(){
		var url = "entry-data/realisasi-rup/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-enda-sitepra-page", function(){
		var url = "entry-data/realisasi-tepra/main-page";
		appMainCheckSession(url);
		return false;
	});


	// --------------- Laporan Danang --------------- //

	jQuery(document).on("click", ".danang-smep-lapor-rapan-page", function(){
		var url = "laporan/rp/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".danang-smep-lapor-rebela-page", function(){
		var url = "laporan/ap/main-page";
		appMainCheckSession(url);
		return false;
	});

	// --------------- Laporan --------------- //

	jQuery(document).on("click", ".smep-lapor-rapan-page", function(){
		var url = "laporan/rencana-pengadaan/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-tepra-perencanaan-page", function(){
		var url = "laporan/tepra/perencanaan/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-tepra-realisasi-page", function(){
		var url = "laporan/tepra/realisasi/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-lapan-page", function(){
		var url = "laporan/laporan-pengadaan/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-rebela-page", function(){
		var url = "laporan/realisasi-belanja-langsung/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-bast-page", function(){
		var url = "laporan/bast/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-rup-page", function(){
		var url = "laporan/rup/main-page";
		appMainCheckSession(url);
		return false;
	});




	// --------------- Administrator --------------- //
	jQuery(document).on("click", ".smep-admin-pengsi-page", function(){
		var url = "administrator/pengguna-aplikasi/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-admin-rup-page", function(){
		var url = "administrator/rup/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-admin-rapan-page", function(){
		var url = "administrator/rencana-pengadaan/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-admin-lapan-page", function(){
		var url = "administrator/laporan-pengadaan/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-admin-rebela-page", function(){
		var url = "administrator/realisasi-belanja-langsung/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-admin-misc-page", function(){
		var url = "administrator/misc/main-page";
		appMainCheckSession(url);
		return false;
	});

	jQuery(document).on("click", ".smep-admin-rup-aktual-page", function(){
		var url = "administrator/rup-aktual/main-page";
		appMainCheckSession(url);
		return false;
	});


	jQuery(document).on("click", ".empty-value", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		return false;
	});

	function appMainCheckSession(url){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'GET',
			url 		: 'app/auth/checkSession',
			async		: true,
			dataType 	: 'JSON',
			success 	: function(JSON){
				if (JSON == 0) {
					jQuery(".smep-content-page").load(url);
				}
				if (JSON == 1) {
					location.href = 'app/auth/sessionPage';
				}
			},
			error		: function(jqXHR, textStatus, errorThrown){
				location.href = window.location.href+"app/auth/sessionPage";
			}
		});
	}
});