jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	// jQuery(document).on("click", "", function(){
	// 	jQuery(".smep-content-page").load("");
	// 	return false;
	// });

	
	// ---------------- Update Data ---------------- //
	jQuery(document).on("click", ".smep-daftandator-save-btn", function(){
		jQuery.ajax({
	      type      : 'AJAX',
	      method    : 'POST',
	      url       : 'data-umum/data-organisasi/save-data',
	      async     : true,
	      data   	: jQuery('.daftandator-update-form').serialize(),
	      dataType  : 'JSON',
	      success   : function(JSON){
			jQuery(".smep-content-page").load("data-umum/data-organisasi/main-page");
	      },
	      error     : function(jqXHR, textStatus, errorThrown){
	       location.href = window.location.href+"app/auth/sessionPage";
	      }
	    });
		return false;
	});	
});