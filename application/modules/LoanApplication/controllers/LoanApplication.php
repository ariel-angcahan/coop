<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoanApplication extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_loanApplication');
        date_default_timezone_set("Asia/Manila");

        if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
    }

    public function index() {
        $data['generated_token'] = $this->security->get_csrf_hash();
        $data['borrower_list'] = $this->borrower_list();
        $data['frequency_payment'] = $this->loan_frequency_of_payment_list();
        $data['deduction_list'] = $this->loan_deduction_list();
        $data['deduction_tax_rate'] = loan_grt_rate();
        $this->load->view('standard_layout.php', $data);
    }

    public function borrower_list(){
        $get_borrower_list_result = $this->Mdl_loanApplication->get_borrower_list();

        if($get_borrower_list_result['success']){
            $html = '<option value="">-- Select Borrower --</option>';
            foreach ($get_borrower_list_result['data'] as $get_borrower_list_key => $get_borrower_list_value) {
                $html .= '<option value="'.encrypt($get_borrower_list_value->id).'">'.strtoupper($get_borrower_list_value->borrower_name).'</option>';

                if(count($get_borrower_list_result['data']) === ++$get_borrower_list_key){
                    return $html;
                }
            }
        }
    }

    public function loan_frequency_of_payment_list(){
        $loan_frequency_of_payment_list_result = $this->Mdl_loanApplication->loan_frequency_of_payment_list();
        if($loan_frequency_of_payment_list_result['success']){
            $html = "";
            foreach ($loan_frequency_of_payment_list_result['data'] as $loan_frequency_of_payment_list_key => $loan_frequency_of_payment_list_value) {
                $disabled = '';
                if(!$loan_frequency_of_payment_list_value->isActive){
                    $disabled = "disabled";
                }
                $html .= '<option '.$disabled.' value="'.encrypt($loan_frequency_of_payment_list_value->id).'" data-days="'.$loan_frequency_of_payment_list_value->days.'">'.strtoupper($loan_frequency_of_payment_list_value->frequency_payment_desc).'</option>';

                if(count($loan_frequency_of_payment_list_result['data']) === ++$loan_frequency_of_payment_list_key){
                    return $html;
                }
            }
        }
    }

    public function loan_deduction_list(){
        $result = $this->Mdl_loanApplication->loan_deduction_list();
        if($result['success']){
            $html;
            foreach ($result['data'] as $key => $row) {
                $rate_contenteditable = "";
                $amount_contenteditable = "";
                $amortize_editable = "";
                $net_proceed_editable = "";
                $amortized_flag = "";
                $net_proceed_flag = "";

                if($row->rate_editable){
                    $rate_contenteditable = "contenteditable";
                }
                if($row->amount_editable){
                    $amount_contenteditable = "contenteditable";
                }
                if(!$row->amortize_editable){
                    $amortize_editable = 'onclick="return false;"';
                }
                if(!$row->net_proceed_editable){
                    $net_proceed_editable = 'onclick="return false;"';
                }

                if($row->amortized_flag){
                    $amortized_flag = "checked";
                }
                if($row->net_proceed_flag){
                    $net_proceed_flag = "checked";
                }

                $html .= '<tr class="deduction-table-row" id="'.encrypt($row->id).'">
                            <td>'.(++$key).'</td>
                            <td>'.strtoupper($row->deduction_desc).'</td>
                            <td>
                                <input class="on-change-amortized" type="checkbox" id="chk-box-amortized'.$row->id.'" '.$amortized_flag.' '.$amortize_editable.'/>
                                <label for="chk-box-amortized'.$row->id.'"></label>
                            </td>
                            <td>
                                <input class="on-change-net-proceed chck-box-change" type="checkbox" id="chk-box-deduct-net-proceed'.$row->id.'" '.$net_proceed_flag.' '.$net_proceed_editable.'/>
                                <label for="chk-box-deduct-net-proceed'.$row->id.'"></label>
                            </td>
                            <td onfocusout="format_table_amount();" '.$rate_contenteditable.'>'.number_format($row->default_rate, 2).'</td>
                            <td onfocusout="format_table_amount();" '.$amount_contenteditable.'>'.number_format($row->default_amount, 2).'</td>
                            <td>
                                <input class="include" type="checkbox" id="chk-box-include'.$row->id.'" checked/>
                                <label for="chk-box-include'.$row->id.'"></label>
                            </td>
                        </tr>';

                if(count($result['data']) === $key){
                    return $html;
                }
            }
        }
    }

    public function create_loan(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $borrower_id = post('borrower-id', true);
        $loan_amount = post('loan-amount');
        $frequency_of_payment = post('frequency-of-payment', true);
        $loan_term = post('loan-term');
        $no_of_payment_period = post('no-of-payment-period');
        $loan_date = post('loan-date');
        $maturity_date = post('maturity-date');
        $first_payment_date = post('first-payment-date');
        $net_proceed = post('net-proceeds');
        $tax_amount = post('tax-amount');
        $monthly_interest = post('monthly-interest');
        $arrErr = array();
        $arrCount = 0;

        if(empty($borrower_id)){
            array_push($arrErr, "Borrower");
            $arrCount++;
        }
        if(empty($loan_amount)){
            array_push($arrErr, "Loan Amount");
            $arrCount++;
        }
        if(empty($frequency_of_payment)){
            array_push($arrErr, "Frequency of Payment");
            $arrCount++;
        }
        if(empty($loan_term)){
            array_push($arrErr, "Loan Term");
            $arrCount++;
        }
        if(empty($no_of_payment_period)){
            array_push($arrErr, "No. of Payment Period");
            $arrCount++;
        }
        if(empty($loan_date)){
            array_push($arrErr, "Loan Date");
            $arrCount++;
        }
        if(empty($maturity_date)){
            array_push($arrErr, "Maturity Date");
            $arrCount++;
        }
        if(empty($first_payment_date)){
            array_push($arrErr, "First Payment Date");
            $arrCount++;
        }
        if(empty($net_proceed)){
            array_push($arrErr, "Net Proceed");
            $arrCount++;
        }
        // if(empty($tax_amount)){
        //     array_push($arrErr, "tax Amount");
        //     $arrCount++;
        // }
        if(empty($monthly_interest)){
            array_push($arrErr, "monthly Interest");
            $arrCount++;
        }

        if($arrCount !== 0){
            $response['msg'] = implode(", ", $arrErr)." is Empty!";
            $response['success'] = false;
            echo json_encode($response);
            exit();
        }

        $loan_date = date_create($loan_date);
        $maturity_date = date_create($maturity_date);
        $first_payment_date = date_create($first_payment_date);

        $form_data = array(
            "person_info_id"        => $borrower_id,
            "loan_amount"           => $loan_amount,
            "frequency_of_payment"  => $frequency_of_payment,
            "loan_term_in_months"   => $loan_term,
            "no_of_payment_period"  => $no_of_payment_period,
            "loan_date"             => date_format($loan_date, "Y-m-d"),
            "maturity_date"         => date_format($maturity_date, "Y-m-d"),
            "first_payment_date"    => date_format($first_payment_date, "Y-m-d"),
            "net_proceed"           => str_replace(",", null, $net_proceed),
            "tax_amount"            => str_replace(",", null, $tax_amount),
            "monthly_interest"      => str_replace(",", null, $monthly_interest),
            "grt_rate"              => loan_grt_rate()
        );

        $response['success'] = false; //default
        $response['msg'] = "Error while creating loan!"; //default

        $this->db->trans_start();
        $create_loan_result = $this->Mdl_loanApplication->create_loan($form_data);
        if($create_loan_result['success']){
            $deduction = $this->input->post("deductions");
            if(!empty($deduction)){
                foreach ($deduction as $key => $value) {
                    $deduction_data = array(
                        "lbid"                      => $create_loan_result['id'],
                        "ldid"                      => decrypt($value['id']),
                        "amortized_flag"            => ($value['amortized_flag'] ? 1 : 0),
                        "deduct_net_proceed_flag"   => ($value['deduct_net_proceed_flag'] ? 1 : 0),
                        "rate"                      => str_replace(",", null, $value['rate']),
                        "amount"                    => str_replace(",", null, $value['amount'])
                    );
                    $create_deduction_result = $this->Mdl_loanApplication->create_deduction($deduction_data);
                    if($create_deduction_result["success"]){
                        if(count($deduction) === ++$key){
                            $this->db->trans_complete();
                            $response['success'] = true;
                            $response['msg'] = "Loan successfully created!";
                        }
                    }else{
                        $response['success'] = false;
                        $response['msg'] = "Error while creating loan!";
                        break;
                    }
                }
            }else{
                $this->db->trans_complete();
                $response['success'] = true;
                $response['msg'] = "Loan successfully created!";
            }
        }else{
            $response['success'] = false;
            $response['msg'] = "Error while creating loan!";
        }

        echo json_encode($response);
    }

    public function frequency_days(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $fop_id = post("fop_id", true);
        $response['days'] = getLoanFrequencyOfPaymentDaysById($fop_id);

        echo json_encode($response);
    }

    public function other_loan_status(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $borrower_id = post("borrower-id", true);

        $result = $this->Mdl_loanApplication->other_loan_status($borrower_id);
        if($result['success']){
            $response['success'] = true;
            $response['msg'] = "This Borrower has existing CURRENT loan status! Do you want to proceed this borrower?";
        }else{
            $response['success'] = false;
        }
        $response['loanable_amount'] = $this->loanable_amount($borrower_id);
        $response['max_loanable_amount'] = base64_encode($this->loanable_amount($borrower_id));

        echo json_encode($response);
    }

    public function loanable_amount($approved_member_id){
        $arrSum = array();
        $subscription_mst = $this->Mdl_loanApplication->subscription_mst($approved_member_id);
        foreach ($subscription_mst as $subscription_mst_key => $subscription_mst_value) {
            $member_ledger_header = $this->Mdl_loanApplication->member_ledger_header($subscription_mst_value->id);
            foreach ($member_ledger_header as $member_ledger_header_key => $member_ledger_header_value) {
                $member_ledger_details = $this->Mdl_loanApplication->member_ledger_details($member_ledger_header_value->id);
                foreach ($member_ledger_details as $member_ledger_details_key => $member_ledger_details_value) {
                    array_push($arrSum, $member_ledger_details_value->paid_amount);
                }
            }

            if(count($subscription_mst) === ++$subscription_mst_key){
                return number_format(array_sum($arrSum) * (loanable_rate() / 100), 2);
            }
        }
    }
}