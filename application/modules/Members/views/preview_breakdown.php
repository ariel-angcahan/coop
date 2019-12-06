<div class="modal fade" id="preview-break-down" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <h4 class="modal-title">BREAKDOWN</h4>
                <button style="float: right; margin-top: -40px;" class="btn sm-btn bg-gray waves-effect waves-block" id="btn-print-break-down"><i class="material-icons">print</i></button>
            </div>
            <div id="preview-break-down_slimscroll" class="modal-body row">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td id="break-down-name"></td>
                            <td id="break-down-code"></td>
                        </tr>
                        <tr>
                            <td id="break-down-approved-date"></td>
                            <td id="break-down-balance"></td>
                        </tr>
                        <tr>
                            <td id="break-down-due-date"></td>
                            <td id="break-down-total-amout-paid"></td>
                        </tr>
                        <tr>
                            <td id="break-down-subscription-amount"></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="table-responsive">
                    <table id="break-down" class="table table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <th style="text-align: left;">SEQ. #</th>
                                <th style="text-align: left;">DUE DATE</th>
                                <th style="text-align: left;">AMOUNT</th>
                                <th style="text-align: left;">BALANCE</th>
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
