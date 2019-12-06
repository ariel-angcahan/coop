
<style type="text/css">
    table#tbl-ledger-details > tbody > tr > td{
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>
<div class="modal fade" id="preview-ledger-details" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <h4 class="modal-title">LEDGER</h4>
                <button style="float: right; margin-top: -40px;" class="btn sm-btn bg-gray waves-effect waves-block" id="btn-print-ledger"><i class="material-icons">print</i></button>
            </div>
            <div id="preview-ledger-details-slimscroll" class="modal-body row">
                <div class="table-responsive">
                    <table class="table" id="tbl-ledger-details">
                        <tr>
                            <td id="ledger-name"></td>
                            <td id="ledger-code"></td>
                        </tr>
                        <tr>
                            <td id="ledger-approved-date"></td>
                            <td id="ledger-balance"></td>
                        </tr>
                        <tr>
                            <td id="ledger-due-date"></td>
                            <td id="ledger-total-amout-paid"></td>
                        </tr>
                        <tr>
                            <td id="ledger-subscription-amount"></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="table-responsive">
                    <table id="ledger-details" class="table table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <th style="text-align: left;">SEQ. #</th>
                                <th style="text-align: left">TRANS #</th>
                                <th style="text-align: left">PAID AMOUNT</th>
                                <th style="text-align: left">BALANCE</th>
                                <th style="text-align: left">DUEDATE</th>
                                <th style="text-align: left">TRANS DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                    
                <!-- <div style="background-color: #eee; height: 40px; text-align: center;">
                    <label style="padding: 10px;">END OF LIST</label>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
