<div class="user-info">
    <div class="image">
        <img id="user-img" src="<?PHP echo $this->session->avatar; ?>" width="48" height="48" alt="User" />
    </div>
    <div class="info-container">
        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?PHP echo strtoupper(get_user_role($this->session->RoleId));?></div>
        <div class="name"><?PHP echo ucwords($this->session->name);?></div>
        <div class="btn-group user-helper-dropdown">
            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
            <ul class="dropdown-menu pull-right">
                <li><a href="<?PHP echo base_url('/Profiles'); ?>"><i class="material-icons">person</i>Profile</a></li>
                <!-- <li><a href="javascript:void(0);"><i class="material-icons">settings</i>Settings</a></li> -->
                <li><a href="<?PHP echo base_url('/Logout'); ?>"><i class="material-icons">input</i>Sign Out</a></li>
            </ul>
        </div>
    </div>
</div>