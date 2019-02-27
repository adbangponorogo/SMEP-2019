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
				location.href = window.location.href+"app/auth/sessionPage";
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
				location.href = window.location.href+"app/auth/sessionPage";
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
	checkMainSKPD();
	function checkMainSKPD(){
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
				appDashboardBoxData(JSON[0][0]);
				appDashboardTableRealisasiData(JSON[0][0]);
				appDashboardChartRealisasi(JSON[0][0]);
				appDashboardTableTemporary();
			},
			error 		: function(jqXHR, textStatus, errorThrown){
				// location.href = window.location.href+"app/auth/sessionPage";
			}
		});
	}
	
	jQuery(document).on("change", ".smep-skpd-categories-main", function(){
		jQuery(".smep-skpd-main").val(jQuery(this).val());
	});

	// ---------------- Auto Focus Value Input ---------------- //
	jQuery(document).on('focus', 'input', function(){
		jQuery(this).select();
	});

	// ---------------- Auto Focus Value Input ---------------- //
	jQuery(document).on('focus', 'textarea', function(){
		jQuery(this).select();
	});
});