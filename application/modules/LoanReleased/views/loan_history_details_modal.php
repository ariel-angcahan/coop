<div class="modal fade" id="loan-history-details-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <h4 class="modal-title" id="loan-history-details-borrower-name"></h4>
                
                <input type="hidden" name="loan-payment-borrower-id" id="loan-payment-borrower-id">
            </div>
            <div class="modal-body row">
                <div class="table-responsive" id="preview-loan-payment-slimscroll">
                    <table id="loan-history-details-table" class="table table-striped table-hover dataTable">
                        <thead>
                            <tr>
                                <th style="text-align: left;">SEQ # </th>
                                <th style="text-align: left;">LOAN PAID AMOUNT</th>
                                <th style="text-align: left;">PENALTY PAID AMOUNT</th>
                                <th style="text-align: left;">TOTAL PAID AMOUNT</th>
                                <th style="text-align: left;">PAID DATE</th>
                                <th style="text-align: left;">DUE DATE</th>
                                <th style="text-align: center;"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" id="loan-history-details-modal-close" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
