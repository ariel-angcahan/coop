<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoanApproval extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_loanApproval');
        date_default_timezone_set("Asia/Manila");

        if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
    }

    public function index() {
        $data['generated_token'] = $this->security->get_csrf_hash();
        $data['borrower_list'] = $this->borrower_list();
        $this->load->view('standard_layout.php', $data);
    }

    public function borrower_list(){
        $get_borrower_list_result = $this->Mdl_loanApproval->get_borrower_list();

        if($get_borrower_list_result['success']){

            $html = '<option value="">-- Select Borrower --</option>';

            foreach ($get_borrower_list_result['data'] as $get_borrower_list_key => $get_borrower_list_value) {

                $html .= '<option value="'.encrypt($get_borrower_list_value->id).'">'.strtoupper($get_borrower_list_value->borrower_name).' | '.number_format($get_borrower_list_value->loan_amount, 2).'</option>';

                if(count($get_borrower_list_result['data']) === ++$get_borrower_list_key){
                    return $html;
                }
            }
        }
    }

    public function get_borrower_info(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $lbid = post('lbid', true);

        $get_borrower_info_result = $this->Mdl_loanApproval->get_borrower_info($lbid);

        if($get_borrower_info_result['success']){
            $data = $get_borrower_info_result['data'];
            $response['loan_amount'] = number_format($data->loan_amount, 2);
            $response['frequency_of_payment'] = getLoanFrequencyOfPaymentById($data->frequency_of_payment);
            $response['loan_term'] = $data->loan_term_in_months;
            $response['no_of_payment_period'] = $data->no_of_payment_period;
            $response['loan_date'] = $data->loan_date;
            $response['maturity_date'] = $data->maturity_date;
            $response['first_payment_date'] = $data->first_payment_date;
            $response['net_proceed'] = number_format($data->net_proceed, 2);
            $response['deductions'] = $this->get_borrower_deductions($data->id);
            $response['tax_amount'] = $data->tax_amount;
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = "No borrower's info found!";
        }

        echo json_encode($response);
    }

    public function get_borrower_deductions($lbid){
        $result = $this->Mdl_loanApproval->get_borrower_deductions($lbid);
        $data = array();
        $response['deduction_flag'] = false;
        if($result['success']){
            foreach ($result['data'] as $key => $value) {
                $subdata['deduction'] = ucwords(getDeductionDescById($value->ldid));
                $subdata['amortized_flag'] = ($value->amortized_flag ? 1 : 0);
                $subdata['deduct_net_proceed_flag'] = ($value->deduct_net_proceed_flag ? 1 : 0);
                $subdata['rate'] = number_format($value->rate, 2);
                $subdata['amount'] = number_format($value->amount, 2);
                array_push($data, $subdata);
            }
            $response['data'] = $data;
            $response['deduction_flag'] = true;
        }
        return $response;
    }

    public function approve(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $lbid = post('lbid', true);

        $update_result = $this->Mdl_loanApproval->approve($lbid);
        if($update_result['success']){
            $response['success'] = true;
            $response['msg'] = "Approval success!";
        }else{
            $response['success'] = false;
            $response['msg'] = "Cannot approve this borrower!";
        }

        echo json_encode($response);
    }
}