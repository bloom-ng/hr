<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		if ( ! $this->session->userdata('logged_in'))
		{
			redirect(base_url().'login');
		}
	}

	public function index()
	{
		$data['staff_members']=$this->Staff_model->get_all_staffs();

		$this->load->view('admin/header');
		$this->load->view('admin/list-bonus-staff', $data);
		$this->load->view('admin/footer');
	}

	public function manage($id)
	{
		$data['staff']= $this->Staff_model->select_staff_byID($id)[0];
		$data['bonuses']= $this->Bonus_model->getWhere(array('staff_id' => $id));


		$this->load->view('admin/header');
		$this->load->view('admin/manage-bonus', $data);
		$this->load->view('admin/footer');
	}

	public function add()
	{
		$this->load->view('admin/header');
		$this->load->view('admin/add-bonus', array());
		$this->load->view('admin/footer');
	}



	public function insert()
	{

		$this->form_validation->set_rules('staff_id', 'Staff ID', 'required');
		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('date', 'Date', 'required');


		$staff_id=$this->input->post('staff_id');
		$amount=$this->input->post('amount');
		$date=$this->input->post('date');
		$reason=$this->input->post('reason');
		$status=$this->input->post('status');

		if($this->form_validation->run() !== false)
		{

			$data = $this->Bonus_model->insert(array('staff_id'=>$staff_id,
					'amount'=>$amount,
					'date'=>$date,
					'status'=>$status,
					'reason' => $reason
				)
			);

			if($data)
			{
				$this->session->set_flashdata('success', "Bonus Added Succesfully");
			}else{
				$this->session->set_flashdata('error', "Something went wrong.");
			}
			redirect($_SERVER['HTTP_REFERER']);
		}
		else{
			$this->manage($staff_id);
			return false;
		}
	}

	public function update($id)
	{
		$this->load->helper('form');

		$this->form_validation->set_rules('staff_id', 'Staff ID', 'required');
		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('date', 'Date', 'required');


		$staff_id=$this->input->post('staff_id');
		$amount=$this->input->post('amount');
		$date=$this->input->post('date');
		$reason=$this->input->post('reason');
		$status=$this->input->post('status');

		if($this->form_validation->run() !== false)
		{

			$this->Bonus_model->update(array('staff_id'=>$staff_id,
				'amount'=>$amount,
				'date'=>$date,
				'status'=>$status,
				'reason'=>$reason
			), $id
			);


			if($this->db->affected_rows() > 0)
			{
				$this->session->set_flashdata('success', "Bonus Updated Succesfully");
			}else{
				$this->session->set_flashdata('error', "Sorry, Bonus Updated Failed.");
			}
			redirect(base_url(). "bonus");
		}
		else{
			$this->index();
			return false;
		}
	}


	public function edit($id)
	{
		$data['bonus']= $this->Bonus_model
			->get($id)[0];

		$this->load->view('admin/header');
		$this->load->view('admin/edit-bonus', $data);
		$this->load->view('admin/footer');
	}


	public function delete($id)
	{
		$this->Bonus_model->delete($id);
		if($this->db->affected_rows() > 0)
		{
			$this->session->set_flashdata('success', "Bonus Deleted Succesfully");
		}else{
			$this->session->set_flashdata('error', "Sorry, Bonus Delete Failed.");
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function paid_bonus($id)
	{
		$this->Bonus_model->update(array('status' => Bonus_model::BONUS_PAID,
			), $id
		);
		if($this->db->affected_rows() > 0)
		{
			$this->session->set_flashdata('success', "Bonus Updated Succesfully");
		}else{
			$this->session->set_flashdata('error', "Sorry, Bonus Updated Failed.");
		}
		redirect(base_url(). "bonus");
	}

}
