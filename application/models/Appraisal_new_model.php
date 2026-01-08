<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appraisal_new_model extends CI_Model {

    const APPRAISAL_DRAFT = 'draft';
    const APPRAISAL_PENDING = 'pending';
    const APPRAISAL_HR_APPROVED = 'hr_approved';
    const APPRAISAL_STAFF_REPLIED = 'staff_replied';
    const APPRAISAL_FINAL = 'final';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Updated filtering for roles
    public function get_appraisals_by_role($role, $staff_id = null) {
        $this->db->select('appraisals_new.*, staff_tbl.staff_name, department_tbl.department_name');
        $this->db->from('appraisals_new');
        $this->db->join('staff_tbl', 'staff_tbl.id = appraisals_new.staff_id');
        $this->db->join('department_tbl', 'department_tbl.id = appraisals_new.department_id');

        if ($role == 'staff') {
             // Staff sees only their own
             $this->db->where('appraisals_new.staff_id', $staff_id);
             // Staff should generally see them once created, but action is only when 'hr_approved'
        } 
        elseif ($role == 'finance') {
             // Finance usually just sees final or all? Assuming mostly just for views
             $this->db->where('status', self::APPRAISAL_FINAL);
        }
        elseif ($role == 'hrm') {
             // HR needs to see PENDING to approve them. Can also see others.
             // Prioritize pending? or just show all.
             // Let's show all for now, but in the UI we will highlight pending.
             // Or filters can be applied in controller.
        }
        elseif ($role == 'super') {
             // Super approves 'staff_replied'.
        }
        
        $this->db->order_by('appraisals_new.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function create_appraisal($data, $kpas) {
        $this->db->trans_start();

        $this->db->insert('appraisals_new', $data);
        $appraisal_id = $this->db->insert_id();

        if (!empty($kpas)) {
            foreach ($kpas as &$kpa) {
                $kpa['appraisal_id'] = $appraisal_id;
            }
            $this->db->insert_batch('appraisal_kpas', $kpas);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_appraisal($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('appraisals_new');
        $appraisal = $query->row_array();

        if ($appraisal) {
            $this->db->where('appraisal_id', $id);
            $kpa_query = $this->db->get('appraisal_kpas');
            $appraisal['kpas'] = $kpa_query->result_array();
        }

        return $appraisal;
    }

    public function list_appraisals($staff_id = null) {
        if ($staff_id) {
            $this->db->where('staff_id', $staff_id);
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('appraisals_new')->result_array();
    }
    
    public function get_all_appraisals() {
         $this->db->order_by('id', 'DESC');
         return $this->db->get('appraisals_new')->result_array();
    }

    public function update_appraisal($id, $data, $kpas = null) {
        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update('appraisals_new', $data);

        if ($kpas !== null) {
             // Replace existing KPAs
             $this->db->where('appraisal_id', $id);
             $this->db->delete('appraisal_kpas');
             
             if(!empty($kpas)) {
                 foreach ($kpas as &$kpa) {
                    $kpa['appraisal_id'] = $id;
                }
                $this->db->insert_batch('appraisal_kpas', $kpas);
             }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    // Helper to get KPAs only
    public function get_kpas($appraisal_id) {
        $this->db->where('appraisal_id', $appraisal_id);
        return $this->db->get('appraisal_kpas')->result_array();
    }

    public function getWhere($where)
	{
		foreach ($where as $key => $value) {
			if (is_int($key)) {
				$this->db->where($value);
				continue;
			}
			$this->db->where($key, $value);
		}

		$this->db->order_by('id', 'DESC');
		$qry = $this->db->get('appraisals_new');
		if($qry->num_rows()>0)
		{
			return $qry->result_array();
		}
	}

}
