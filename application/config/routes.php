<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Main_controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = 'FALSE';


// ------------------ Application ------------------ //
// ------ Authentication ------ //
$route['app/auth/loginPage'] = 'app/Applogin_controller/loginPage';
$route['app/auth/sessionPage'] = 'app/Applogin_controller/LostSessionPage';
$route['app/auth/login'] = 'app/Applogin_controller/loginProcess';
$route['app/auth/checkSession'] = 'app/Applogin_controller/checkSession';
$route['app/auth/logout'] = 'app/Applogin_controller/logoutProcess';

// ------ Main ------ //
$route['app/main/data-user'] = 'Main_controller/getUserData';
$route['app/main/data-skpd'] = 'Main_controller/getDataSKPD';
$route['app/main/tester'] = 'Main_controller/getDataTester';


// ------ Dashboard ------ //
$route['app/dashboard/main-page'] = 'app/Appdashboard_controller/mainPage';





// ------------------ Data Umum ------------------ //
// ------ Penanggung Jawab ------ //
$route['data-umum/penanggung-jawab/main-page'] = 'data-umum/Datumpenja_controller/mainPage';
$route['data-umum/penanggung-jawab/main-data/(:any)'] = 'data-umum/Datumpenja_controller/getData/$1';
$route['data-umum/penanggung-jawab/upload-data'] = 'data-umum/Datumpenja_controller/sendData';
$route['data-umum/penanggung-jawab/edit-data/(:any)'] = 'data-umum/Datumpenja_controller/changeData/$1';
$route['data-umum/penanggung-jawab/update-data'] = 'data-umum/Datumpenja_controller/updateData';
$route['data-umum/penanggung-jawab/delete-data/(:any)'] = 'data-umum/Datumpenja_controller/trashData/$1';

// ------ Kegiatan ------ //
$route['data-umum/kegiatan/main-page'] = 'data-umum/Datumdaftan_controller/mainPage';
$route['data-umum/kegiatan/edit-kegiatan-page'] = 'data-umum/Datumdaftan_controller/editKegiatanPage';
$route['data-umum/kegiatan/program-data/(:any)'] = 'data-umum/Datumdaftan_controller/getDataProgram/$1';
$route['data-umum/kegiatan/kegiatan-data/(:any)/(:any)'] = 'data-umum/Datumdaftan_controller/getDataKegiatan/$1/$2';
$route['data-umum/kegiatan/edit-data-kegiatan/(:any)/(:any)'] = 'data-umum/Datumdaftan_controller/changeDataKegiatan/$1/$2';
$route['data-umum/kegiatan/pptk-data/(:any)'] = 'data-umum/Datumdaftan_controller/getDataPPTK/$1';
$route['data-umum/kegiatan/edit-pptk-data/(:any)'] = 'data-umum/Datumdaftan_controller/getDataPPTKUnique/$1';
$route['data-umum/kegiatan/save-data'] = 'data-umum/Datumdaftan_controller/saveData';

// ------ Data Organisasi ------ //
$route['data-umum/data-organisasi/main-page'] = 'data-umum/Datumdator_controller/mainPage';
$route['data-umum/data-organisasi/main-data/(:any)'] = 'data-umum/Datumdator_controller/getMainData/$1';
$route['data-umum/data-organisasi/save-data'] = 'data-umum/Datumdator_controller/saveData';

// ------ Pagu Kegiatan ------ //
$route['data-umum/pagu-kegiatan/main-page'] = 'data-umum/Datumpatan_controller/mainPage';
$route['data-umum/pagu-kegiatan/rincian-page'] = 'data-umum/Datumpatan_controller/rincianPage';
$route['data-umum/pagu-kegiatan/program-data/(:any)'] = 'data-umum/Datumpatan_controller/getDataProgram/$1';
$route['data-umum/pagu-kegiatan/pagu-skpd-data/(:any)'] = 'data-umum/Datumpatan_controller/getDataPaguSKPD/$1';
$route['data-umum/pagu-kegiatan/kegiatan-data/(:any)/(:any)'] = 'data-umum/Datumpatan_controller/getDataKegiatan/$1/$2';
$route['data-umum/pagu-kegiatan/program-kegiatan-data/(:any)/(:any)'] = 'data-umum/Datumpatan_controller/getDataKegiatanSumberDana/$1/$2';
$route['data-umum/pagu-kegiatan/sumber-dana-data/(:any)/(:any)'] = 'data-umum/Datumpatan_controller/getDataSumberData/$1/$2';
$route['data-umum/pagu-kegiatan/save-data/(:any)/(:any)'] = 'data-umum/Datumpatan_controller/saveData/$1/$2';

// ------ Laporan Pencairan SP2D ------ //
$route['data-umum/pencairan-sppd/main-page'] = 'data-umum/Datumdapan_controller/mainPage';
$route['data-umum/pencairan-sppd/skpd/all/(:any)'] = 'data-umum/Datumdapan_controller/getMainDataAllSKPD/$1';
$route['data-umum/pencairan-sppd/kegiatan/all/(:any)'] = 'data-umum/Datumdapan_controller/getMainDataAllKegiatan/$1';
$route['data-umum/pencairan-sppd/print-data'] = 'data-umum/Datumdapan_controller/getPrintData';


// ------ Target Keuangan ------ //
$route['data-umum/target-keuangan/main-page'] = 'data-umum/Datumtangan_controller/mainPage';
$route['data-umum/target-keuangan/main-data/(:any)'] = 'data-umum/Datumtangan_controller/getMainData/$1';
$route['data-umum/target-keuangan/keuangan-data/(:any)'] = 'data-umum/Datumtangan_controller/getMainKeuanganData/$1';
$route['data-umum/target-keuangan/save-data'] = 'data-umum/Datumtangan_controller/saveData';

// ------ Target Fisik ------ //
$route['data-umum/target-fisik/main-page'] = 'data-umum/Datumtasik_controller/mainPage';
$route['data-umum/target-fisik/main-data/(:any)'] = 'data-umum/Datumtasik_controller/getMainData/$1';
$route['data-umum/target-fisik/fisik-data/(:any)'] = 'data-umum/Datumtasik_controller/getMainFisikData/$1';
$route['data-umum/target-fisik/save-data'] = 'data-umum/Datumtasik_controller/saveData';



// ------------------ Entry Data ------------------ //
// ------ Rencana Umum Pengadaan ------ //
$route['entry-data/data-rup/main-page'] = 'entry-data/Endarup_controller/mainPage';
$route['entry-data/data-rup/users-data'] = 'entry-data/Endarup_controller/getDataUser';
$route['entry-data/data-rup/users-data/ppk/(:any)'] = 'entry-data/Endarup_controller/getDataUserPPK/$1';
$route['entry-data/data-rup/main-data/program/all/(:any)'] = 'entry-data/Endarup_controller/getMainDataAllProgram/$1';
$route['entry-data/data-rup/main-data/kegiatan/all/(:any)/(:any)'] = 'entry-data/Endarup_controller/getMainDataAllKegiatan/$1/$2';
$route['entry-data/data-rup/main-data/rincian-obyek/all/(:any)/(:any)/(:any)'] = 'entry-data/Endarup_controller/getMainDataAllRincianObyek/$1/$2/$3';
$route['entry-data/data-rup/main-data/rincian-obyek/unique/(:any)/(:any)/(:any)/(:any)'] = 'entry-data/Endarup_controller/getMainDataUniqueRincianObyek/$1/$2/$3/$4';
$route['entry-data/data-rup/register-page'] = 'entry-data/Endarup_controller/registerDataPage';
$route['entry-data/data-rup/skpd-data/(:any)'] = 'entry-data/Endarup_controller/getDataSKPD/$1';
$route['entry-data/data-rup/skpd-data-unique/(:any)'] = 'entry-data/Endarup_controller/getDataSKPDOther/$1';
$route['entry-data/data-rup/mak-data/(:any)'] = 'entry-data/Endarup_controller/getDataMAK/$1';
$route['entry-data/data-rup/program-data/(:any)'] = 'entry-data/Endarup_controller/getDataProgram/$1';
$route['entry-data/data-rup/kegiatan-data/(:any)'] = 'entry-data/Endarup_controller/getDataKegiatan/$1';
$route['entry-data/data-rup/rincian-obyek-data/(:any)'] = 'entry-data/Endarup_controller/getDataRincianObyek/$1';
$route['entry-data/data-rup/rincian-obyek-unique-data/(:any)'] = 'entry-data/Endarup_controller/getDataRincianObyekUnique/$1';
$route['entry-data/data-rup/upload-data'] = 'entry-data/Endarup_controller/uploadData';
$route['entry-data/data-rup/edit-page'] = 'entry-data/Endarup_controller/editDataPage';
$route['entry-data/data-rup/edit-data/(:any)'] = 'entry-data/Endarup_controller/changeData/$1';
$route['entry-data/data-rup/update-data'] = 'entry-data/Endarup_controller/updateData';
$route['entry-data/data-rup/delete-data/(:any)'] = 'entry-data/Endarup_controller/trashData/$1';
$route['entry-data/data-rup/multi-delete-data'] = 'entry-data/Endarup_controller/multiTrashData';


// ------ Realisasi - Rencana Umum Pengadaan ------ //
$route['entry-data/realisasi-rup/main-page'] = 'entry-data/Endasirup_controller/mainPage';
$route['entry-data/realisasi-rup/kegiatan-data/(:any)'] = 'entry-data/Endasirup_controller/getDataAllKegiatan/$1';
$route['entry-data/realisasi-rup/realisasi-page'] = 'entry-data/Endasirup_controller/realisasiPage';
$route['entry-data/realisasi-rup/main-data/kegiatan/all/(:any)/(:any)'] = 'entry-data/Endasirup_controller/getRUPDataAllKegiatan/$1/$2';
$route['entry-data/realisasi-rup/register-page'] = 'entry-data/Endasirup_controller/registerDataPage';
$route['entry-data/realisasi-rup/rup-data/(:any)'] = 'entry-data/Endasirup_controller/getDataRUP/$1';
$route['entry-data/realisasi-rup/main-data/rup-realisasi/all/(:any)'] = 'entry-data/Endasirup_controller/getDataRUPRealisasi/$1';
$route['entry-data/realisasi-rup/main-data/realisasi/all/(:any)'] = 'entry-data/Endasirup_controller/getMainDataRealisasi/$1';
$route['entry-data/realisasi-rup/upload-data'] = 'entry-data/Endasirup_controller/uploadData';
$route['entry-data/realisasi-rup/edit-page'] = 'entry-data/Endasirup_controller/editDataPage';
$route['entry-data/realisasi-rup/add-data/(:any)'] = 'entry-data/Endasirup_controller/changeDataAdd/$1';
$route['entry-data/realisasi-rup/edit-data/(:any)'] = 'entry-data/Endasirup_controller/changeData/$1';
$route['entry-data/realisasi-rup/update-data'] = 'entry-data/Endasirup_controller/updateData';
$route['entry-data/realisasi-rup/delete-data/(:any)'] = 'entry-data/Endasirup_controller/trashData/$1';
$route['entry-data/realisasi-rup/multi-delete-data'] = 'entry-data/Endasirup_controller/multiTrashData';


// ------ Realisasi - TEPRA ------ //
$route['entry-data/realisasi-tepra/main-page'] = 'entry-data/Endasitepra_controller/mainPage';
$route['entry-data/realisasi-tepra/form-page'] = 'entry-data/Endasitepra_controller/formPage';
$route['entry-data/realisasi-tepra/main-data/(:any)'] = 'entry-data/Endasitepra_controller/getMainData/$1';
$route['entry-data/realisasi-tepra/realsiasi-tepra-data/(:any)'] = 'entry-data/Endasitepra_controller/getDataRealisasiTepra/$1';
$route['entry-data/realisasi-tepra/save-data'] = 'entry-data/Endasitepra_controller/saveData';


// ------------------/ Laporan Danang /------------------ //

// ------ Rencana Pengadaan ------ //
$route['laporan/rp/main-page'] = 'laporan/Rp_controller';
$route['laporan/rp/print-data'] = 'laporan/Rp_controller/getPrintData';

// ------ Realisasi Belanja Langsung ------ //
$route['laporan/ap/main-page'] = 'laporan/Ap_controller';
$route['laporan/ap/print-data'] = 'laporan/Ap_controller/getPrintData';

// ------------------/ Laporan Danang /------------------ //


// ------------------ Laporan ------------------ //
// ------ Rencana Pengadaan ------ //
$route['laporan/rencana-pengadaan/main-page'] = 'laporan/Laporrapan_controller/mainPage';
$route['laporan/rencana-pengadaan/skpd/all/(:any)'] = 'laporan/Laporrapan_controller/getMainDataAllSKPD/$1';
$route['laporan/rencana-pengadaan/print-data'] = 'laporan/Laporrapan_controller/getPrintData';

// ------ TEPRA ------ //
// --- Perencanaan --- //
$route['laporan/tepra/perencanaan/main-page'] = 'laporan/Laportepraperencanaan_controller/mainPage';
$route['laporan/tepra/perencanaan/skpd/all/(:any)'] = 'laporan/Laportepraperencanaan_controller/getMainDataAllSKPD/$1';
$route['laporan/tepra/perencanaan/dana/(:any)'] = 'laporan/Laportepraperencanaan_controller/getDataDana/$1';
$route['laporan/tepra/perencanaan/pagu-paket/(:any)/(:any)'] = 'laporan/Laportepraperencanaan_controller/getDataPaketPaguRUP/$1/$2';
$route['laporan/tepra/perencanaan/paket/(:any)/(:any)'] = 'laporan/Laportepraperencanaan_controller/getDataPaketRUP/$1/$2';
$route['laporan/tepra/perencanaan/rekap-paket/(:any)'] = 'laporan/Laportepraperencanaan_controller/getDataRekapRUP/$1';
$route['laporan/tepra/perencanaan/print-data'] = 'laporan/Laportepraperencanaan_controller/getPrintData';


// --- Realisasi --- //
$route['laporan/tepra/realisasi/main-page'] = 'laporan/Laporteprarealisasi_controller/mainPage';
$route['laporan/tepra/realisasi/main-data/(:any)/(:any)'] = 'laporan/Laporteprarealisasi_controller/getMainData/$1/$2';
$route['laporan/tepra/realisasi/skpd/all/(:any)'] = 'laporan/Laporteprarealisasi_controller/getMainDataAllSKPD/$1';
$route['laporan/tepra/realisasi/print-data'] = 'laporan/Laporteprarealisasi_controller/getPrintData';

// ------ Laporan Pengadaan ------ //
$route['laporan/laporan-pengadaan/main-page'] = 'laporan/Laporlapan_controller/mainPage';
$route['laporan/laporan-pengadaan/skpd/all/(:any)'] = 'laporan/Laporlapan_controller/getMainDataAllSKPD/$1';
$route['laporan/laporan-pengadaan/print-data'] = 'laporan/Laporlapan_controller/getPrintData';

// ------ Realisasi Belanja Langsung ------ //
$route['laporan/realisasi-belanja-langsung/main-page'] = 'laporan/Laporrebela_controller/mainPage';
$route['laporan/realisasi-belanja-langsung/skpd/all/(:any)'] = 'laporan/Laporrebela_controller/getMainDataAllSKPD/$1';
$route['laporan/realisasi-belanja-langsung/print-data'] = 'laporan/Laporrebela_controller/getPrintData';


// ------ Berita Acara Serah Terima ------ //
$route['laporan/bast/main-page'] = 'laporan/Laporbast_controller/mainPage';
$route['laporan/bast/(:any)'] = 'laporan/Laporbast_controller/getMainData/$1';

// ------ Rencana Umum Pengadaan ------ //
$route['laporan/rup/main-page'] = 'laporan/Laporrup_controller/mainPage';
$route['laporan/rup/skpd/all/(:any)'] = 'laporan/Laporrup_controller/getMainDataAllSKPD/$1';
$route['laporan/rup/print-data'] = 'laporan/Laporrup_controller/getPrintData';






// ------------------ Administrator ------------------ //
// ------ Pengguna Aplikasi ------ //
$route['administrator/pengguna-aplikasi/main-page'] = 'administrator/Adminpengsi_controller/mainPage';
$route['administrator/pengguna-aplikasi/user-page'] = 'administrator/Adminpengsi_controller/userPage';
$route['administrator/pengguna-aplikasi/main-data'] = 'administrator/Adminpengsi_controller/getMainData';
$route['administrator/pengguna-aplikasi/user-ppk/(:any)'] = 'administrator/Adminpengsi_controller/getUserDataPPK/$1';
$route['administrator/pengguna-aplikasi/user-data/(:any)'] = 'administrator/Adminpengsi_controller/getUserData/$1';
$route['administrator/pengguna-aplikasi/data-skpd/(:any)'] = 'administrator/Adminpengsi_controller/getDataSKPD/$1';
$route['administrator/pengguna-aplikasi/upload-data'] = 'administrator/Adminpengsi_controller/sendData';
$route['administrator/pengguna-aplikasi/edit-data/(:any)'] = 'administrator/Adminpengsi_controller/changeData/$1';
$route['administrator/pengguna-aplikasi/update-data'] = 'administrator/Adminpengsi_controller/updateData';
$route['administrator/pengguna-aplikasi/delete-data/(:any)'] = 'administrator/Adminpengsi_controller/trashData/$1';
$route['administrator/pengguna-aplikasi/generate-data'] = 'administrator/Adminpengsi_controller/GenerateData';


// ------ Rekapitulasi Rencana Pengadaan ------ //
$route['administrator/rencana-pengadaan/main-page'] = 'administrator/Adminrapan_controller/mainPage';
$route['administrator/rencana-pengadaan/print-data'] = 'administrator/Adminrapan_controller/getPrintData';


// ------ Rekapitulasi Rencana Umum Pengadaan ------ //
$route['administrator/rup/main-page'] = 'administrator/Adminrup_controller/mainPage';
$route['administrator/rup/skpd/all/(:any)'] = 'administrator/Adminrup_controller/getMainDataAllSKPD/$1';
$route['administrator/rup/print-data'] = 'administrator/Adminrup_controller/getPrintData';

// ------ Rekapitulasi Laporan Pengadaan ------ //
$route['administrator/laporan-pengadaan/main-page'] = 'administrator/Adminlapan_controller/mainPage';
$route['administrator/laporan-pengadaan/print-data'] = 'administrator/Adminlapan_controller/getPrintData';

// ------ Rekapitulasi Realisasi Belanja Langsung ------ //
$route['administrator/realisasi-belanja-langsung/main-page'] = 'administrator/Adminrebela_controller/mainPage';
$route['administrator/realisasi-belanja-langsung/print-data'] = 'administrator/Adminrebela_controller/getPrintData';

// ------ Rekapitulasi Misc ------ //
$route['administrator/misc/main-page'] = 'administrator/Adminmisc_controller/mainPage';
$route['administrator/misc/skpd/all/(:any)'] = 'administrator/Adminmisc_controller/getMainDataAllSKPD/$1';
$route['administrator/misc/kegiatan/all/(:any)'] = 'administrator/Adminmisc_controller/getMainDataAllKegiatan/$1';
$route['administrator/misc/print-data'] = 'administrator/Adminmisc_controller/getPrintData';


// ------ Rekapitulasi Laporan Umum Pengadaan Aktual ------ //
$route['administrator/rup-aktual/main-page'] = 'administrator/Adminrupaktual_controller/mainPage';
$route['administrator/rup-aktual/skpd/all/(:any)'] = 'administrator/Adminrupaktual_controller/getMainDataAllSKPD/$1';
$route['administrator/rup-aktual/print-data'] = 'administrator/Adminrupaktual_controller/getPrintData';


// ------ Konfigurasi ------ //
$route['administrator/konfigurasi/main-page'] = 'administrator/Adminkonfigurasi_controller/mainPage';
$route['administrator/konfigurasi/edit-data'] = 'administrator/Adminkonfigurasi_controller/changeData';
$route['administrator/konfigurasi/update-data'] = 'administrator/Adminkonfigurasi_controller/updateData';
$route['administrator/konfigurasi/trash-image'] = 'administrator/Adminkonfigurasi_controller/trashImage';

