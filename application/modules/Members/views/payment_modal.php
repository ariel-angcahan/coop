<div class="modal fade" id="payment-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <h4 class="modal-title">PAYMENT</h4>
            </div>
            <div class="modal-body row">
                <div class="col-sm-12">
                    <p>
                        <b>TMC CODE</b>
                    </p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="payment-tmc-code" id="payment-tmc-code"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <p>
                        <b>ACCOUNT HOLDER</b>
                    </p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="payment-account-holder" id="payment-account-holder" readonly="" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <p>
                        <b>AMOUNT TO PAY</b>
                    </p>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" name="payment-amount" id="payment-amount" placeholder="0.00" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-payment-proceed" class="btn btn-link waves-effect">PROCEED</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
