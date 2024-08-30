<?php defined('BASEPATH') or exit('No direct script access allowed');

class Owner_Controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('bcrypt');
	}

	/**
	 * Login
	 */
	public function index()
	{


		$data['title'] = "Group";
		$data['description'] = "Group";
		$data['keywords'] = "Group";
		$data['data'] = $this->auth_model->get_group();

		$this->load->view('partials/_header', $data);
		$this->load->view('owner/index');
		$this->load->view('partials/_footer');
	}
	public function detail($id)
	{
		$id = str_replace(array('{', '}'), '', $id);

		//slider posts
		$this->slider_posts = get_cached_data('slider_posts');
		if (empty($this->slider_posts)) {
			$this->slider_posts = $this->post_model->get_slider_posts();
			set_cache_data('slider_posts', $this->slider_posts);
		}

		$count_key = 'posts_count';
		$posts_key = 'posts';
		//posts count
		$total_rows = get_cached_data($count_key);
		if (empty($total_rows)) {
			$total_rows = $this->post_model->get_post_count();
			set_cache_data($count_key, $total_rows);
		}
		//set paginated
		$pagination = $this->paginate(lang_base_url(), $total_rows);
		$data['posts'] = get_cached_data($posts_key . '_page' . $pagination['current_page']);
		if (empty($data['posts'])) {
			$data['posts'] = $this->post_model->get_paginated_user_posts($id, $pagination['per_page'], $pagination['offset']);
			set_cache_data($posts_key . '_page' . $pagination['current_page'], $data['posts']);
		}



		$data['k'] = $this->auth_model->get_group_id($id);
		$data['j'] = $this->owner_model->get_jadwal($id);

		$data["following"] = $this->profile_model->get_following_users($id);
		$data["followers"] = $this->profile_model->get_followers($id);		$data['title'] = trans("login");
		$data['description'] = trans("login") . " - " . $this->settings->application_name;
		$data['keywords'] = trans("login") . "," . $this->settings->application_name;

		$this->load->view('partials/_header', $data);
		$this->load->view('owner/detail');
		$this->load->view('partials/_footer');
	}

	public function booking($id)
	{
		$id = str_replace(array('{', '}'), '', $id);

		$k = $this->post_model->get_post_and_user_by_post_id($id);
		$data['k'] = $k;
		$data['id'] = $id;
		$data['title'] = "Booking-" . $k->title;
		$data['description'] = "Booking-" . $k->title . " - " . $this->settings->application_name;
		$data['keywords'] = "Booking-" . $k->title . "," . $this->settings->application_name;
		$this->load->view('partials/_header', $data);
		$this->load->view('owner/booking');
		$this->load->view('partials/_footer');
	}
	public function add_booking()
	{
		$this->form_validation->set_rules('nama', trans("nama"), 'required|xss_clean');
		$this->form_validation->set_rules('tenor', trans("tenor"), 'required|xss_clean');
		$this->form_validation->set_rules('tgl', trans("tgl"), 'required|xss_clean');
		$this->form_validation->set_rules('hp', trans("hp"), 'required|xss_clean|min_length[12]|max_length[16]');
		$this->form_validation->set_rules('alamat', trans("alamat"), 'required|xss_clean|min_length[15]');
		$this->form_validation->set_rules('email', trans("email"), 'required|xss_clean|max_length[200]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('form_data', $this->owner_model->booking_values());
			redirect($this->agent->referrer());
		} else {
			//if post added
			if ($this->owner_model->insert_booking()) {
				reset_cache_data_on_change();
				$this->session->set_flashdata('success', "booking acara berhasil, selanjutnya tunggu konfirmasi dari management");
				redirect(lang_base_url());
			} else {
				$this->session->set_flashdata('form_data', $this->owner_model->booking_values());
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
 	}
	public function add()
	{
		$data['title'] = 'owner add';

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/owner/add');
		$this->load->view('admin/includes/_footer');
	}
 
	public function list_booking()
    {
        //check if admin
        if ($this->auth_model->is_admin() == false) {
            redirect('login');
        }

        $data['title'] ="Daftar Booking Acara";
        $data['data'] = $this->owner_model->get_list_booking();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/owner/list_booking', $data);
        $this->load->view('admin/includes/_footer');
    }

	public function booking_options_post()
    {
        prevent_author();

        //check if admin
        if (is_admin() == false) {
            redirect('login');
        }

        $option = $this->input->post('option', true);
        $id = $this->input->post('id', true);

        //if option ban
        if ($option == 'ban') {
            if ($this->auth_model->ban_user($id)) {
                $this->session->set_flashdata('success', trans("msg_user_banned"));
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect($this->agent->referrer());
            }
        }

        //if option remove ban
        if ($option == 'remove_ban') {
            if ($this->auth_model->remove_user_ban($id)) {
                $this->session->set_flashdata('success', trans("msg_ban_removed"));
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect($this->agent->referrer());
            }
        }
    }
	public function update_pembayaran()
    {
        //check if admin
        if ($this->auth_model->is_admin() == false) {
            redirect('login');
        }

        $id = $this->input->post('id_booking', true);
        $jml = $this->input->post('jumlah', true);
        $tenor = $this->input->post('tenor', true);
        $ub = $this->owner_model->update_pembayaran($id, $jml, $tenor);

        if ($ub) {
			$this->session->set_flashdata('success', "Pembaran Berhasil Diupdate");
            redirect($this->agent->referrer());
        } else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
        }
    }
	

}
