<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoanReleasing extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_loanReleasing');
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
        $get_borrower_list_result = $this->Mdl_loanReleasing->get_borrower_list();

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

        $get_borrower_info_result = $this->Mdl_loanReleasing->get_borrower_info($lbid);

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
        $result = $this->Mdl_loanReleasing->get_borrower_deductions($lbid);
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

    public function release(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $lbid = post('lbid', true);

        $get_borrower_info_result = $this->Mdl_loanReleasing->get_borrower_info($lbid);

        if($get_borrower_info_result['success']){
            $data = $get_borrower_info_result['data'];
            $loan_amount = $data->loan_amount;
            $frequency_of_payment = $data->frequency_of_payment;
            $loan_term = $data->loan_term_in_months;
            $no_of_payment_period = $data->no_of_payment_period;
            $loan_date = $data->loan_date;
            $maturity_date = $data->maturity_date;
            $first_payment_date = $data->first_payment_date;
            $grt_rate = $data->grt_rate;
            $date_array = array();
            $date = new DateTime($first_payment_date);
            $orig_day = $date->format("d");

            for($i = 1; $i <= $no_of_payment_period; $i++) { 
                if($i == 1){
                    $date = $date;
                }else if($i == $no_of_payment_period){
                    $date = new DateTime($maturity_date);
                }else{
                    switch($frequency_of_payment){
                        case '1':
                            $date->modify('next sunday');
                        break;
                        case '2':
                            if($date->format('d') == 15){
                                $date->modify('last day of this month');
                            }else{
                                $date->modify('first day of next month');
                                $date->modify('+14 day');
                            }
                        break;
                        case '3':
                            if($date->format('m') == 1){
                                $isLeapYear = (bool)date('L', strtotime($date->format( 'M d, Y')));
                                if($isLeapYear){
                                    if($date->format('d') == "30" || $date->format('d') == "31"){
                                        $date->modify('last day of next month');
                                    }else{
                                        $date->modify('next month');
                                    }
                                }else{
                                    if($date->format('d') == "29" || $date->format('d') == "30" || $date->format('d') == "31"){
                                        $date->modify('last day of next month');

                                    }else{
                                        $date->modify( 'next month' );
                                    }
                                }
                            }else{
                                $date->modify('next month');
                                $date = $date->format("Y")."-".$date->format("m")."-".$orig_day;
                                $date = new DateTime($date);
                            }
                        break;
                        case '4':
                            $date->modify('+180 day');
                        break;
                        case '5':
                            $date->modify('next year');
                        break;
                    }
                }
                array_push($date_array, $date->format('Y-m-d'));
            }   

            $seq;
            $due_date;
            $installment;
            $principal;
            $deduction;
            $grt;
            $balance = $loan_amount;
            $arrAmortized = array();
            $days = getLoanFrequencyOfPaymentDaysById($frequency_of_payment);

            for ( $i = 0; $i <= count($date_array) - 1; $i++) {
                $seq = $i;
                $due_date = $date_array[$i];
                $principal = $loan_amount / $no_of_payment_period;
                $principal = round($principal, 2);
                $deduction = $loan_amount * (getAmortizeDeductionRateByLoanBorrowerId($lbid) / 100);
                if($days == 15){
                    $deduction = $deduction / 2;
                }else if($days == 30){
                    $deduction = $deduction / 1;
                }
                $deduction = round($deduction, 2);

                if($i == count($date_array) - 1){
                    $principal = $balance;
                    $principal = round($principal, 2);
                }

                $grt = $deduction * (loan_grt_rate() / 100);
                $grt = round($grt, 2);
                $installment = floatval($principal) + floatval($deduction) + floatval($grt);
                $balance -= $principal;

                if($i == count($date_array) - 1){
                    $balance = 0;
                }

                array_push($arrAmortized, array(
                    "loan_borrower_id" => $lbid,
                    "due_date" => $due_date,
                    "installment_amount" => $installment,
                    "principal_amount" => floatval($principal),
                    "interest_amount" => floatval($deduction),
                    "grt_amount" => floatval($grt),
                    "balance_amount" => floatval($balance)
                ));

                $balance = $balance;
            }

            $this->db->trans_start();
            foreach ($arrAmortized as $arrAmortized_key => $arrAmortized_value) {
                $insert_amortization_result = $this->Mdl_loanReleasing->insert_amortization($arrAmortized_value);
                if($insert_amortization_result['success']){
                    if(count($arrAmortized) === ++$arrAmortized_key){
                        $release_result = $this->Mdl_loanReleasing->release($lbid);
                        if($release_result['success']){
                            $this->db->trans_complete();
                            $response['success'] = true;
                            $response['msg'] = "Loan released successfully!";
                        }else{
                            $response['success'] = false;
                            $response['msg'] = "Error while releasing!";
                        }
                    }
                }
            }
        }else{
            $response['success'] = false;
            $response['msg'] = "Cannot find borrower details!";
        }

        echo json_encode($response);
    }
}