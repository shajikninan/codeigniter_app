<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User_group_model extends CI_Model
{
	private $table_name = 'tbl_user_group';
	private $primary_key = 'id';
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	public function get($order_by)
	{
		$limit = 25;
		$start = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['result'] = $this->db->get($this->table_name,$limit,$start)->result();
		$data['pagination'] = bootstrap_pagination($this->count_record(),$limit);
		return $data;
	}
	public function save($data)
	{
		$data['inserted_at'] = date('Y-m-d H:i:s');
		$data['inserted_by'] = $this->session->user_id;
		return $this->db->insert($this->table_name,$data);
	}
	public function find($id)
	{
		return $this->db->get_where($this->table_name,[$this->primary_key=>$id])->row_array();
	}
	public function count_record()
	{
		return $this->db->count_all($this->table_name);
	}
	public function update($id,$data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['updated_by'] = $this->session->user_id;
		$this->db->where($this->primary_key, $id);
		return $this->db->update($this->table_name,$data);
	}
	public function delete($id)
	{
		return $this->db->delete($this->table_name,[$this->primary_key=>$id]);
	}
	//insert permission to user group 
	public function insert_permission_to_user_group($user_group_id,$access_flag)
	{
		return $this->db->query("
			insert into tbl_permissions (user_group_id,method_id,has_access,inserted_at,inserted_by)
			select '{$user_group_id}',tbl_method.id,'{$access_flag}',now(),'{$this->session->user_id}' from tbl_method
		");
	}
	
}
?>