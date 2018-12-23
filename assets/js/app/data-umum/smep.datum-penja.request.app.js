jQuery(document).ready(function(){
	// ---------------- Register ---------------- //
	jQuery(document).on("click", ".smep-penjadatum-register-btn", function(){
		jQuery(".penjadatum-idskpd-reg").val(jQuery(".smep-skpd-main").val());
		jQuery(".smep-penjadatum-register-modal").modal("show");
		return false;
	});

	jQuery(document).on("click", ".smep-penjadatum-register-upload-btn", function(){
		if (jQuery(".penjadatum-nama-reg").val() != "") {
			jQuery.ajax({
				type 		: 'AJAX',
				method 		: 'POST',
				url 		: 'data-umum/penanggung-jawab/upload-data',
				async		: true,
				data 		: jQuery(".penjadatum-register-form").serialize(),
				dataType 	: 'JSON',
				success 	: function(JSON){
					datumPenjaData();
					jQuery(".penjadatum-register-form")[0].reset();
					jQuery(".smep-penjadatum-register-modal").modal("hide");
				},
				error 		: function(jqXHR, textStatus, errorThrown){
					console.log('failed');
				}
			});
		}
		else{
			jQuery(".penjadatum-nama-reg").focus();
		}

		return false;	
	});

	jQuery(document).on("click", ".smep-penjadatum-register-cancel-btn", function(){
		jQuery(".penjadatum-register-form")[0].reset();
		jQuery(".smep-penjadatum-register-modal").modal("hide");
		return false;
	});


	// ---------------- Update ---------------- //
	jQuery(document).on("click", ".smep-penjadatum-edit-btn", function(){
		jQuery.ajax({
	      type      : 'AJAX',
	      method    : 'GET',
	      url       : 'data-umum/penanggung-jawab/edit-data/'+jQuery(this).data("id"),
	      async     : true,
	      dataType  : 'JSON',
	      success   : function(JSON){
	      	jQuery(".penjadatum-token-edit").val(JSON[0][0]);
	      	jQuery(".penjadatum-nama-edit").val(JSON[0][1]);
	      	jQuery(".penjadatum-nip-edit").val(JSON[0][2]);
	      	jQuery(".penjadatum-jabatan-edit").val(JSON[0][3]);
	      	jQuery(".penjadatum-status-edit").val(JSON[0][4]).change();
	      	jQuery(".penjadatum-golongan-edit").val(JSON[0][5]);
	      	jQuery(".penjadatum-pangkat-edit").val(JSON[0][6]);
			jQuery(".smep-penjadatum-edit-modal").modal("show");
	      },
	      error     : function(jqXHR, textStatus, errorThrown){
	        console.log('failed');
	      }
	    });
		return false;
	});

	jQuery(document).on("click", ".smep-penjadatum-edit-update-btn", function(){
		if (jQuery(".penjadatum-nama-edit").val() != "") {
			jQuery.ajax({
				type 		: 'AJAX',
				method 		: 'POST',
				url 		: 'data-umum/penanggung-jawab/update-data',
				async		: true,
				data 		: jQuery(".penjadatum-edit-form").serialize(),
				dataType 	: 'JSON',
				success 	: function(JSON){
					datumPenjaData();
					jQuery(".penjadatum-edit-form")[0].reset();
					jQuery(".smep-penjadatum-edit-modal").modal("hide");
				},
				error 		: function(jqXHR, textStatus, errorThrown){
					console.log('failed');
				}
			});
		}
		else{
			jQuery(".penjadatum-nama-edit").focus();
		}
		return false;
	});

	jQuery(document).on("click", ".smep-penjadatum-edit-cancel-btn", function(){
		jQuery(".penjadatum-edit-form")[0].reset();
		jQuery(".smep-penjadatum-edit-modal").modal("hide");
		return false;
	});

	// ---------------- Delete ---------------- //
	jQuery(document).on("click", ".smep-penjadatum-delete-btn", function(){
		if (confirm('Apakah anda yakin ingin menghapusnya?')) {
			jQuery.ajax({
				type 		: 'AJAX',
				method 		: 'GET',
				url 		: 'data-umum/penanggung-jawab/delete-data/'+jQuery(this).data("id"),
				async		: true,
				dataType 	: 'JSON',
				success 	: function(JSON){
					datumPenjaData();
				},
				error 		: function(jqXHR, textStatus, errorThrown){
					console.log('failed');
				}
			});
		}
		return false;	
	});
});