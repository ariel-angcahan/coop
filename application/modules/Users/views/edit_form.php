<div class="modal fade" id="editUsers" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2196f3; color: white;">
                <h4 class="modal-title" id="largeModalLabel2">Edit User</h4>
            </div>
            <div class="modal-body">
                <div class="body">
                    <div class="row">
                        <div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#image" aria-controls="settings" role="tab" data-toggle="tab">Image</a></li>
                                <li role="presentation"><a href="#profile_settings" aria-controls="settings" role="tab" data-toggle="tab">Profile Settings</a></li>
                                <li role="presentation"><a href="#change_password_settings" aria-controls="settings" role="tab" data-toggle="tab">Change Password</a></li>
                            </ul>
                            <input type="hidden" id="selected-edit-user-id">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="image">
                                    <div class="panel panel-default panel-post">
                                        <div class="panel-heading">
                                            <div class="media">
                                                <div class="media-left">
                                                    <a href="#">
                                                        <img id="preview-edit-image-user" src="" />
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">
                                                        <a href="#" id="preview-edit-image-user-name"></a>
                                                    </h4>
                                                    <p id="image-date">
                                                        
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="post">
                                                <div class="post-content">
                                                    <div id="image-upload" class="dropzone">
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
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade in" id="profile_settings">
                                    <form class="form-horizontal" id="edit-form-profile">
                                        <br>
                                        <label for="edit-first-name" class="col-sm-3 control-label">Firstname</label>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="edit-first-name" name="edit-first-name" placeholder="Firstname">
                                                </div>
                                            </div>
                                        </div>
                                        <label for="edit-last-name" class="col-sm-3 control-label">Lastname</label>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="edit-last-name" name="edit-last-name" placeholder="Lastname">
                                                </div>
                                            </div>
                                        </div>
                                        <label for="Email" class="col-sm-3 control-label">Role</label>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <select name="role" id="select-edit-role" class="form-control show-tick" data-size="5" data-live-search="true"></select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger">SAVE</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane fade in" id="change_password_settings">
                                    <form class="form-horizontal" id="form-password-settings">
                                        <br>
                                        <label for="OldPassword" class="col-sm-3 control-label">Old Password</label>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="edit-old-password" name="edit-old-password" placeholder="Old Password">
                                                </div>
                                            </div>
                                        </div>
                                        <label for="NewPassword" class="col-sm-3 control-label">New Password</label>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="edit-new-password" name="edit-new-password" placeholder="New Password">
                                                </div>
                                            </div>
                                        </div>
                                        <label for="NewPasswordConfirm" class="col-sm-3 control-label">New Password (Confirm)</label>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="password" class="form-control" id="edit-new-password-confirm" name="edit-new-password-confirm" placeholder="New Password (Confirm)">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger">SAVE</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>