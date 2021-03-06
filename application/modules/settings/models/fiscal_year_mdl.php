<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fiscal_year_mdl extends CI_Model {
    
    function __construct() {
        parent::__construct();
        $this->load->model('logUpdate');
    }
    
    function getrecordcount()
    {
            $data = $this->db->count_all_results('fiscal_year');
            return $data;
    }
        
    function getdatalist()
        {
            $data = array();
            $fields = array(
                'fiscal_year_id',
                "(DATE_FORMAT(fiscal_year_start, '%d-%m-%Y')) as fiscal_year_start",
                "(DATE_FORMAT(fiscal_year_end, '%d-%m-%Y')) as fiscal_year_end",
                'fiscal_year_is_active',
            );
            
            $this->db->select($fields);            
            $query = $this->db->get('fiscal_year');
            $nomor = 1;
            foreach($query->result() as $row):
                $data[] = array(
                    'nomor'                     => $nomor,
                    'fiscal_year_id'            => $row->fiscal_year_id,
                    'fiscal_year_start'         => $row->fiscal_year_start,
                    'fiscal_year_end'           => $row->fiscal_year_end,
                    'fiscal_year_is_active'     => $row->fiscal_year_is_active,
                );
                $nomor++;
            endforeach;
            return $data;
        }
        
        function getdataid($id)
        {
            $fields = array(
                'fiscal_year_id',
                "(DATE_FORMAT(fiscal_year_start, '%d-%m-%Y')) as fiscal_year_start",
                "(DATE_FORMAT(fiscal_year_end, '%d-%m-%Y')) as fiscal_year_end",
                'fiscal_year_is_active',
            );
            
            $this->db->select($fields);
            $this->db->where('fiscal_year_id', $id);
            $query = $this->db->get('fiscal_year');
            $row = $query->row();
            $status = '';
            if ($row->fiscal_year_is_active == 'TRUE'){
                $status = 'checked="checked"';
            }
            
            $data = array(
                    'fiscal_year_id'            => $row->fiscal_year_id,
                    'fiscal_year_start'         => $row->fiscal_year_start,
                    'fiscal_year_end'           => $row->fiscal_year_end,
                    'fiscal_year_is_active'     => $row->fiscal_year_is_active,
                    'status'                    => $status,
            );
            return $data;
        }
        
        
        public function save($params)
	{	
                $status = 'FALSE';
                if (!empty($params->fiscal_year_is_active)){
                    $status = 'TRUE';
                }
                
		$fields = array(
                    'fiscal_year_start'         => date('Y-m-d', strtotime($params->fiscal_year_start)),
                    'fiscal_year_end'           => date('Y-m-d', strtotime($params->fiscal_year_end)),
                    'fiscal_year_is_active'     => $status,
                );                                
		$this->db->set($fields);
                
                $btnsave = @$params->btnsave;
                if (!empty($btnsave)){
                    
                    $tahun = date('Y', strtotime($params->fiscal_year_start));
                    $this->db->set($fields);
                    $this->db->set('fiscal_year', $tahun);
                    $valid = $this->db->insert('fiscal_year');
                    $valid = $this->logUpdate->addLog("insert", "user_group", $params);
                }
                
                $btnedit = @$params->btnedit;
                if(!empty($btnedit)) {
                    
                    $id = $params->fiscal_year_id;
                    $this->db->set($fields);
                    $tahun = date('Y', strtotime($params->fiscal_year_start));
                    $this->db->set('fiscal_year', $tahun);                    
                    $this->db->where("fiscal_year_id", $id);
                    $valid = $this->db->update('fiscal_year');
                    $valid = $this->logUpdate->addLog("update", "user_group", $params);
                }
                //echo $this->db->last_query();
		return true;		
	}
        
        public function delete($id)
	{	
				
		$valid = $this->logUpdate->addLog("delete", "fiscal_year", array("fiscal_year_id" => $id));	
		
		if ($valid){
                    $this->db->where('fiscal_year_id', $id);
                    $valid = $this->db->delete('fiscal_year');
		}
		
		return $valid;		
	}
}    