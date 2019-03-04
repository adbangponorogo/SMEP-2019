jQuery(document).ready(function(){
	// --- Window Preload ---
	windowLoad();
	function windowLoad(){
		var preload = "<div class='loader'><img src='../../assets/uploads/preload-animate.gif'></div>";
		jQuery('body').append(preload);
		jQuery(window).on("load", function(){
			jQuery(".loader").fadeOut("slow", function(){
				jQuery(this).remove();
			});
		});
	}

	jQuery(document).ajaxStart(function(){
		var preload = "<div class='loader'><img src='../../assets/uploads/preload-animate.gif'></div>";
		jQuery('body').append(preload);
	});
	jQuery(document).ajaxComplete(function(){
		jQuery(".loader").fadeOut("slow", function(){
			jQuery(this).remove();
		});
	});

	// --- Authentication ---
	// checkSession();
	function checkSession(){
		jQuery.ajax({
			type 		: 'AJAX',
			method 		: 'POST',
			url 		: 'checkSession',
			async		: true,
			data 		: jQuery(".auth-login-form").serialize(),
			dataType 	: 'JSON',
			success 	: function(JSON){
				if (JSON == 0) {
						location.href='../../';
				}
				if (JSON == 1) {
					location.href='';
				}
			},
			error		: function(jqXHR, textStatus, errorThrown){
				console.log('failed');
			}
		});
	}
	
	jQuery(document).on("click", ".smep-auth-login-btn", function(){
		if (jQuery(".smep-username-auth").val() != "") {
			if (jQuery(".smep-password-auth").val() != "") {
				jQuery.ajax({
					type 		: 'AJAX',
					method 		: 'POST',
					url 		: 'login',
					async		: true,
					data 		: jQuery(".auth-login-form").serialize(),
					dataType 	: 'JSON',
					success 	: function(JSON){
						checkSession();
					},
					error		: function(jqXHR, textStatus, errorThrown){
						jQuery(".smep-auth-modal").modal("show");
						console.log('failed');
					}
				});
			}
			else{
				jQuery(".smep-password-auth").focus();
			}
		}
		else{
			jQuery(".smep-username-auth").focus();
		}
		return false;
	});

	jQuery(document).on("click", ".smep-auth-close-btn", function(){
		jQuery(".smep-auth-modal").modal("hide");
		return false;
	});
});