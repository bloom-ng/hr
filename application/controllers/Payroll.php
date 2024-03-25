<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in') && 
            ! (in_array($this->session->userdata('role'), ["super", "finance"])) )
        {
            redirect(base_url().'login');
        }
    }

    public function index()
    {
        $data['payrolls'] = $this->Payroll_model->getPayrollPeriods();

        $this->load->view('admin/header');
        $this->load->view('admin/list-payroll', $data);
        $this->load->view('admin/footer');
    }

    public function add()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/add-memo', []);
        $this->load->view('admin/footer');
    }



    public function generate()
    {

        $this->form_validation->set_rules('period', 'Period', 'required');
       
        
        
        $period = $this->input->post('period');
        
        if($this->form_validation->run() !== false)
        {
            $staff_members = $this->Staff_model->get_all_staffs();
            
            // Forach staff
            foreach ($staff_members as $staff) {
                # code...
                // if it exists for the month then continue
                $this->Payroll_model->deleteWhere([
                    'staff_id' => $staff['id'],
                    'period'   => $period
                ]);

                // create a payroll
                $bonus = $this->Bonus_model->userBonus($staff['id']);
				$commission = $this->Commission_model->userCommission($staff['id']);
				$unpaidFine = $this->Deduction_model->userUnpaidFines($staff['id']);

                $payrollData = [
                    'staff_id'      => $staff['id'],
                    'period'        => $period,
                    'salary'        => $staff['base_salary'] ? $staff['base_salary'] : 0,
                    'housing'       => 0,
                    'transport'     => 0,
                    'utility'       => 0,
                    'wardrobe'      => 0,
                    'medical'       => 5544,
                    'meal_subsidy'  => 0,

                    'addition_advance_salary'  => 0,
                    'addition_loans'  => 0,
                    'addition_commission'  => 0 + $commission,
                    'addition_others'  => 0 + $bonus,

                    'deduction_advance_salary'  => 0,
                    'deduction_loans'  => 0,
                    'deduction_commission'  => 0,
                    'deduction_others'  => 0 + $unpaidFine,

                    'date'              => date("Y-m-d"),
                    'remark'            => ""
                ];

                $data = $this->Payroll_model->insert($payrollData);

                $this->Log_model->log('generate-staff-payroll',
                                    $this->session->userdata('userid'),
                                    "Generated Staff Payroll for  {$staff['id']} - {$staff['staff_name']} ",
                                    $payrollData);
            }

            
            if($data)
            {
                $this->session->set_flashdata('success', "Payroll Generated Succesfully");
            }else{
                $this->session->set_flashdata('error', "Something went wrong.");
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        else{
            $this->index();
            return false;
        }
    }

    public function update()
    {
        $this->load->helper('form');

        $period = $this->input->post('period');
        $payrolls = $this->input->post('payrolls');
        $payrolls = explode(",", $payrolls);


        foreach ($payrolls as $payroll) {
            if (empty($payroll)) { continue ;}

            $salary     =   $this->input->post('salary'.'_'.$payroll);
            $housing    =   $this->input->post('housing'.'_'.$payroll);
            $transport  =   $this->input->post('transport'.'_'.$payroll);
            $utility    =   $this->input->post('utility'.'_'.$payroll);
            $wardrobe   =   $this->input->post('wardrobe'.'_'.$payroll);
            $medical    =   $this->input->post('medical'.'_'.$payroll);
            $meal_subsidy    =   $this->input->post('meal_subsidy'.'_'.$payroll);
    
            $addition_advance_salary    =   $this->input->post('addition_advance_salary'.'_'.$payroll);
            $addition_loans    =   $this->input->post('addition_loans'.'_'.$payroll);
            $addition_commission    =   $this->input->post('addition_commission'.'_'.$payroll);
            $addition_others    =   $this->input->post('addition_others'.'_'.$payroll);
    
            $deduction_advance_salary    =   $this->input->post('deduction_advance_salary'.'_'.$payroll);
            $deduction_loans    =   $this->input->post('deduction_loans'.'_'.$payroll);
            $deduction_commission    =   $this->input->post('deduction_commission'.'_'.$payroll);
            $deduction_others    =   $this->input->post('deduction_others'.'_'.$payroll);
    
            $date       =   $this->input->post('date'.'_'.$payroll);
            $remark     =   $this->input->post('remark'.'_'.$payroll);
    
    
            $payload = [
                'salary'    =>  $salary,
                'housing'   =>  $housing,
                'transport' =>  $transport,
                'utility'   =>  $utility,
                'wardrobe'  =>  $wardrobe,
                'medical'   =>  $medical,
                'meal_subsidy'   =>  $meal_subsidy,
    
                'addition_advance_salary'   =>  $addition_advance_salary,
                'addition_loans'   =>  $addition_loans,
                'addition_commission'   =>  $addition_commission,
                'addition_others'   =>  $addition_others,
    
                'deduction_advance_salary'   =>  $deduction_advance_salary,
                'deduction_loans'   =>  $deduction_loans,
                'deduction_commission'   =>  $deduction_commission,
                'deduction_others'   =>  $deduction_others,
    
                'remark'    =>  $remark,
                'date'      =>  $date,
            ];

          
    
            $this->Payroll_model->update($payload, (int)$payroll);

            $this->Log_model->log('updated-payroll',
            $this->session->userdata('userid'),
            "Updated Staff Payroll for $period ",
            $payload);
        }
       
        
        
        
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Payroll Updated Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Payroll Updated Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
       
    }


    public function manage($period)
    {
        $data['period'] = $period;
        $data['payrolls']= $this->Payroll_model
                                ->getRaw(" SELECT p.*, s.base_salary, s.account, s.bank, s.staff_name FROM `payroll` p
                                            LEFT JOIN staff_tbl s ON s.id = p.staff_id 
                                            WHERE period = '{$period}'");
        $data['salary_sum']= $this->Payroll_model
                                ->getRaw(" SELECT SUM(p.salary) as salary_sum FROM `payroll` p
                                            WHERE period = '{$period}'")[0]['salary_sum'] ?? 0;

      


        $this->load->view('admin/header');
        $this->load->view('admin/manage-payroll', $data);
        $this->load->view('admin/footer');
    }


    public function delete($period)
    {
        $this->Payroll_model->deleteWhere(['period' => $period]);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Payroll Deleted Succesfully");
        }else{
            $this->session->set_flashdata('error', "Sorry, Payroll Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

}
