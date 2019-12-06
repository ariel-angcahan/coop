<?php if( ! defined('BASEPATH') ) exit('NO direct script access allowed');


class coop{

	public function __construct(){
		// parent::__construct();
		$this->ci =& get_instance();
	}

	public function currentSubscriptionBalance($tmc_code){
        $response['success'] = false;
        $this->ci->db->select('sm.subscription_amount, (select SUM(paid_amount) from member_ledger_details where member_ledger_header_id IN (select id from member_ledger_header where subscription_id = sm.id)) as total_amount_paid');
        $this->ci->db->from('member_subscription_mst as sm');
        $this->ci->db->join('approved_member as am', 'sm.approved_member_id = am.id');
        $this->ci->db->where('sm.current_subscription', 1);
        $this->ci->db->where('am.tmc_code', $tmc_code);
        $data = $this->ci->db->get()->result();

        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getTmcCodeBySubscriotnId($subscription_id){
        $response['success'] = false;
        $this->ci->db->select('tmc_code');
        $this->ci->db->from('member_subscription_mst as sm');
        $this->ci->db->join('approved_member as am', 'sm.approved_member_id = am.id');
        $this->ci->db->where('sm.id', $subscription_id);
        $data = $this->ci->db->get()->result();
        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getCurrentLedgerHeaderToPaid($tmc_code){
        $response['success'] = false;
        $this->ci->db->select('mlh.id, mlh.amount_to_paid, (select SUM(paid_amount) from member_ledger_details where member_ledger_header_id = mlh.id) as total_paid_amount');
        $this->ci->db->from('approved_member as am');
        $this->ci->db->join('member_subscription_mst as sm', 'am.id = sm.approved_member_id');
        $this->ci->db->join('member_ledger_header as mlh', 'sm.id = mlh.subscription_id');
        $this->ci->db->join('person_info as pi', 'am.person_info_id = pi.id');
        $this->ci->db->where('sm.approved_flag', 1);
        $this->ci->db->where('sm.current_subscription', 1);
        $this->ci->db->where('am.tmc_code', $tmc_code);
        $data = $this->ci->db->get()->result();

        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data;
        }

        return $response;
	}

	public function geSubscriptionIdByTmcCode($tmc_code){
        $response['success'] = false;
		$this->ci->db->select('sm.id');
		$this->ci->db->from('approved_member as am');
		$this->ci->db->join('member_subscription_mst as sm', 'am.id = sm.approved_member_id');
		$this->ci->db->where('am.tmc_code', $tmc_code);
		$this->ci->db->where('sm.approved_flag', 1);
		$this->ci->db->where('sm.current_subscription', 1);
        $data = $this->ci->db->get()->result();

        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function frequencyOfPayment(){
		$this->ci->db->select('*');
		$this->ci->db->from('payment_mode_mst');
		return $this->ci->db->get()->result();
	}

	public function geApprovedMemberIdByTmcCode($tmc_code){
        $response['success'] = false;
		$this->ci->db->select('id');
		$this->ci->db->from('approved_member');
		$this->ci->db->where('tmc_code', $tmc_code);
		$data = $this->ci->db->get()->result();
        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getBalanceBySubscriptionId($id){
		$this->ci->db->select('(sm.subscription_amount - (select SUM(paid_amount) from member_ledger_details where member_ledger_header_id IN (select id from member_ledger_header where subscription_id = sm.id))) as balance');
		$this->ci->db->from('member_subscription_mst as sm');
		$this->ci->db->join('approved_member as am', 'sm.approved_member_id = am.id');
		$this->ci->db->where('sm.id', $id);
		return $this->ci->db->get()->result();
	}

	public function getMemberNameByTmcCode($tmc_code){
        $response['success'] = false;
		$this->ci->db->select("CONCAT(pi.first_name,' ',pi.middle_name,' ',pi.last_name) as name");
		$this->ci->db->from('approved_member as am');
		$this->ci->db->join('person_info as pi', 'am.person_info_id = pi.id');
		$this->ci->db->where('am.tmc_code', $tmc_code);
		$data = $this->ci->db->get()->result();
        if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getTotalPaidByTmcCode($tmc_code){
		$response['success'] = false;
		$id = geSubscriptionIdByTmcCode($tmc_code);
		$this->ci->db->select('total_paid');
		$this->ci->db->from('member_subscription_mst');
		$this->ci->db->where('id', $id);
		$data = $this->ci->db->get()->result();

		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getLoanFrequencyOfPaymentById($id){
		$response['success'] = false;
		$this->ci->db->select('frequency_payment_desc');
		$this->ci->db->from('loan_frequency_of_payment_mst');
		$this->ci->db->where('id', $id);
		$data = $this->ci->db->get()->result();

		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getLoanFrequencyOfPaymentDaysById($id){
		$response['success'] = false;
		$this->ci->db->select('days');
		$this->ci->db->from('loan_frequency_of_payment_mst');
		$this->ci->db->where('id', $id);
		$data = $this->ci->db->get()->result();

		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getDeductionDescById($id){
		$response['success'] = false;
		$this->ci->db->select('deduction_desc');
		$this->ci->db->from('loan_deduction_mst');
		$this->ci->db->where('id', $id);
		$data = $this->ci->db->get()->result();

		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getLoanMonthlyDeductionById($id){
		$response['success'] = false;
		$this->ci->db->select('monthly_deduction');
		$this->ci->db->from('loan_deduction_mst');
		$this->ci->db->where('id', $id);
		$data = $this->ci->db->get()->result();

		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getPersonFullNameById($id){
		$response['success'] = false;
		$this->ci->db->select("CONCAT(first_name, ' ', middle_name, ' ', last_name) as name");
		$this->ci->db->from('person_info');
		$this->ci->db->where('id', $id);
		$data = $this->ci->db->get()->result();
		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getAmortizeDeductionRateByLoanBorrowerId($lbid){
		$response['success'] = false;
		$this->ci->db->select("SUM(rate) as rate");
		$this->ci->db->from('loan_borrower_deductions');
		$this->ci->db->where('lbid', $lbid);
		$this->ci->db->where('amortized_flag', 1);
		$this->ci->db->where('deduct_net_proceed_flag', 0);
		$data = $this->ci->db->get()->result();
		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getTotalPenaltyPerDueDateByLoanBorrowerDetailsId($llhid){
		$response['success'] = false;
		$this->ci->db->select("sum(lpp.amount) AS total_penalty");
		$this->ci->db->from('loan_borrower_details AS lbd');
		$this->ci->db->join('loan_payment_penalty AS lpp', 'lbd.id = lpp.loan_ledger_header_id');
		$this->ci->db->where('lbd.id', $llhid);
		$data = $this->ci->db->get()->result();
		
		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getPersonInfoIdByLoanBorrowerId($lbid){
		$response['success'] = false;
		$this->ci->db->select("person_info_id");
		$this->ci->db->from('loan_borrower_header');
		$this->ci->db->where('id', $lbid);
		$data = $this->ci->db->get()->result();
		
		if(!empty($data)){
            $response['success'] = true;
            $response['data'] = $data[0];
        }

        return $response;
	}

	public function getSubscriptionMasterIdByTmbCode($tmc){
		$this->ci->db->select('msm.id');
		$this->ci->db->from('member_subscription_mst as msm');
		$this->ci->db->join('approved_member as am', 'msm.approved_member_id = am.id');
		$this->ci->db->where('am.tmc_code', $tmc);
		return $this->ci->db->get()->result();
	}
}

function getSubscriptionMasterIdByTmbCode($tmc){
	$coop = new coop();
	return $coop->getSubscriptionMasterIdByTmbCode($tmc);
}

function getPersonInfoIdByLoanBorrowerId($lbid){
	$coop = new coop();
	$result = $coop->getPersonInfoIdByLoanBorrowerId($lbid);
	if($result['success']){
		return ucwords($result['data']->person_info_id);
	}
}

function getTotalPenaltyPerDueDateByLoanBorrowerDetailsId($llhid){
	$coop = new coop();
	$result = $coop->getTotalPenaltyPerDueDateByLoanBorrowerDetailsId($llhid);
	if($result['success']){
		return ucwords($result['data']->total_penalty);
	}
}

function getAmortizeDeductionRateByLoanBorrowerId($lbid){
	$coop = new coop();
	$result = $coop->getAmortizeDeductionRateByLoanBorrowerId($lbid);
	if($result['success']){
		return ucwords($result['data']->rate);
	}
}

function getPersonFullNameById($id){
	$coop = new coop();
	$result = $coop->getPersonFullNameById($id);
	if($result['success']){
		return ucwords($result['data']->name);
	}
}

function getLoanMonthlyDeductionById($id){
	$coop = new coop();
	$result = $coop->getLoanMonthlyDeductionById($id);
	if($result['success']){
		return $result['data']->monthly_deduction;
	}
}

function getDeductionDescById($id){
	$coop = new coop();
	$result = $coop->getDeductionDescById($id);
	if($result['success']){
		return $result['data']->deduction_desc;
	}
}

function getLoanFrequencyOfPaymentDaysById($id){
	$coop = new coop();
	$result = $coop->getLoanFrequencyOfPaymentDaysById($id);
	if($result['success']){
		return $result['data']->days;
	}else{
		return 0;
	}
}

function getLoanFrequencyOfPaymentById($id){
	$coop = new coop();
	$result = $coop->getLoanFrequencyOfPaymentById($id);
	if($result['success']){
		return $result['data']->frequency_payment_desc;
	}else{
		return 0;
	}
}

function getTotalPaidByTmcCode($tmc_code){
	$coop = new coop();
	$result = $coop->getTotalPaidByTmcCode($tmc_code);
	if($result['success']){
		return $result['data']->total_paid;
	}else{
		return 0;
	}
}

function getMemberNameByTmcCode($tmc_code){
	$coop = new coop();
	$result = $coop->getMemberNameByTmcCode($tmc_code);
	if($result['success']){
		return $result['data']->name;
	}else{
		return 'No Member name!';
	}
}

function getBalanceBySubscriptionId($id){
	$coop = new coop();
	$balance = $coop->getBalanceBySubscriptionId($id);
	if(!empty($balance)){
		return $balance[0]->balance;
	}else{
		return 'No data found!';
	}
}

function geApprovedMemberIdByTmcCode($tmc_code){
	$coop = new coop();
	$result = $coop->geApprovedMemberIdByTmcCode($tmc_code);
	if($result['success']){
		return $result['data']->id;
	}else{
		return null;
	}
}

function frequencyOfPayment(){
	$coop = new coop();
	$result = $coop->frequencyOfPayment();

	$html = '<option value>SELECT FREQUENCY OF PAYMENT</option>';
	foreach ($result as $key => $value) {
		$html .= "<option value='".$value->id."'>".$value->mode."</option>";
	}

	echo $html;
}

function getCurrentLedgerHeaderToPaid($tmc_code){
	$coop = new coop();
	$result = $coop->getCurrentLedgerHeaderToPaid($tmc_code);
	$response['success'] = false;
	if($result['success']){
		$arr = array();
		foreach ($result['data'] as $key => $value) {
			if(($value->amount_to_paid - $value->total_paid_amount) > 0){
				array_push($arr, array('ledger_header_id' => $value->id, 'balance' => ($value->amount_to_paid - $value->total_paid_amount)));
			}

			if(count($result['data']) === ++$key){
				$response['success'] = true;
				$response['data'] = $arr[0];
			}
		}
	}

	return $response;
}

function currentSubscriptionBalance($tmc_code){
	$coop = new coop();
	$result = $coop->currentSubscriptionBalance($tmc_code);
	if($result['success']){
		return ($result['data']->subscription_amount - $result['data']->total_amount_paid);
	}else{
		return 0;
	}
}

function getTmcCodeBySubscriotnId($subscription_id){
	$coop = new coop();
	$result = $coop->getTmcCodeBySubscriotnId($subscription_id);
	if($result['success']){
		return $result['data']->tmc_code;
	}

}

function geSubscriptionIdByTmcCode($tmc_code){
	$coop = new coop();
	$result = $coop->geSubscriptionIdByTmcCode($tmc_code);
	if($result['success']){
		return $result['data']->id;
	}
}

function due_date_in_a_month_count($due_date, $date_array){
	$due_date = new DateTime($due_date);
	$monthly_due_date_count = 0;
	foreach ($date_array as $date_key => $date_date) {
        $date_date = new DateTime($date_date);
        if($date_date->format('m') === $due_date->format('m') && $date_date->format('Y') === $due_date->format('Y')){
            $vMonth = $date_date->format('m');
            $vYear = $date_date->format('Y');

            if($date_date->format('m') === $vMonth && $date_date->format('Y') === $vYear){
                $monthly_due_date_count++;
            }else{
                break;
            }
        }
    }

    return $monthly_due_date_count;
}