<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applogin_controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("app/Applogin_model", "app_model");
	}

	public function loginPage(){
		global $smep;

		if (empty($this->session->userdata('auth_id'))) {
			$data['title'] = strtoupper('SMEP '.$smep->tingkat.' '.$smep->klpd);
			$data['logo'] = $smep->logo;
			$this->load->view('Login_view', $data);
		}
		else{
			redirect(base_url());
		}
	}

	public function LostSessionPage(){
		global $smep;
		
		if (empty($this->session->userdata('auth_id'))) {
			$data['title'] = strtoupper('SMEP '.$smep->tingkat.' '.$smep->klpd);
			$data['logo'] = $smep->logo;
			$this->load->view('Session_view', $data);
		}
		else{
			redirect(base_url());
		}
	}

	public function loginProcess(){
		$username = $this->input->post("username");
		$password = md5($this->input->post("password"));
		$resultUsers = $this->app_model->getDataUsers($username, $password);
		if ($resultUsers->num_rows() > 0) {
			foreach ($resultUsers->result() as $rows_users) {
				// Insert To Temporary
				$data = array(
							"kd_skpd" => $rows_users->kd_skpd,
							"id_users" => $rows_users->id,
							"keterangan"	=> "Melakukan Login",
							"ip_address"	=> $this->getIPClient(),
						);
				$this->app_model->insertTemporary($data);


				// Create Season
				$this->session->set_userdata(array(
					"auth_id" => $rows_users->id
					));

				echo json_encode(array("status"=>TRUE));
			}
		}
		else{
			redirect(base_url());
		}
	} 

	public function checkSession(){
		if ($this->session->userdata('auth_id') != '') {
			echo json_encode(0);
		}
		else{
			echo json_encode(1);
		}
	}

	public function logoutProcess(){
		if (!empty($this->session->userdata('auth_id'))) {
			$this->session->unset_userdata(array('auth_id'));
		}
		echo json_encode(array("status"=>TRUE));
	}

	public function getIPClient(){
		// Get real visitor IP behind CloudFlare network
	    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
	              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	    }
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }

	    return $ip;
	}


	public function getDataTester(){
		// 
	}

}