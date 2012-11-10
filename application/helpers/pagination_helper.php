<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); 


/*
* This function creates a pagination using the CI pagination library. It will allow a dynamic use of attributes as a multidimensional array (MUST BE ARRAY).
* Styling and structure is molded to the twitter bootstrap form.
*/
/* EXPECT THIS KIND OF ATTRIBUTES ARRAY:
	array(
		'full_tag_div' => array(
			'class' => array(
				'blah',
				'lol',
			),
			'id'	=> array(
				'blah',
				'lol',
			),
			'super_id'	=> 'lolololol',
		),
		... so on
	);
	
	return: class="blah lol" id="blah lol" super_id="lolololol"
*/
if(!function_exists('create_pagination')){

	function create_pagination($base_url, $total_rows, $limit, $attributes = array(), $uri_segment = 3, $display_pages = true, $num_links = 2, $use_page_numbers = false){
		
		$CI =& get_instance();
		
		if(!empty($attributes) AND is_array($attributes)){
		
			foreach($attributes as $tag_key => $tag_value){
			
				#is tag_value an array? if not, just leave it
				if(is_array($tag_value)){
				
					$attribute_string = '';
					
					foreach($tag_value as $key => $value){
					
						$attribute_string .= ' ' . $key . '="' . ((is_array($value)) ? implode(' ', $value) : $value) . '"';
						
					}
					
					#replace the tag_value which was an array with the new constructed string
					$tag_value = $attribute_string;
					
				}else{
				
					#$tag_value must be string by now
					$tag_value = ' ' . $tag_value;
				
				}
				
				$attributes[$tag_key] = $tag_value;
			
			}
		
		}else{
		
			$attributes = false;
		
		}
		
		#working on pagination now
		$pagination_config = array(
			'base_url'			=> $base_url,
			'total_rows'		=> $total_rows,
			'per_page'			=> $limit,
			'uri_segment'		=> $uri_segment,
			'display_pages'		=> $display_pages,
			'num_links'			=> $num_links,
			'use_page_numbers'	=> $use_page_numbers,
			'full_tag_open'		=> '<div' . ((!empty($attributes['full_tag_div'])) ? $attributes['full_tag_div'] : '') . '><ul' . ((!empty($attributes['full_tag_ul'])) ? $attributes['full_tag_ul'] : '') . '>',
			'full_tag_close'	=> '</ul></div>',
			'first_link'		=> '&laquo;',
			'first_tag_open'	=> '<li' . ((!empty($attributes['first_tag'])) ? $attributes['first_tag'] : '') . '>',
			'first_tag_close'	=> '</li>',
			'last_link'			=> '&raquo;',
			'last_tag_open'		=> '<li' . ((!empty($attributes['last_tag'])) ? $attributes['last_tag'] : '') . '>',
			'last_tag_close'	=> '</li>',
			'next_link'			=> '&rsaquo;',
			'next_tag_open'		=> '<li' . ((!empty($attributes['next_tag'])) ? $attributes['next_tag'] : '') . '>',
			'next_tag_close'	=> '</li>',
			'prev_link'			=> '&lsaquo;',
			'prev_tag_open'		=> '<li' . ((!empty($attributes['prev_tag'])) ? $attributes['prev_tag'] : '') . '>',
			'prev_tag_close'	=> '</li>',
			'cur_tag_open'		=> '<li' . ((!empty($attributes['cur_tag'])) ? $attributes['cur_tag'] : '') . '><span>',
			'cur_tag_close'		=> '</span></li>',
			'num_tag_open'		=> '<li' . ((!empty($attributes['num_tag'])) ? $attributes['num_tag'] : '') . '>',
			'num_tag_close'		=> '</li>',
		);
		
		$CI->pagination->initialize($pagination_config);
		
		return $CI->pagination->create_links();

	}
	
}