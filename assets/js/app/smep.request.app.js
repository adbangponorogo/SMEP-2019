jQuery(document).ready(function(){
	// --- Authentication ---
	function checkSession(){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'POST',
			url 		: 'app/auth/checkSession',
			async		: true,
			data 		: jQuery(".auth-login-form").serialize(),
			dataType 	: 'JSON',
			success 	: function(JSON){
				if (JSON == 0) {
						location.href='';
				}
				if (JSON == 1) {
					location.href='app/auth/loginPage';
				}
			},
			error		: function(jqXHR, textStatus, errorThrown){
				console.log('failed');
			}
		});
	}
	
	// --- Routes ---
	jQuery(document).on("click", ".smep-logout-auth", function(){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'GET',
			url 		: 'app/auth/logout',
			async		: true,
			dataType 	: 'JSON',
			success 	: function(JSON){
				checkSession();
			},
			error 		: function(jqXHR, textStatus, errorThrown){
				console.log('failed');
			}
		});
		return false;
	});

	// --- Window Preload ---
	windowLoad();
	function windowLoad(){
		var preload = "<div class='loader'><img src='assets/uploads/preload-animate.gif'></div>";
		jQuery('body').append(preload);
		jQuery(window).on("load", function(){
			jQuery(".loader").fadeOut("slow", function(){
				jQuery(this).remove();
			});
		});
	}

	jQuery(document).ajaxStart(function(){
		var preload = "<div class='loader'><img src='assets/uploads/preload-animate.gif'></div>";
		jQuery('body').append(preload);
	});
	jQuery(document).ajaxComplete(function(){
		jQuery(".loader").fadeOut("slow", function(){
			jQuery(this).remove();
		});
	});


	// --- SKPD Option ---
	jQuery.ajax({
		type 		: 'AJAX',
		method 		: 'GET',
		url 		: 'app/main/data-skpd',
		async		: true,
		dataType 	: 'JSON',
		success 	: function(JSON){
			var option = '';
			for (var skpd = 0; skpd < JSON.length; skpd++) {
				option += "<option value='"+JSON[skpd][0]+"'>["+JSON[skpd][1]+"] - "+
				JSON[skpd][2]+"</option>";
			}
			jQuery(".smep-skpd-categories-main").html(option);
			jQuery(".smep-skpd-categories-main").val(JSON[0][0]).change();
			jQuery(".smep-skpd-categories-main").select2();
		},
		error 		: function(jqXHR, textStatus, errorThrown){
			console.log('failed');
		}
	});
	
	jQuery(document).on("change", ".smep-skpd-categories-main", function(){
		jQuery(".smep-skpd-main").val(jQuery(this).val());
	});

});