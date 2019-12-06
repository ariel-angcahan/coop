<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Analytics extends MX_Controller {

    public function __construct(){

        parent::__construct();
        $this->load->model('Mdl_analytics');
        date_default_timezone_set("Asia/Manila");

        if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
    }

    public function SubscriptionRatings() {
        $data['generated_token'] = $this->security->get_csrf_hash();
        $this->load->view('SubscriptionRatings', $data);
    }

    public function member_list() {

        $datas = $this->Mdl_analytics->member_list_table();

        $data = array();
        foreach ($datas as $index => $row) {
            $date_created = new DateTime($row->date_created);
            $date_approved = new DateTime($row->date_approved);

            $sub_array = array();
            $sub_array[] = ++$index;
            $sub_array[] = ucfirst($row->first_name) . " " . ucfirst($row->middle_name) . " " . ucfirst($row->last_name);
            $sub_array[] = strtoupper($row->tmc_code);
            $sub_array[] = $this->grand_total_ratings($row->id);
            $sub_array[] = $this->grand_total_amount($row->id);
            $sub_array[] = '<button type="button" class="btn dropdown-toggle bg-purple show-subscription-modal" data-id="'.encrypt($row->id).'">
                                DETAILS
                            </button>';
            $data[] = $sub_array;
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_analytics->get_all_member_data(),  
            "recordsFiltered"   => $this->Mdl_analytics->get_filtered_member_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
    }

    public function get_subscription_list(){
        $approved_member_id = post('id', true);
        $respose['generated_token'] = $this->security->get_csrf_hash();

        $result = $this->Mdl_analytics->get_subscription_list($approved_member_id);
        if($result['success']){
            $dataArr = array();
            foreach ($result['data'] as $header_key => $header_value){
                $result_detail = $this->Mdl_analytics->get_subscription_details($header_value->id);
                $arrDetail = array();
                $parentage;
                if($result_detail['success']){
                    foreach ($result_detail['data'] as $key => $value) {
                        if(!empty($value->payment_on_time)){
                            array_push($arrDetail, $value->payment_on_time);
                        }

                        if(count($result_detail['data']) === ++$key){
                            if(!empty($arrDetail)){
                                $parentage = array_sum($arrDetail) / count($arrDetail);
                            }else{
                                $parentage = '0.00%';
                            }
                        }
                    }
                
                    array_push($dataArr, array(
                        'subscription_amount' => $header_value->subscription_amount,
                        'current_subscription' => $header_value->current_subscription ? '<label class="col-green">ACTIVE</label>' : '<label class="col-red">FULLY PAID</label>',
                        'parentage' => number_format(round($parentage, 2), 2).'%',
                        'button' => '<button type="button" class="btn dropdown-toggle bg-purple show-subscription-details" data-id="'.encrypt($header_value->id).'">
                                            DETAILS
                                        </button>'
                    ));
                }

                if(count($result['data']) === ++$header_key){
                    $respose['data'] = $dataArr;
                    $respose['success'] = true;
                }
            }
        }else{
            $respose['success'] = false;
            $respose['msg'] = 'No subscription list found!';
        }

        echo json_encode($respose);
    }

    public function get_subscription_detail_list(){
        $subscription_id = post('id', true);
        $respose['generated_token'] = $this->security->get_csrf_hash();

        $result = $this->Mdl_analytics->get_subscription_detail_list($subscription_id);

        if($result['success']){
            $dataArr = array();
            foreach ($result['data'] as $key => $value) {
                $due_date = new DateTime($value->due_date);
                array_push($dataArr, array(
                    'due_date' => $due_date->format('M d, Y'),
                    'on_time_count' => $value->on_time_count,
                    'payment_on_time_rating' => number_format(round($value->payment_on_time_rating, 2), 2).'%',
                    'delay_count' => $value->delay_count,
                    'payment_delay_rating' => (number_format(round($value->payment_delay_rating, 2), 2).'%' > 0 ? number_format(round($value->payment_delay_rating, 2), 2).'%' : number_format(round($value->payment_delay_rating, 2), 2).'%')
                ));

                if(count($result['data']) === ++$key){
                    $respose['data'] = $dataArr;
                    $respose['success'] = true;
                }
            }
        }else{
            $respose['success'] = false;
            $respose['msg'] = 'No subscription details found!';
        }

        echo json_encode($respose);
    }

    public function grand_total_ratings($approved_member_id){
        $result = $this->Mdl_analytics->get_subscription_list($approved_member_id);
        if($result['success']){
            $dataArr = array();
            foreach ($result['data'] as $header_key => $header_value){
                $result_detail = $this->Mdl_analytics->get_subscription_details($header_value->id);
                $arrDetail = array();
                $parentage;
                if($result_detail['success']){
                    foreach ($result_detail['data'] as $key => $value) {
                        if(!empty($value->payment_on_time)){
                            array_push($arrDetail, $value->payment_on_time);
                        }

                        if(count($result_detail['data']) === ++$key){
                            if(!empty($arrDetail)){
                                $parentage = array_sum($arrDetail) / count($arrDetail);
                            }else{
                                $parentage = '0.00%';
                            }
                        }
                    }
                    array_push($dataArr, $parentage);
                }

                if(count($result['data']) === ++$header_key){
                    return number_format(round((array_sum($dataArr) / count($dataArr)), 2), 2).'%';
                }
            }
        }else{
            return '0.00%';
        }
    }

    public function grand_total_amount($approved_member_id){
        $result = $this->Mdl_analytics->grand_total_amount($approved_member_id);
        return number_format($result, 2).' * '.(loanable_rate() / 100).' = <label>'.number_format(($result * (loanable_rate() / 100)), 2).'</label>';
    }

    ////live computing from ledger updated to all payments
    // public function grand_total_amount($approved_member_id){
    //     $arrSum = array();
    //     $subscription_mst = $this->Mdl_analytics->subscription_mst($approved_member_id);
    //     foreach ($subscription_mst as $subscription_mst_key => $subscription_mst_value) {
    //         $member_ledger_header = $this->Mdl_analytics->member_ledger_header($subscription_mst_value->id);
    //         foreach ($member_ledger_header as $member_ledger_header_key => $member_ledger_header_value) {
    //             $member_ledger_details = $this->Mdl_analytics->member_ledger_details($member_ledger_header_value->id);
    //             foreach ($member_ledger_details as $member_ledger_details_key => $member_ledger_details_value) {
    //                 array_push($arrSum, $member_ledger_details_value->paid_amount);
    //             }
    //         }

    //         if(count($subscription_mst) === ++$subscription_mst_key){
    //             return number_format(array_sum($arrSum), 2).' * '.(loanable_rate() / 100).' = <label>'.number_format((array_sum($arrSum) * (loanable_rate() / 100)), 2).'</label>';
    //         }
    //     }
    // }
}