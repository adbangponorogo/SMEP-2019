jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	jQuery(document).on("click", ".smep-pengsiadmin-user-data-page", function(){
		jQuery(".smep-content-page").load("administrator/pengguna-aplikasi/user-page");
		jQuery(".smep-unique-token").val(jQuery(this).data("id"));
		return false;
	});
	
	// --------------- Register --------------- //
	jQuery(document).on("click", ".smep-pengsiadmin-user-register-btn", function(){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'GET',
			url 		: 'administrator/pengguna-aplikasi/data-skpd/'+jQuery(".smep-unique-token").val(),
			async		: true,
			dataType 	: 'JSON',
			success 	: function(JSON){
				var option = '';
		        for (var skpd = 0; skpd < JSON.length; skpd++) {
		          option += "<option value='"+JSON[skpd][0]+"'>["+JSON[skpd][1]+"] - "+JSON[skpd][2]+"</option>";
		        }
		        jQuery(".pengsiadmin-skpd-user-reg").html(option);
		        jQuery(".pengsiadmin-skpd-user-reg").select2();
				jQuery(".pengsiadmin-register-user-form .modal-body .alert").remove();
				// var status_data =   [
	   //                                  [1, "Root - Administrator"],
	   //                                  [2, "Operator - PA/KPA SKPD"]
	   //                              ]
		  //       var option_user = '';
		  //       for (var status = 0; status < status_data.length; status++) {
		  //           option_user += "<option value='"+status_data[status][0]+"'>"+status_data[status][1]+"</option>";
		  //       }
		  //       jQuery(".pengsiadmin-status-user-reg").html(option_user);
				jQuery(".smep-pengsiadmin-user-register-modal").modal("show");
			},
			error 		: function(jqXHR, textStatus, errorThrown){
				console.log('failed');
			}
		});
		return false;
	});

	jQuery(document).on("change", ".pengsiadmin-status-user-reg", function(){
		if (jQuery(this).val() == 1) {
			jQuery(".pengsiadmin-password-user-reg").val("");
			jQuery(".pengsiadmin-password-user-reg").removeAttr("readonly");
			jQuery(".pengsiadmin-password-user-reg").removeAttr("placeholder");
			jQuery(".pengsiadmin-password-user-reg").attr("placeholder", "Password");
		}
		if (jQuery(this).val() == 2) {
			jQuery(".pengsiadmin-password-user-reg").val("");
			jQuery(".pengsiadmin-password-user-reg").removeAttr("readonly");
			jQuery(".pengsiadmin-password-user-reg").removeAttr("placeholder");
			jQuery(".pengsiadmin-password-user-reg").attr("readonly", "readonly");
			jQuery(".pengsiadmin-password-user-reg").attr("placeholder", "Password sama seperti Username");
		}
		if (jQuery(this).val() == 3) {
			jQuery(".pengsiadmin-password-user-reg").val("");
			jQuery(".pengsiadmin-password-user-reg").removeAttr("readonly");
			jQuery(".pengsiadmin-password-user-reg").removeAttr("placeholder");
			jQuery(".pengsiadmin-password-user-reg").attr("readonly", "readonly");
			jQuery(".pengsiadmin-password-user-reg").attr("placeholder", "Password sama seperti Username");
		}
		return false;
	});

	jQuery(document).on("click", ".smep-pengsiadmin-register-user-upload-btn", function(){
		if (jQuery(".pengsiadmin-nama-user-reg").val() != "") {
			if (jQuery(".pengsiadmin-username-user-reg").val() != "") {
				if (jQuery(".pengsiadmin-status-user-reg").val() == 2 || jQuery(".pengsiadmin-status-user-reg").val() == 3) {
					jQuery(".pengsiadmin-password-user-reg").val(jQuery(".pengsiadmin-username-user-reg").val());
					jQuery.ajax({
						type 		: 'AJAX',
						method 		: 'POST',
						url 		: 'administrator/pengguna-aplikasi/upload-data',
						async		: true,
						data 		: jQuery(".pengsiadmin-register-user-form").serialize(),
						dataType 	: 'JSON',
						success 	: function(JSON){
							if (JSON == 0) {
								adminPengsiUserData();
								jQuery(".pengsiadmin-register-user-form")[0].reset();
								jQuery(".smep-pengsiadmin-user-register-modal").modal("hide");
							}
							if (JSON == 1) {
								var alert = "<div class='alert alert-danger alert-dismissible'>"+
					            			"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+
					                		"Username tidak dapat dipakai / username sudah terdaftar"+
					            			"</div>";
								jQuery(".pengsiadmin-register-user-form .modal-body").prepend(alert);
								jQuery(".pengsiadmin-username-user-reg").focus();
							}
						},
						error 		: function(jqXHR, textStatus, errorThrown){
							console.log('failed');
						}
					});
				}
				if (jQuery(".pengsiadmin-status-user-reg").val() == 1) {
					if (jQuery(".pengsiadmin-password-user-reg").val() != "") {
						jQuery.ajax({
							type 		: 'AJAX',
							method 		: 'POST',
							url 		: 'administrator/pengguna-aplikasi/upload-data',
							async		: true,
							data 		: jQuery(".pengsiadmin-register-user-form").serialize(),
							dataType 	: 'JSON',
							success 	: function(JSON){
								if (JSON == 0) {
									adminPengsiUserData();
									jQuery(".pengsiadmin-register-user-form")[0].reset();
									jQuery(".smep-pengsiadmin-user-register-modal").modal("hide");
								}
								if (JSON == 1) {
									var alert = "<div class='alert alert-danger alert-dismissible'>"+
						            			"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>"+
						                		"Username tidak dapat dipakai / username sudah terdaftar"+
						            			"</div>";
									jQuery(".pengsiadmin-register-user-form .modal-body").prepend(alert);
									jQuery(".pengsiadmin-username-user-reg").focus();
								}
							},
							error 		: function(jqXHR, textStatus, errorThrown){
								console.log('failed');
							}
						});
					}
					else{
						jQuery(".pengsiadmin-password-user-reg").focus();
					}
				}
			}
			else{
				jQuery(".pengsiadmin-username-user-reg").focus();
			}
		}
		else{
			jQuery(".pengsiadmin-nama-user-reg").focus();
		}
		return false;
	});
	

	


	// --------------- Update --------------- //
	jQuery(document).on("click", ".smep-pengsiadmin-user-edit-btn", function(){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'GET',
			url 		: 'administrator/pengguna-aplikasi/edit-data/'+jQuery(this).data("id"),
			async		: true,
			dataType 	: 'JSON',
			success 	: function(JSON){

				// Password
				if (JSON[0][1] == 2 || JSON[0][1] == 3) {
					jQuery(".pengsiadmin-password-user-edit").val("");
					jQuery(".pengsiadmin-password-user-edit").removeAttr("readonly");
					jQuery(".pengsiadmin-password-user-edit").removeAttr("placeholder");
					jQuery(".pengsiadmin-password-user-edit").attr("readonly", "readonly");
					if (JSON[0][1] == 2) {
						jQuery(".pengsiadmin-password-user-edit").attr("placeholder", "Password sama seperti Username");	
					}
					if (JSON[0][1] == 3) {
						jQuery(".pengsiadmin-password-user-edit").attr("placeholder", "Password dirubah di SPSE");	
					}
				}
				if (JSON[0][1] == 1) {
					jQuery(".pengsiadmin-password-user-edit").val("Jika tidak ingin rubah password - harap tidak merubah inputan ini");
					jQuery(".pengsiadmin-password-user-edit").removeAttr("readonly");
					jQuery(".pengsiadmin-password-user-edit").removeAttr("placeholder");
					jQuery(".pengsiadmin-password-user-edit").attr("placeholder", "Password");
				}


				// Values
				jQuery(".pengsiadmin-token-user-edit").val(JSON[0][0]);
				jQuery(".pengsiadmin-idstatus-user-edit").val(JSON[0][1]);
				jQuery(".pengsiadmin-status-user-edit").val(JSON[0][2]);
				jQuery(".pengsiadmin-skpd-user-edit").val(JSON[0][3]);
				jQuery(".pengsiadmin-nama-user-edit").val(JSON[0][4]);
				jQuery(".pengsiadmin-username-user-edit").val(JSON[0][5]);
				jQuery(".pengsiadmin-email-user-edit").val(JSON[0][6]);
				jQuery(".pengsiadmin-telepon-user-edit").val(JSON[0][7]);
				jQuery(".pengsiadmin-alamat-user-edit").val(JSON[0][8]);
				jQuery(".smep-pengsiadmin-user-edit-modal").modal("show");
			},
			error 		: function(jqXHR, textStatus, errorThrown){
				console.log('failed');
			}
		});
		return false;
	});

	jQuery(document).on("click", ".smep-pengsiadmin-edit-user-update-btn", function(){
		if (jQuery(".pengsiadmin-idstatus-user-edit").val() == 2 || jQuery(".pengsiadmin-idstatus-user-edit").val() == 3) {
			jQuery.ajax({
				type 		: 'AJAX',
				method 		: 'POST',
				url 		: 'administrator/pengguna-aplikasi/update-data',
				async		: true,
				data 		: jQuery(".pengsiadmin-edit-user-form").serialize(),
				dataType 	: 'JSON',
				success 	: function(JSON){
					adminPengsiUserData();
					jQuery(".pengsiadmin-edit-user-form")[0].reset();
					jQuery(".smep-pengsiadmin-user-edit-modal").modal("hide");
				},
				error 		: function(jqXHR, textStatus, errorThrown){
						console.log('failed');
				}
			});
		}
		if (jQuery(".pengsiadmin-idstatus-user-edit").val() == 1) {
			if (jQuery(".pengsiadmin-password-user-edit").val() == "Jika tidak ingin rubah password - harap tidak merubah inputan ini") {
				jQuery.ajax({
					type 		: 'AJAX',
					method 		: 'POST',
					url 		: 'administrator/pengguna-aplikasi/update-data',
					async		: true,
					data 		: jQuery(".pengsiadmin-edit-user-form").serialize(),
					dataType 	: 'JSON',
					success 	: function(JSON){
						adminPengsiUserData();
						jQuery(".pengsiadmin-edit-user-form")[0].reset();
						jQuery(".smep-pengsiadmin-user-edit-modal").modal("hide");
					},
					error 		: function(jqXHR, textStatus, errorThrown){
						console.log('failed');
					}
				});
			}
			else{
				if (jQuery(".pengsiadmin-password-user-edit").val() != "") {
					jQuery.ajax({
						type 		: 'AJAX',
						method 		: 'POST',
						url 		: 'administrator/pengguna-aplikasi/update-data',
						async		: true,
						data 		: jQuery(".pengsiadmin-edit-user-form").serialize(),
						dataType 	: 'JSON',
						success 	: function(JSON){
							adminPengsiUserData();
							jQuery(".pengsiadmin-edit-user-form")[0].reset();
							jQuery(".smep-pengsiadmin-user-edit-modal").modal("hide");
						},
						error 		: function(jqXHR, textStatus, errorThrown){
							console.log('failed');
						}
					});
				}
				else{
					jQuery(".pengsiadmin-password-user-edit").focus();
				}
			}
		}
		return false;
	});

	jQuery(document).on("click", ".smep-pengsiadmin-edit-user-cancel-btn", function(){
		jQuery(".pengsiadmin-edit-user-form")[0].reset();
		jQuery(".smep-pengsiadmin-user-edit-modal").modal("hide");
		return false;
	});



	// --------------- Delete --------------- //
	jQuery(document).on("click", ".smep-pengsiadmin-user-delete-btn", function(){
		if (confirm("Apakah anda yakin ingin menghapus data ini ?") == true) {
			jQuery.ajax({
				type 		: 'AJAX',
				method 		: 'GET',
				url 		: 'administrator/pengguna-aplikasi/delete-data/'+jQuery(this).data("id"),
				async		: true,
				dataType 	: 'JSON',
				success 	: function(JSON){
					adminPengsiUserData();
				},
				error 		: function(jqXHR, textStatus, errorThrown){
					console.log('failed');
				}
			});
			return false;
		}
	});

	// --------------- Generate Data --------------- //
	jQuery(document).on("click", ".smep-pengsiadmin-generate-user-btn", function(){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'GET',
			url 		: 'administrator/pengguna-aplikasi/generate-data',
			async		: true,
			dataType 	: 'JSON',
			success 	: function(JSON){
				adminPengsiUserData();
			},
			error 		: function(jqXHR, textStatus, errorThrown){
				console.log('failed');
			}
		});
		return false;
	});
});