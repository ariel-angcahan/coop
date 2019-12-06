<div class="modal fade" id="new-subscription-request-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <h4 class="modal-title">NEW REQUEST</h4>
            </div>
            <div class="modal-body row">
                <div class="col-sm-12">
                    <p>
                        <b>TMC CODE</b>
                    </p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="request-tmc-code" id="request-tmc-code"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <p>
                        <b>ACCOUNT HOLDER</b>
                    </p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="request-account-holder" id="request-account-holder" readonly="" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <p>
                        <b>SUBSCRIPTION AMOUNT</b>
                    </p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="request-subscriptiom-amount" id="request-subscriptiom-amount" placeholder="0.00" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <p>
                        <b>FREQUENCY OF PAYMENT</b>
                    </p>
                    <div class="form-group">
                        <div class="form-line">
                            <select class="form-control show-tick" data-live-search="true" name="subscription-payment-mode" id="subscription-payment-mode">
                                <?php echo frequencyOfPayment(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <p>
                        <b>PAYMENT PER MODE</b>
                    </p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="request-payment-per-mode-amount" id="request-payment-per-mode-amount" placeholder="0.00" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-request-proceed" class="btn btn-link waves-effect">REQUEST</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
