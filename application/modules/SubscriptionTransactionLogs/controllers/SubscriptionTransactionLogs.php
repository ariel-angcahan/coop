<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubscriptionTransactionLogs extends MX_Controller {
    public function __construct(){

        parent::__construct();
        $this->load->model('Mdl_subscriptionTransactionLogs');
        date_default_timezone_set("Asia/Manila");

        if(!$this->session->userdata('isLogin')) {
            header('location:'.base_url());
            die();
        }
    }

    public function index() {
        
        $data['generated_token'] = $this->security->get_csrf_hash();
        $this->load->view('standard_layout.php', $data);
    }

    public function subscription_transaction_logs() {

        $datas = $this->Mdl_subscriptionTransactionLogs->subscription_transaction_list_table();

        $data = array();
        foreach ($datas as $index => $row) {
            $date_created = new DateTime($row->date_transact);

            $sub_array = array();
            $sub_array[] = ++$index;
            $sub_array[] = strtoupper($row->tmc_code);
            $sub_array[] = ucwords($row->member_name);
            $sub_array[] = number_format($row->amount, 2);
            $sub_array[] = $date_created->format('D')." | ".$date_created->format('M d, Y | h:m:i A');
            $sub_array[] = '<a class="btn btn-transaction-details bg-blue btn-md" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="bottom" title="Personal Details">
                                More Details
                            </a>';
            $data[] = $sub_array;
        }

        $output = array(  
            "draw"              => intval($this->input->post('draw')),  
            "recordsTotal"      => $this->Mdl_subscriptionTransactionLogs->get_all_subscription_transaction_data(),  
            "recordsFiltered"   => $this->Mdl_subscriptionTransactionLogs->get_filtered_subscription_transaction_data(),  
            "data"              => $data,
            "generated_token"   => $this->security->get_csrf_hash()
        );

        echo json_encode($output);
    }

    public function get_transaction_details(){
        $id = $this->input->post('id');
        $result = $this->Mdl_subscriptionTransactionLogs->get_transaction_details($id);

        $respose['generated_token'] = $this->security->get_csrf_hash();

        if($result['success']){
            $arrDataSet = array();
            $total_amount = 0;
            foreach ($result['data'] as $key => $value) {
                $date_paid = new DateTime($value->date_paid);
                $due_date = new DateTime($value->due_date);
                $data['due_date'] = date_format($due_date, "D")." | ".date_format($due_date, "M d, Y");
                $data['paid_amount'] = number_format($value->paid_amount, 2);
                $data['date_paid'] = date_format($date_paid,'D')." | ".date_format($date_paid,'M d, Y | h:i:s A');
                $total_amount += $value->paid_amount;
                array_push($arrDataSet, $data);

                $tot_amount += $value->paid_amount;

                if(count($result['data']) === ++$key){
                    $respose['total_amount'] = number_format($tot_amount, 2);
                    $respose['success'] = true;
                    $respose['data'] = $arrDataSet;
                }
            }
        }else{
            $respose['success'] = false;
            $respose['msg'] = "Error! No data found!";
        }

        echo json_encode($respose);
    }





























    public function transaction_report(){

        $from = new DateTime($this->input->get('date_from'));
        $to = new DateTime($this->input->get('date_date'));

        $body = $this->Mdl_subscriptionTransactionLogs->transaction_logs_report($from, $to);
        if($body['success']){
            $body_html .= ' <table width="100%;">
                                <thead>
                                    <tr>
                                        <td style="font-weight: bold;">SEQ. #</td>
                                        <!--<td style="font-weight: bold;">TMC ID</td>-->
                                        <td style="font-weight: bold;">MEMBER</td>
                                        <td style="font-weight: bold;">AMOUNT</td>
                                        <td style="font-weight: bold;">TRANS DATE</td>
                                    </tr>
                                </thead>
                                <tbody>';
            $arrAmount = array();
            $total_balance = 0;
            $Lno = 1;
            foreach ($body['data'] as $index => $row) {
                $date = new DateTime($row->date_transact);
                $body_html .=       '<tr>';
                $body_html .=            '<td>'.($Lno).'</td>';
                // $body_html .=            '<td>'.strtoupper($row->tmc_code).'</td>';
                $body_html .=            '<td>'.ucwords(trim($row->member_name)).'</td>';
                $body_html .=            '<td>'.number_format($row->amount, 2).'</td>';
                $body_html .=            '<td>'.$date->format('M d, Y').'</td>';
                $body_html .=       '</tr>';
                // $total_amount += number_format($row->amount, 2);
                array_push($arrAmount, $row->amount);
                if(count($body['data']) === ++$index){
                    $body_html .=       '<tr>';
                    $body_html .=           '<td></td>';
                    $body_html .=           '<td style="text-align: left; font-weight: bold;">TOTAL:</td>';
                    $body_html .=           '<td style="text-align: left; font-weight: bold;">'.number_format(array_sum($arrAmount), 2).'</td>';
                    $body_html .=           '<td></td>';
                    $body_html .=       '</tr>';
                }
                $Lno++;
            }

            $body_html .= '     </tbody>';
            $body_html .= ' </table>';
        }

        $mpdf = new mPDF('utf-8','letter'); 
        $mpdf->AddPage('P');
        $mpdf->WriteHTML($body_html);
        $mpdf->Output();
    }

    // public function transaction_report_details(){

    //     $from = new DateTime($this->input->get('date_from'));
    //     $to = new DateTime($this->input->get('date_date'));

    //     $body = $this->Mdl_subscriptionTransactionLogs->transaction_logs_report($from, $to);
    //     if($body['success']){
    //         $body_html .= ' <table width="100%;">
    //                             <thead>
    //                                 <tr>
    //                                     <td style="font-weight: bold;">SEQ. #</td>
    //                                     <!--<td style="font-weight: bold;">TMC ID</td>-->
    //                                     <td style="font-weight: bold;">MEMBER</td>
    //                                     <td style="font-weight: bold;">AMOUNT</td>
    //                                     <td style="font-weight: bold;">TRANSACTION DATE</td>
    //                                 </tr>
    //                             </thead>
    //                             <tbody>';
    //         $arrAmount = array();
    //         $total_balance = 0;
    //         $Lno = 1;
    //         foreach ($body['data'] as $index => $row) {
    //             $date = new DateTime($row->date_transact);
    //             $body_html .=       '<tr>';
    //             $body_html .=            '<td>'.($Lno).'</td>';
    //             // $body_html .=            '<td>'.strtoupper($row->tmc_code).'</td>';
    //             $body_html .=            '<td>'.ucwords(trim($row->member_name)).'</td>';
    //             $body_html .=            '<td>'.number_format($row->amount, 2).'</td>';
    //             $body_html .=            '<td>'.$date->format('M d, Y').'</td>';
    //             $body_html .=       '</tr>';
    //             // $total_amount += number_format($row->amount, 2);
    //             array_push($arrAmount, $row->amount);
    //             if(count($body['data']) === ++$index){
    //                 $body_html .=       '<tr>';
    //                 $body_html .=           '<td></td>';
    //                 $body_html .=           '<td style="text-align: right; font-weight: bold;">TOTAL:</td>';
    //                 $body_html .=           '<td>'.number_format(array_sum($arrAmount), 2).'</td>';
    //                 $body_html .=           '<td></td>';
    //                 $body_html .=       '</tr>';
    //             }
    //             $Lno++;
    //         }

    //         $body_html .= '     </tbody>';
    //         $body_html .= ' </table>';
    //     }

    //     $mpdf = new mPDF('utf-8','letter'); 
    //     $mpdf->AddPage('P');
    //     $mpdf->WriteHTML($body_html);
    //     $mpdf->Output();
    // }
}