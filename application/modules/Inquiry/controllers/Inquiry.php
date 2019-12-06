<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Inquiry extends MX_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Mdl_inquiry');
        date_default_timezone_set("Asia/Manila");
        
        if ($this->session->isLogin) {
            header('location:'.base_url('Dashboards'));
        }
    }

    public function index(){
        $data['token'] = $this->security->get_csrf_hash();
        $this->load->view('standard_layout.php', $data);
    }

    public function get_subscription_list(){
        $approved_member_id = geApprovedMemberIdByTmcCode($this->input->post('tmc'));
        $datas = $this->Mdl_inquiry->subscription_list_table($approved_member_id);

        $data = array();
        foreach ($datas as $index => $row) {

            $date_created = new DateTime($row->date_created);
            $date_approved = new DateTime($row->date_approved);
            $balance = getBalanceBySubscriptionId($row->id);
            $status;
            if($balance == 0 && $balance != null){
                $status = '<label class="col-red">FULLY PAID</label>';
            }else{
                $status = '<label class="col-green">ACTIVE</label>';
            }
            $sub_array = array();
            $sub_array['Lno'] = ++$index;
            $sub_array['subscription_amount'] = number_format($row->subscription_amount, 2);
            $sub_array['mode'] = strtoupper($row->mode);
            $sub_array['date_created'] = $date_created->format('D')." | ".$date_created->format('M d, Y');
            $sub_array['date_approved'] = $date_approved->format('D')." | ".$date_approved->format('M d, Y');
            $sub_array['status'] = $status;
            $sub_array['button'] = '<div class="btn-group">
                                        <button type="button" class="btn dropdown-toggle bg-purple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            ACTIONS <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:void(0);" class="btn-preview-break-down" data-id="'.encrypt($row->id).'">Schedule & Balance</a></li>
                                            <li><a href="javascript:void(0);" class="btn-preview-ledger" data-id="'.encrypt($row->id).'">Ledger</a></li>
                                            <li><a href="javascript:void(0);" class="btn-preview-transaction" data-id="'.encrypt($row->id).'">Transaction History</a></li>
                                        </ul>
                                    </div>';
            $data[] = $sub_array;
        }

        $response['token'] = $this->security->get_csrf_hash();
        $response['name'] = ucwords(getMemberNameByTmcCode($this->input->post('tmc')));
        $response['tmc'] = $this->input->post('tmc');
        $response['data'] = $data;

        echo json_encode($response);
    }


    public function get_ledger_details(){

        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = post('id', true);
        $datas = $this->Mdl_inquiry->get_ledger_header($id);

        if($datas['success']){
            $start_date = new DateTime($datas['data']->start_date);
            $due_date = new DateTime($datas['data']->due_date);

            $response['name'] = ucfirst($datas['data']->first_name) . " " . ucfirst($datas['data']->middle_name) . " " . ucfirst($datas['data']->last_name);
            $response['subscription_amount'] = number_format($datas['data']->subscription_amount, 2);
            $response['tmc_code'] = strtoupper($datas['data']->tmc_code);
            $response['total_amount_paid'] = number_format($datas['data']->total_amount_paid, 2);
            $response['start_date'] = $start_date->format('M d, Y');
            $response['due_date'] = $due_date->format('M d, Y');
            $response['balance'] = number_format(($datas['data']->subscription_amount - $datas['data']->total_amount_paid), 2);
            $response['ledger'] = $this->ledger_detail($id);
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = 'Error while retreiving data!';
        }

        echo json_encode($response);
    }

    public function ledger_detail($ledger_header_id) {

        $datas = $this->Mdl_inquiry->ledger_detail_table($ledger_header_id);
        if($datas['success']){
            $grand_total = $datas['data'][0]->grand_total;
            $arrData = array();
            $paid_amount = 0;
            foreach ($datas['data'] as $index => $row) {
                $paid_amount += $row->paid_amount;
                $sub_array = array();
                $date_paid = new DateTime($row->date_paid);
                $due_date = new DateTime($row->due_date);
                $sub_array['transaction_log_id'] = $row->transaction_log_id;
                $sub_array['paid_amount'] = number_format($row->paid_amount, 2);
                $sub_array['balance'] = number_format(($grand_total - $paid_amount), 2);
                $sub_array['due_date'] = $due_date->format('D')." | ".$due_date->format('M d, Y');
                $sub_array['date_paid'] = $date_paid->format('D')." | ".$date_paid->format('M d, Y h:i:s a');
                array_push($arrData, $sub_array);
            }

            $response['success'] = true;
            $response['ledger_data'] = $arrData;
        }else{
            $response['success'] = false;
        }

        return $response;
    }

    public function get_break_down_details(){

        $response['generated_token'] = $this->security->get_csrf_hash();
        $id = post('id', true);
        $datas = $this->Mdl_inquiry->get_ledger_header($id);

        if($datas['success']){
            $start_date = new DateTime($datas['data']->start_date);
            $due_date = new DateTime($datas['data']->due_date);

            $response['name'] = ucfirst($datas['data']->first_name) . " " . ucfirst($datas['data']->middle_name) . " " . ucfirst($datas['data']->last_name);
            $response['subscription_amount'] = number_format($datas['data']->subscription_amount, 2);
            $response['tmc_code'] = strtoupper($datas['data']->tmc_code);
            $response['total_amount_paid'] = number_format($datas['data']->total_amount_paid, 2);
            $response['start_date'] = $start_date->format('M d, Y');
            $response['due_date'] = $due_date->format('M d, Y');
            $response['balance'] = number_format(($datas['data']->subscription_amount - $datas['data']->total_amount_paid), 2);
            $response['break_down'] = $this->break_down($id);
            $response['success'] = true;
        }else{
            $response['success'] = false;
            $response['msg'] = 'Error while retreiving data!';
        }

        echo json_encode($response);
    }

    public function break_down($id){

        $datas = $this->Mdl_inquiry->break_down_table($id);
        if($datas['success']){
            $arrData = array();
            foreach ($datas['data'] as $index => $row) {
                $date = new DateTime($row->due_date);
                $sub_array['Lno'] = (++$index);
                $sub_array['due_date'] = $date->format('D')." | ".$date->format('M d, Y');
                $sub_array['amount'] = number_format($row->amount_to_paid, 2);
                $sub_array['balance'] = number_format(($row->amount_to_paid - $row->total_paid_amount), 2);
                array_push($arrData, $sub_array);
            }

            $response['break_down_data'] = $arrData;
            $response['success'] = true;
        }else{
            $response['success'] = false;
        }

        return $response;
    }


    public function get_subscription_transaction(){
        $response['generated_token'] = $this->security->get_csrf_hash();
        $subscription_id = post('id', true);
        $result = $this->Mdl_inquiry->get_subscription_transaction($subscription_id);
        if($result['success']){
            $arrData = array();
            foreach ($result['data'] as $key => $value) {
                array_push($arrData, $value);
                if(count($result['data']) === ++$key){
                    $response['success'] = true;
                    $response['data'] = $arrData;
                }
            }
        }else{
            $response['msg'] = "No transaction logs found!";
            $response['success'] = false;
        }

        echo json_encode($response);
    }
}