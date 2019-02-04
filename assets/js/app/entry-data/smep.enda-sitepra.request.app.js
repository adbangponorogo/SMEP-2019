jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	jQuery(document).on("click", ".smep-sitepraenda-edit-btn", function(){
		jQuery(".smep-unique-token").val(jQuery(this).data("id"));
		jQuery(".smep-content-page").load("entry-data/realisasi-tepra/form-page");
		return false;
	});


	// ---------------- Register ---------------- //

	jQuery(document).on("click", ".smep-sitepraenda-save-btn", function(){
		jQuery.ajax({
          	type      	: 'AJAX',
          	method    	: 'POST',
          	url       	: 'entry-data/realisasi-tepra/save-data',
          	async     	: true,
          	data  		: jQuery(".sitepraenda-update-form").serialize(),
          	dataType  	: 'JSON',
          	success   	: function(JSON){
          		jQuery(".smep-content-page").load("entry-data/realisasi-tepra/main-page");
          	},
          	error     	: function(jqXHR, textStatus, errorThrown){
              	location.href = window.location.href+"app/auth/sessionPage";
          	}
        });
		return false;
	});

	jQuery(document).on("click", ".smep-sitepraenda-cancel-btn", function(){
		jQuery(".smep-content-page").load("entry-data/realisasi-tepra/main-page");
		return false;
	});
});