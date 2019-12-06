<div>
	<input type="hidden" id="controller" value="<?PHP echo $this->router->fetch_class().'/'; ?>">
	<input type="hidden" name="token" value="<?PHP echo $generated_token; ?>" id="token">
	<!-- <input type="hidden" name="department_id" value="<?PHP //echo $this->session->department_id; ?>" id="department_id"> -->
	<input type="hidden" id="base_url" value="<?PHP echo base_url(); ?>">
</div>