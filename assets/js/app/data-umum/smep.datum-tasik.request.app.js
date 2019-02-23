jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	jQuery(document).on("click", ".smep-datumtasik-save-btn", function(){
		var januari = jQuery(".tasikdatum-januari-update").val();
		var februari = jQuery(".tasikdatum-februari-update").val();
		var maret = jQuery(".tasikdatum-maret-update").val();
		var april = jQuery(".tasikdatum-april-update").val();
		var mei = jQuery(".tasikdatum-mei-update").val();
		var juni = jQuery(".tasikdatum-juni-update").val();
		var juli = jQuery(".tasikdatum-juli-update").val();
		var agustus = jQuery(".tasikdatum-agustus-update").val();
		var september = jQuery(".tasikdatum-september-update").val();
		var oktober = jQuery(".tasikdatum-oktober-update").val();
		var november = jQuery(".tasikdatum-november-update").val();
		var desember = jQuery(".tasikdatum-desember-update").val();
		var hasil_fisik = januari+februari+maret+april+mei+juni+juli+agustus+september+oktober+november+desember;
		jQuery.ajax({
		    type      : 'AJAX',
		    method    : 'POST',
		    url       : 'data-umum/target-fisik/save-data',
		    async     : true,
		    data  	: jQuery(".tasikdatum-update-form").serialize(),
		    dataType  : 'JSON',
		    success   : function(JSON){
		    	datumTasikMainData();
		      	datumTasikRencanaFisikData();
		    },
		    error     : function(jqXHR, textStatus, errorThrown){
		        location.href = window.location.href+"app/auth/sessionPage";
		    }
		});
		return false;
	});
});