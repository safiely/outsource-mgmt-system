<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mr extends MY_Controller
{
    function __construct() {
        parent::__construct("authorize", "mr", true);
        $this->load->model('mr_model');
          
    }
    public function get_mr_list()
    {
        echo "{\"data\" : " . json_encode($this->mr_model->get_mr_all()) . "}";
    }
    
    public function get_mr_list_open()
    {
        echo "{\"data\" : " . json_encode($this->mr_model->get_mr_open()) . "}";
    }
    
     public function save_mr()
    {
        if($this->input->post('is_edit') == 'true')
        {
            $this->mr_model->edit_mr($this->input->post());
        }
        else
        {
            $this->mr_model->add_mr($this->input->post());
        }
        
        return null;
    }
     public function validate_mr($id)
    {
        $param = null;
        $param = $this->mr_model->validate_mr($id);
        $interfunction_param = array();
        $interfunction_param[0] = array("paramKey" => "id", "paramValue" => $id);
        return array('log_param' => $param, "interfunction_param" => $interfunction_param);
    }
    public function delete_mr()
    {
        $this->mr_model->delete_mr($this->input->post(  'id_mr'));
        
        return null;
    }
    
    public function init_edit_mr($id)
    {
        $data = array(
            'data_edit' => $this->mr_model->get_mr_by_id($id),
            'is_edit' => 'true'
        );
        
        return $data;
    }
    
    public function get_mr_product_list()
    {
         echo "{\"data\" : " . json_encode($this->mr_model->get_mr_product_by_id_mr($this->input->get('id'))) . "}";
    }
    
    public function get_mr_product_list_open()
    {
		$this->load->model('stock_model');
		$product_mr = $this->mr_model->get_mr_product_open_by_id_mr($this->input->get('id'));
		$stock_available = $this->stock_model->get_stock_all();
		$result = array();
		
		for($i=0;$i<count($product_mr);$i++)
		{
			$found = false;
			for($j=0;$j<count($stock_available);$j++)
			{
				if($product_mr[$i]['id_product'] == $stock_available[$j]['id_product'])
				{
					if($product_mr[$i]['qty'] > $stock_available[$j]['total_qty'])
					{
						$found = true;
						$product_mr[$i]['qty'] = $product_mr[$i]['qty'] - $stock_available[$j]['total_qty'];
						array_push($result, $product_mr[$i]);
					}
				}
			}
			
			if($found == false)
			{
				array_push($result, $product_mr[$i]);
			}
		}
         echo "{\"data\" : " . json_encode($result) . "}";
    }
}
?>
