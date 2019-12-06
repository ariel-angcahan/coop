<div class="modal fade" id="subscription-list-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="overflow: hidden;">
            <div class="modal-header bg-blue">
                <h4 class="modal-title">Subscription Payment Detailed Ratings</h4>
            </div>
            <div class="modal-body row">
                <div id="div-subscription-list-table" class="body table-responsive">
                    <table id="subscription-list-table" class="table table-striped table-hover dataTable">
                        <thead>
                            <th style="text-align: left;">SEQ #</th>
                            <th style="text-align: left;">SUBSCRIPTION AMOUNT</th>
                            <th style="text-align: left;">STATUS</th>
                            <th style="text-align: left;">RATING</th>
                            <th></th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div id="div-subscription-detail-list-table" class="body table-responsive" style="display: none;">
                    <button type="button" class="btn dropdown-toggle bg-purple" id="btn-subscription-list-table">Back</button>
                    <table id="subscription-detail-list-table" class="table table-striped table-hover dataTable">
                        <thead>
                            <th style="text-align: left;">SEQ #</th>
                            <th style="text-align: left;">DUE DATE</th>
                            <th style="text-align: left;">TOTAL<br>PAYMENT COUNT</th>
                            <th style="text-align: left;">ON TIME<br>PAYMENT COUNT</th>
                            <th style="text-align: left;">RATING<br><small style="font-size: 9px;">(On-Time / Total) * 100</small></th>
                            <th style="text-align: left;">DELAYED<br> PAYMENT COUNT</th>
                            <th style="text-align: left;">RATING<br><small style="font-size: 9px;">(Delayed / Total) * 100</small></th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
