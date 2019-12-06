<?php if( ! defined('BASEPATH') ) exit('NO direct script access allowed');

class Transaction {

	public function __construct() {
		$this->ci =& get_instance();
	}

	public function status($status_id) {

        if ($status_id == 1) {
            $status = 'Approved';
        } else if($status_id == 2) {
            $status = 'Cancelled';
        } else if($status_id == 3) {
            $status = 'Refused';
        } else if($status_id == 4) {
            $status = 'Waiting for approval...';
        }

        return $status;
	}

    private function approve($id, $transaction_type, $transaction_status = 1) {

        $flag['success'] = false;

        $user_id = $this->ci->session->userdata('UAId');

        $this->ci->db->where('card_transaction_header', $id);

        $update = $this->ci->db->update('temp_card_transaction', array('added_by' => $user_id , 'transaction_status' => $transaction_status, 'transaction_type' => $transaction_type));

        if ($update) {
            
            $delete = $this->ci->db->delete('temp_card_transaction', array('card_transaction_header' => $id));

            if ($delete) {
                $flag['success'] = true;
            }
        }

        return $flag;
    }

    // trans status ---> 1 APPROVED; 2 CANCELLED; 3 REFUSED; 4 PENDING
    // trans type ---> 1 PRINT; 2 RECEIVE; 3 RELEASE; 4 STORE-OUT; 5 STORE-IN; 6 SOLD; 7 REDEEM
    private function cancel($id, $transaction_type, $transaction_status = 3) {

        $flag['success'] = false;

        $user_dep_id = $this->ci->session->userdata('department_id');
        $user_id = $this->ci->session->userdata('UAId');

        $this->ci->db->where('card_transaction_header', $id);

        $update = $this->ci->db->update('temp_card_transaction', array('added_by' => $user_id , 'transaction_status' => $transaction_status, 'transaction_type' => $transaction_type));

        if ($update) {

            $query = $this->ci->db->get_where('temp_card_transaction', array('card_transaction_header' => $id));
            $sender = $query->result()[0];

            $delete = $this->ci->db->delete('temp_card_transaction', array('card_transaction_header' => $id));

            if ($delete) {

                $flag['success'] = true;

                // check if sender was the printing press
                // if so, mark the card as deleted to avoid inconsistency in db vs reality
                if ($sender->department_from_id == 1) {

                    $this->ci->db->where('id', $sender->card_transaction_header);
                    $flagged = $this->ci->db->update('card_transaction_header', array('deleted' => 1));

                    $flagged ? $flag['success'] = true : $flag['success'] = false;
                }
            }
        }

        return $flag;
    }

    public function batch_cancel($cards, $type, $status) {

        $this->ci->db->trans_start();

        foreach ($cards as $id) {
            $result = $this->cancel($id, $type, $status);
        }

        $this->ci->db->trans_complete();

        if ($this->ci->db->trans_status() === FALSE) {
            $flag['success'] = false;
        } else {
            $flag['success'] = true;
        }

        return $flag;
    }

    public function batch_approve($cards, $type, $status) {

        $this->ci->db->trans_start();

        foreach ($cards as $id) {
            $result = $this->approve($id, $type, $status);
        }

        $this->ci->db->trans_complete();

        if ($this->ci->db->trans_status() === FALSE) {
            $flag['success'] = false;
        } else {
            $flag['success'] = true;
        }

        return $flag;
    }

}