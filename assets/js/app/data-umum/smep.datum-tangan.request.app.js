jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	jQuery(document).on("click", ".smep-tangandatum-save-btn", function(){
		var pagu = parseInt(jQuery(".tangandatum-real-pagu-update").val());
		var januari = parseInt(jQuery(".tangandatum-januari-update").val());
		var februari = parseInt(jQuery(".tangandatum-februari-update").val());
		var maret = parseInt(jQuery(".tangandatum-maret-update").val());
		var april = parseInt(jQuery(".tangandatum-mei-update").val());
		var mei = parseInt(jQuery(".tangandatum-april-update").val());
		var juni = parseInt(jQuery(".tangandatum-juni-update").val());
		var juli = parseInt(jQuery(".tangandatum-juli-update").val());
		var agustus = parseInt(jQuery(".tangandatum-agustus-update").val());
		var september = parseInt(jQuery(".tangandatum-september-update").val());
		var oktober = parseInt(jQuery(".tangandatum-oktober-update").val());
		var november = parseInt(jQuery(".tangandatum-november-update").val());
		var desember = parseInt(jQuery(".tangandatum-desember-update").val());
		var total_rencana = januari+februari+maret+april+mei+juni+juli+agustus+september+oktober+november+desember;
			if (januari >= 0 && januari <= pagu) {
				if (februari >= 0 && februari <= pagu) {
					if (maret >= 0 && maret <= pagu) {
						if (april >= 0 && april <= pagu) {
							if (mei >= 0 && mei <= pagu) {
								if (juni >= 0 && juni <= pagu) {
									if (juli >= 0 && juli <= pagu) {
										if (agustus >= 0 && agustus <= pagu) {
											if (september >= 0 && september <= pagu) {
												if (oktober >= 0 && oktober <= pagu) {
													if (november >= 0 && november <= pagu) {
														if (januari >= 0 && desember <= pagu) {
															jQuery.ajax({
														      type      : 'AJAX',
														      method    : 'POST',
														      url       : 'data-umum/target-keuangan/save-data',
														      async     : true,
														      data  	: jQuery(".tangandatum-update-form").serialize(),
														      dataType  : 'JSON',
														      success   : function(JSON){
														      	datumTanganMainData();
														      	datumTanganMainData();
														      },
														      error     : function(jqXHR, textStatus, errorThrown){
														        location.href = window.location.href+"app/auth/sessionPage";
														      }
														    });
														}
														else{
															jQuery(".tangandatum-desember-update").focus();
														}
													}
													else{
														jQuery(".tangandatum-november-update").focus();
													}
												}
												else{
													jQuery(".tangandatum-oktober-update").focus();
												}
											}
											else{
												jQuery(".tangandatum-september-update").focus();
											}
										}
										else{
											jQuery(".tangandatum-agustus-update").focus();
										}
									}
									else{
										jQuery(".tangandatum-juli-update").focus();
									}
								}
								else{
									jQuery(".tangandatum-juni-update").focus();
								}
							}
							else{
								jQuery(".tangandatum-mei-update").focus();
							}
						}
						else{
							jQuery(".tangandatum-april-update").focus();
						}
					}
					else{
						jQuery(".tangandatum-maret-update").focus();
					}
				}
				else{
					jQuery(".tangandatum-februari-update").focus();
				}
			}
			else{
				jQuery(".tangandatum-januari-update").focus();
			}
		return false;
	});
});