jQuery(document).ready(function(){
	
	// ---------------- Default ---------------- //

	// jQuery(document).on("click", ".", function(){

	// 	return false;
	// });


	// --------------- Application --------------- //
	jQuery(document).on("click", ".smep-app-dashboard-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("app/dashboard/main-page");
		return false;
	});


	// --------------- Data Umum --------------- //
	jQuery(document).on("click", ".smep-datum-penja-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("data-umum/penanggung-jawab/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-datum-daftan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("data-umum/kegiatan/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-datum-dator-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("data-umum/data-organisasi/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-datum-patan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("data-umum/pagu-kegiatan/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-datum-dapan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("data-umum/pencairan-sppd/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-datum-tangan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("data-umum/target-keuangan/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-datum-tasik-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("data-umum/target-fisik/main-page");
		return false;
	});
 

	// --------------- Entry Data --------------- //
	jQuery(document).on("click", ".smep-enda-rup-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("entry-data/data-rup/main-page");
		return false;
	});
	
	jQuery(document).on("click", ".smep-enda-sirup-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("entry-data/realisasi-rup/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-enda-sitepra-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("entry-data/realisasi-tepra/main-page");
		return false;
	});


	// --------------- Laporan --------------- //
	jQuery(document).on("click", ".smep-lapor-rapan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("laporan/rencana-pengadaan/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-tepra-perencanaan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("laporan/tepra/perencanaan/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-tepra-realisasi-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("laporan/tepra/realisasi/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-lapan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("laporan/laporan-pengadaan/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-rebela-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("laporan/realisasi-belanja-langsung/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-bast-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("laporan/bast/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-lapor-rup-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("laporan/rup/main-page");
		return false;
	});




	// --------------- Administrator --------------- //
	jQuery(document).on("click", ".smep-admin-pengsi-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("administrator/pengguna-aplikasi/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-admin-rup-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("administrator/rup/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-admin-rapan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("administrator/rencana-pengadaan/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-admin-lapan-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("administrator/laporan-pengadaan/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-admin-rebela-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("administrator/realisasi-belanja-langsung/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-admin-misc-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("administrator/misc/main-page");
		return false;
	});

	jQuery(document).on("click", ".smep-admin-rup-aktual-page", function(){
		jQuery(".smep-unique-token").val("");
		jQuery(".smep-unique-edit-token").val("");
		jQuery(".smep-idprogram-token").val("");
		jQuery(".smep-idkegiatan-token").val("");
		jQuery(".smep-idro-token").val("");
		jQuery(".smep-content-page").load("administrator/rup-aktual/main-page");
		return false;
	});
});