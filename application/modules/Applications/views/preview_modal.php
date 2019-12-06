<div class="modal fade" id="preview-info" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-body">
            <div class="col-sm-12">
                <div class="collapse" id="collapse_upload">
                    <div class="body">
                        <div id="image-upload" class="dropzone" data-id="">
                            <div class="dz-message">
                                <div class="drag-icon-cph">
                                    <i class="material-icons">touch_app</i>
                                </div>
                                <h3>Drop image here or click to upload.</h3>
                            </div>
                            <div class="fallback">
                                <input name="file" id="file" type="file"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card profile-card">
                    <div class="profile-header">&nbsp;</div>
                    <div class="profile-body">
                        <div class="image-area">
                            <img id="applicant-image" src="" width="150px" alt="" />
                            <button id="btn-upload" style="position: absolute;
                                          left: 50%;
                                          transform: translate(-50%, -50%);
                                          -ms-transform: translate(-50%, -50%);
                                          background-color: #555;
                                          color: white;
                                          font-size: 16px;
                                          padding: 5px 10px;
                                          border: none;
                                          cursor: pointer;
                                          border-radius: 5px;" data-toggle="collapse" data-target="#collapse_upload" aria-expanded="false"
                            aria-controls="collapse_upload">
                            <i class="material-icons">add_a_photo</i>
                            </button>
                        </div>
                        <div class="content-area">
                            <h3 id="full_name"></h3>
                            <p id="birth_date"></p>
                            <p id="email"></p>
                            <p id="mobile"></p>
                            <p id="market_address"></p>
                            <p id="membership_type"></p>
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                <span>Payment Terms</span>
                                <span id="payment_mode"></span>
                            </li>
                            <li>
                                <span>Subscription Amount</span>
                                <span id="subscription_amount"></span>
                            </li>
                            <li>
                                <span>Payment Per Terms</span>
                                <span id="payment_per_mode"></span>
                            </li>
                        </ul>
                        <button id="btn-approve" class="btn btn-primary btn-lg waves-effect btn-block">Approve</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>