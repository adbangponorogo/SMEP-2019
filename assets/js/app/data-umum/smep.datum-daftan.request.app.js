jQuery(document).ready(function(){
	// ---------------- Routes ---------------- //
	jQuery(document).on("click", ".smep-daftandatum-edit-btn", function(){
		jQuery(".smep-content-page").load("data-umum/kegiatan/edit-kegiatan-page");
		jQuery(".smep-unique-token").val(jQuery(this).data("id"));
		return false;
	});
});