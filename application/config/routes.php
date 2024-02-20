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
$route['default_controller'] = 'home';
$route['404_override'] = 'home/error_page';
$route['translate_uri_dashes'] = FALSE;
// $route['404_override'] = '';

$route['login'] = 'home/login_page';
$route['logout'] = 'home/logout';

// department routes
$route['add-department'] = 'department';
$route['insert-department'] = 'department/insert';
$route['manage-department'] = 'department/manage_department';
$route['edit-department/(:num)'] = 'department/edit/$1';
$route['update-department'] = 'department/update';
$route['delete-department/(:num)'] = 'department/delete/$1';

//staff routes
$route['add-staff'] = 'staff';
$route['manage-staff'] = 'staff/manage';
$route['insert-staff'] = 'staff/insert';
$route['delete-staff/(:num)'] = 'staff/delete/$1';
$route['edit-staff/(:num)'] = 'staff/edit/$1';
$route['update-staff'] = 'staff/update';

//salary routes
$route['add-salary'] = 'salary';
$route['manage-salary'] = 'salary/manage';
$route['view-salary'] = 'salary/view';
$route['salary-invoice/(:num)'] = 'salary/invoice/$1';
$route['print-invoice/(:num)'] = 'salary/invoice_print/$1';
$route['delete-salary/(:num)'] = 'salary/delete/$1';

$route['apply-leave'] = 'leave';
$route['approve-leave'] = 'leave/approve';
$route['leave-history'] = 'leave/manage';
$route['leave-approved/(:num)'] = 'leave/insert_approve/$1';
$route['leave-rejected/(:num)'] = 'leave/insert_reject/$1';
$route['view-leave'] = 'leave/view';
$route['salaryinvoice/(:num)'] = 'salary/invoicestaff/$1';

//deduction routes
$route['staff/manage-deductions/(:num)'] = 'deduction/manage/$1';
$route['staff/insert-deductions'] = 'deduction/insert';
$route['staff/edit-deductions/(:num)'] = 'deduction/edit/$1';
$route['staff/update-deductions/(:num)'] = 'deduction/update/$1';
$route['staff/delete-deductions/(:num)'] = 'deduction/delete/$1';

//Attendance routes
$route['export-attendance'] = 'attendance/export';
$route['attendance-manage'] = 'attendance/manage_attendance';
$route['attendance-export'] = 'attendance/export';
$route['attendance-do_export'] = 'attendance/do_export';

//Commisions routes
$route['commission-staff'] = 'commission/index';
$route['commission/insert'] = 'commission/insert';
$route['commission/delete/(:num)'] = 'commission/delete/$1';
$route['commission/update/(:num)'] = 'commission/update/$1';
$route['commission/manage/(:num)'] = 'commission/manage/$1';

//Appraisal route
$route['manage-appraisal'] = 'appraisal/manage';
$route['add-appraisal/(:num)'] = 'appraisal/add/$1';
$route['edit-appraisal/(:num)'] = 'appraisal/edit/$1';
$route['list-appraisal/(:num)'] = 'appraisal/list_appraisal/$1';
$route['review-appraisal/(:num)'] = 'appraisal/review_appraisal/$1';


// Admins routes
$route['admins'] = 'admin/index';
$route['admins/insert'] = 'admin/insert';
$route['admins/update/(:num)'] = 'admin/update/$1';
$route['admins/delete/(:num)'] = 'admin/delete/$1';
