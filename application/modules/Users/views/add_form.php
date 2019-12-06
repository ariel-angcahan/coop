<div class="modal fade" id="addUsers" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2196f3; color: white;">
                <h4 class="modal-title" id="largeModalLabel1">Add User</h4>
            </div>
            <div class="modal-body">
                <form id="add-form-profile">
                    <div class="body">
                        <div class="row">
                            <div class="col-md-3">
                                <image class="img-responsive thumbnail" id="avatar" src="<?PHP echo user_base_url('add_avatar.png'); ?>" with="150px" height="170px"/>
                                <input name="add-file" id="add-file" type="file" style="visibility: hidden;"/>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="email_address">First Name</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="add-fname" id="add-fname" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email_address">Last Name</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="add-lname" id="add-lname" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="email_address">Username</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="add-username" id="add-username" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email_address">Password</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="password" name="add-password" id="add-password" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Role</label>
                                        <div class="form-group">
                                            <select name="add-role" id="add-role" class="form-control show-tick" data-size="5" data-live-search="true">
                                            <?PHP echo $roles ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email_address">Confirm Password</label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="password" name="add-cpassword" id="add-cpassword" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>       
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect">SAVE</button>
                    <button type="button" class="btn btn-primary waves-effect" id="btnClearForm">CLEAR</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>