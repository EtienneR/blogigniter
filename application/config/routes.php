<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = 'front/blog';

# 404
//$route['erreur404'] 	= $route['default_controller'] . '/erreur404';
$route['404_override'] = 'front/error404';


/*--------------- ADMIN ---------------*/

$admin_route = 'admin';

# ADMIN (connection)
$route[$admin_route]					 = 'admin/admin';
$route[$admin_route . '/logout']		 = 'admin/admin/logout';
$route[$admin_route . '/reset_password'] = 'admin/admin/reset_password';

# ADMIN content
$route[$admin_route . '/content']				= 'admin/content';
$route[$admin_route . '/content/edit']			= 'admin/content/edit';
$route[$admin_route . '/content/edit/(:num)']	= 'admin/content/edit/$1';
$route[$admin_route . '/content/delete/(:num)'] = 'admin/content/delete/$1';
$route[$admin_route . '/content/t']				= 'admin/content/tag';
$route[$admin_route . '/content/multi_options'] = 'admin/content/multi_options';
$route[$admin_route . '/content/(:any)']		= 'admin/content';
$route[$admin_route . '/user/(:num)']			= 'admin/content/author/$1';

# ADMIN rubric
$route[$admin_route . '/rubric']				 = 'admin/rubric';
$route[$admin_route . '/rubric/edit']			 = 'admin/rubric/edit';
$route[$admin_route . '/rubric/edit/(:num)']	 = 'admin/rubric/edit/$1';
$route[$admin_route . '/rubric/delete/(:num)']	 = 'admin/rubric/delete/$1';
$route[$admin_route . '/content/r'] 			 = 'admin/content/rubric';
$route[$admin_route . '/rubric/(:any)']			 = 'admin/rubric';

# ADMIN comments
$route[$admin_route . '/comments']					  = 'admin/comments';
$route[$admin_route . '/comments/moderate/(:num)']	  = 'admin/comments/moderate/$1';
$route[$admin_route . '/comments/desactivate/(:num)'] = 'admin/comments/desactivate/$1';
$route[$admin_route . '/comments/delete/(:num)']	  = 'admin/comments/delete/$1';
$route[$admin_route . '/comments/(:any)']			  = 'admin/comments';

# ADMIN users
$route[$admin_route . '/user']				   = 'admin/user';
$route[$admin_route . '/user/edit']			   = 'admin/user/edit';
$route[$admin_route . '/user/edit/(:num)']	   = 'admin/user/edit/$1';
$route[$admin_route . '/user/delete/(:num)']   = 'admin/user/delete/$1';
$route[$admin_route . '/user/change_password'] = 'admin/user/change_password';

# ADMIN media
$route[$admin_route . '/medias']		= 'admin/medias';
$route[$admin_route . '/medias/upload'] = 'admin/medias/upload';
$route[$admin_route . '/medias/(:any)'] = 'admin/medias';

# ADMIN params
$route[$admin_route . '/params']		= 'admin/params';
$route[$admin_route . '/search']		= 'admin/params/search';
$route[$admin_route . '/params/(:any)'] = 'admin/params';


/*--------------- FRONT ---------------*/

# FRONT contact
$route['contact'] = 'front/contact';

# FRONT RSS
$route['rss'] = "front/feed";

# FRONT Search
$route['s'] = $route['default_controller'] . '/search';

# FRONT Author
//$route['auteur'] = $route['default_controller'];
$route['author/(:any)'] = $route['default_controller'] . '/author/$1';

# FRONT Tag
$route['t'] = $route['default_controller'] . '/tags';

# FRONT Pagination home
$route['page/(:num)'] = $route['default_controller'] . '/index/$1';

# FRONT Rubric
$route['(:any)'] = $route['default_controller'] . '/view/$1';

# FRONT Rubric + Content
$route['(:any)/(:any)'] = $route['default_controller'] . '/view/$1/$2';


/* End of file routes.php */
/* Location: ./application/config/routes.php */