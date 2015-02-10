<?php
if ( ! function_exists('css_url'))
{
	function css_url($file_name)
	{
		return '<link rel="stylesheet" href="' . base_url() . 'assets/css/' . $file_name . '.css" />
	';
	}
}

if ( ! function_exists('js_url'))
{
	function js_url($file_name)
	{
		return '<script src="' . base_url() . 'assets/js/' . $file_name . '.js"></script>
	';
	}
}

if ( ! function_exists('content_url'))
{
	function content_url($rubric, $content, $title)
	{
		return '<a href="' . base_url($rubric . '/' . $content) . '">' . $title . '</a>';
	}
}

if ( ! function_exists('content_url_button'))
{
	function content_url_button($rubric, $content)
	{
		return '<a href="' . base_url($rubric . '/' . $content) . '" class="btn btn-primary">Lire la suite</a>';
	}
}

if ( ! function_exists('rubric_url'))
{
	function rubric_url($rubric, $title)
	{
		return '<a href="' . base_url($rubric) . '">' . $title . '</a>';
	}
}

if ( ! function_exists('author_url'))
{
	function author_url($author)
	{
		return '<a href="' . base_url('author/' . $author) . '">' . $author . '</a>';
	}
}

if ( ! function_exists('tag_url'))
{
	function tag_url($tag)
	{	
		if ($tag != ''):
			return '<a href="' . base_url('t?q=' . $tag) . '">' . $tag . '</a> ';
		endif;
	}
}

if ( ! function_exists('img_thumb') )
{
	function img_thumb($image)
	{
		return '<img src="' . $image . '" alt="" class="img-responsive" style="margin: 0 auto; text-align: center;" />';
	}
}

if ( ! function_exists('img_thumb_url') )
{
	function img_thumb_url($rubric, $content, $image)
	{
		return '<a href="' . base_url($rubric . '/' . $content).'">
					<img src="' . $image . '" alt="" class="img-responsive" style="margin: 0 auto; text-align: center;" />
				</a>';
	}
}

if ( ! function_exists('date_fr'))
{
	function date_fr($jour, $mois, $annee)
	{
		$mois_n = $mois;
		switch ($mois) {
			case '01':
				$mois = 'Janvier';
				break;
			case '02':
				$mois = 'Février';
				break;
			case '03':
				$mois = 'Mars';
				break;
			case '04':
				$mois = 'Avril';
				break;
			case '05':
				$mois = 'Mai';
				break;
			case '06':
				$mois = 'Juin';
				break;
			case '7':
				$mois = 'Juillet';
				break;
			case '8':
				$mois = 'Août';
				break;
			case '9':
				$mois = 'Septembre';
				break;
			case '10':
				$mois = 'Octobre';
				break;
			case '11':
				$mois = 'Novembre';
				break;
			case '12':
				$mois = 'Décembre';
				break;
			
			default:
				break;
		}

		return '<time datetime="' . $annee . '-' . $mois_n . '-' . $jour . '">' .$jour . ' ' . $mois . ' ' . $annee . '</time>';
	}
}

if ( ! function_exists('date_complete_fr'))
{
	function date_complete_fr($jour, $mois, $annee, $heure, $minute)
	{
		$mois_n = $mois;
		switch ($mois) {
			case '01':
				$mois = 'Janvier';
				break;
			case '02':
				$mois = 'Février';
				break;
			case '03':
				$mois = 'Mars';
				break;
			case '04':
				$mois = 'Avril';
				break;
			case '05':
				$mois = 'Mai';
				break;
			case '06':
				$mois = 'Juin';
				break;
			case '7':
				$mois = 'Juillet';
				break;
			case '8':
				$mois = 'Août';
				break;
			case '9':
				$mois = 'Septembre';
				break;
			case '10':
				$mois = 'Octobre';
				break;
			case '11':
				$mois = 'Novembre';
				break;
			case '12':
				$mois = 'Décembre';
				break;
			
			default:
				break;
		}

		return $jour . '  ' . $mois . ' ' . $annee . ' à ' . $heure . 'h' . $minute;
	}
}

if ( ! function_exists('pagination_custom'))
{
	function pagination_custom($p_nb_listing)
	{

		$config['per_page']         = $p_nb_listing;
		$config['use_page_numbers'] = TRUE;

		$config['full_tag_open']    = '<ul class="pagination">';
		$config['full_tag_close']   = '</ul><!--pagination-->';
		$config['num_tag_open']     = '<li>';
		$config['num_tag_close']    = '</li>';
		$config['cur_tag_open']     = '<li class="active"><span>';
		$config['cur_tag_close']    = '</span></li>';
		$config['next_tag_open']    = '<li>';
		$config['next_tag_close']   = '</li>';
		$config['prev_tag_open']    = '<li>';
		$config['prev_tag_close']   = '</li>';
		$config['first_tag_open']   = '<li style="display: none;">';
		$config['first_tag_close']  = '</li>';
		$config['last_tag_open']    = '<li style="display: none;">';
		$config['last_tag_close']   = '</li>';

		return $config;
	}

}

if ( ! function_exists('pagination_links'))
{
	function pagination_links($base_url, $first_url, $total_rows, $num_links, $uri_segment)
	{	
		$config['base_url']    = $base_url;
		$config['first_url']   = $first_url;
		$config['total_rows']  = $total_rows;
		$config['num_links']   = $num_links;
		$config['uri_segment'] = $uri_segment;

		return $config;
	}
}

if ( ! function_exists('get_tags') )
{
	function get_tags($tags)
	{
		$tags = explode(';', $tags);
		foreach ($tags as $tag):
			if (!empty($tag)):
				echo '<a href="' . base_url('admin/content') . '/t?q=' . $tag . '" title="Afficher tous les articles avec ce tag">'. $tag . '</a> ';
			endif;
		endforeach;
	}
}

if ( ! function_exists('get_active_tags') )
{
	function get_active_tags($tags, $tag)
	{
		if ($tags):
			for ($i = 0; $i < count($tags); $i++):
				if ($tag == $tags[$i]):
					echo 'checked';
				endif;
			endfor;
		endif;
	}
}

if ( ! function_exists('meta_twitter') )
{
	function meta_twitter($twitter, $meta_title, $meta_desc, $c_image, $page)
	{
		echo '			<meta property="twitter:card" content="summary" />
			<meta property="twitter:site" content="' . $twitter . '" />
			<meta property="twitter:title" content="' . $meta_title . '" />
			<meta property="twitter:description" content="' . $meta_desc . '" />';
			 if ($page == "content"):
				if ($c_image):
					echo '<meta property="twitter:image" content="' . $c_image . ' " />';
				endif;
			endif;
			echo '<meta property="twitter:url" content="' . current_url() . '" />';
	}
}

if ( ! function_exists('meta_google') )
{
	function meta_google($google)
	{
		echo '<link rel="author" href="https://plus.google.com/' . $google . '" />';
	}
}


/* End of file functions_helper.php */
/* Location: ./application/helpers/functions_helper.php */