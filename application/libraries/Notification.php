<?php if( ! defined('BASEPATH') ) exit('NO direct script access allowed');


class Notification{

	public function __construct()
	{
		$this->ayieee =& get_instance();
	}

    public function save_notification($department_from, $department_to_id, $trans_type){

        $flag['success'] = false;
        $status = '';

        if($trans_type == 3) {
            $status = 'Receiving cards!';
            $url = 'Receiving';
        } else if ($trans_type == 4) {
            $status = 'Store-in cards!';
            $url = 'Store-in';
        }

        if ($status) {
            $this->ayieee->db->set('header_title', $status);
            $this->ayieee->db->set('notification_from', $department_from);
            $this->ayieee->db->set('notification_to', $department_to_id);
            $this->ayieee->db->set('url', $url);
            $insert = $this->ayieee->db->insert('notification_master');

            if($insert) {
                $flag['success'] = true;
            }
        }

        return $flag;
    
    }

}