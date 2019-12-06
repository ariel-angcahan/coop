<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Index';

$route['404_override'] = 'pagenotfound';
$route['translate_uri_dashes'] = FALSE;

// Logout
$route['Authenticate'] = 'Logins/authentication';
$route['Logout'] = 'Logins/logout';



// Dashboards
$route['Dashboards'] = 'Dashboards';
$route['Dashboard'] = 'Dashboards';
$route['dashboards'] = 'Dashboards';
$route['dashboard'] = 'Dashboards';

//Access Rights
$route['security/access/user'] = 'Access_rights/user';
$route['security/access/role'] = 'Access_rights/role';
$route['security/access/user/getEmpList'] = 'Access_rights/employee_list';
$route['security/access/get_AccessRights'] = 'Access_rights/get_AccessRights';
$route['security/access/user/menu'] = 'Access_rights/menu';
$route['security/access/user/stat_update'] = 'Access_rights/stat_update';
$route['security/access/ar_update'] = 'Access_rights/ar_update';
$route['security/access/getAccessRights'] = 'Access_rights/getAccessRights';

$route['security/access/role/getRoleAccessRights'] = 'Access_rights/getRoleAccessRights';
$route['security/access/role/ar_update'] = 'Access_rights/ar_update';

//profile 
$route['Profile/getAccessID'] = 'Profiles/getAccessID';
$route['Profile'] = 'Profiles/Profiles';
$route['Profile/changeProfilePicture'] = 'Profiles/changeProfilePicture';
$route['Profile/changePassword'] = 'Profiles/changePassword';
$route['Profile/savePassword'] = 'Profiles/savePassword';









//Application routes
$route['loans/application'] = 'LoanApplication';
$route['loans/approval'] = 'LoanApproval';
$route['loans/releasing'] = 'LoanReleasing';
$route['loans/released'] = 'LoanReleased';
$route['loans/settings'] = 'LoanSettings';
$route['loans/penalty'] = 'LoanPenalty';
$route['loans/released/loan_amortization_schedule'] = 'LoanReleased/loan_amortization_schedule';
$route['loans/released/loan_ledger_report'] = 'LoanReleased/loan_ledger_report';