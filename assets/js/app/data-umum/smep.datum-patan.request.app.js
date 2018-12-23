jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	jQuery(document).on("click", ".smep-patandatum-rincian-get-btn", function(){
		jQuery(".smep-unique-token").val(jQuery(this).data("id"));
		jQuery(".smep-content-page").load("data-umum/pagu-kegiatan/rincian-page");
		return false;
	});

	// ---------------- Update ---------------- //
	jQuery(document).on("change", ".patandatum-sumber-rincian-obyek-update", function(){
		jQuery.ajax({
	      	type      : 'AJAX',
	      	method    : 'GET',
	      	url       : 'data-umum/pagu-kegiatan/save-data/'+jQuery(this).data("id")+'/'+jQuery(this).val(),
	      	async     : true,
	      	dataType  : 'JSON',
	      	success   : function(JSON){
	      		datumPatanSumberDanaData();
	      	},
	      	error     : function(jqXHR, textStatus, errorThrown){
	        	console.log('failed');
	      	}
	    });
		return false;
	});
});