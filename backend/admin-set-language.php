<?php 
$langOptions = "";
$langCodes = array();
$langOptions_anchortag = "";
$selected_lang = "";
$isSelectedlanguage = "";
$lang_j = 0;
$rzvy_show_dropdown = $obj_set_lang->get_option("rzvy_rzvy_show_dropdown_languages");
$default_language_on_page_load = $obj_set_lang->get_option("rzvy_default_language_on_page_load");

if($rzvy_show_dropdown != ""){ 
	$explodedLang = explode(",", $rzvy_show_dropdown); 
	for($i=0;$i<sizeof($explodedLang);$i++){
		foreach($langnames as $key => $vals){
			if('rzvy-'.$key.'.php' == 'rzvy-'.$explodedLang[$i].'.php' && file_exists(ROOT_PATH.'/uploads/languages/rzvy-'.$explodedLang[$i].'.php')){ 
				if($lang_j==0 && !isset($_COOKIE["rzvy_language"])){ 
					if($default_language_on_page_load != ""){
						setcookie("rzvy_language", $default_language_on_page_load, time() + (86400 * 30), "/");
						$selected_lang = $default_language_on_page_load;
					}else{
						setcookie("rzvy_language", $explodedLang[$i], time() + (86400 * 30), "/");
						$selected_lang = $explodedLang[$i];
					}
					if(file_exists(ROOT_PATH.'/uploads/languages/rzvy-'.$selected_lang.'.php')){ 
						include(ROOT_PATH.'/uploads/languages/rzvy-'.$selected_lang.'.php');
						foreach($rzvy_translangArr as $keys => $value){
							$rzvy_translangArr[$keys] = addslashes(html_entity_decode(htmlspecialchars_decode(base64_decode($value), ENT_QUOTES)));
						}
					}
				}else if($lang_j==0 && isset($_COOKIE["rzvy_language"]) && file_exists(ROOT_PATH.'/uploads/languages/rzvy-'.$_COOKIE["rzvy_language"].'.php')){ 
					$selected_lang = $_COOKIE["rzvy_language"];
					if(file_exists(ROOT_PATH.'/uploads/languages/rzvy-'.$selected_lang.'.php')){ 
						include(ROOT_PATH.'/uploads/languages/rzvy-'.$selected_lang.'.php');
						foreach($rzvy_translangArr as $keys => $value){
							$rzvy_translangArr[$keys] = addslashes(html_entity_decode(htmlspecialchars_decode(base64_decode($value), ENT_QUOTES)));
						}
					}
				}else if($lang_j==0 && isset($_COOKIE["rzvy_language"]) && !file_exists(ROOT_PATH.'/uploads/languages/rzvy-'.$_COOKIE["rzvy_language"].'.php')){ 
					if($default_language_on_page_load != ""){
						setcookie("rzvy_language", $default_language_on_page_load, time() + (86400 * 30), "/");
						$selected_lang = $default_language_on_page_load;
					}else{
						setcookie("rzvy_language", $explodedLang[$i], time() + (86400 * 30), "/");
						$selected_lang = $explodedLang[$i];
					}
					if(file_exists(ROOT_PATH.'/uploads/languages/rzvy-'.$selected_lang.'.php')){ 
						include(ROOT_PATH.'/uploads/languages/rzvy-'.$selected_lang.'.php');
						foreach($rzvy_translangArr as $keys => $value){
							$rzvy_translangArr[$keys] = addslashes(html_entity_decode(htmlspecialchars_decode(base64_decode($value), ENT_QUOTES)));
						}
					}
				}else{} 
				
				$isSelected = "";
				$selectedlangcode = "en";
				if($selected_lang == $explodedLang[$i]){
					$isSelected = "selected";
					$langOptions_anchortag .= '<a href="javascript:void(0)" class="dropdown-item ai-icon rzvy_set_language_atag text-white bg-dark" data-lng="'.$explodedLang[$i].'"><span class="ml-2">'.$vals.'</span></a>';
					$isSelectedlanguage = '<img src="'.SITE_URL.'/includes/images/blank.gif" alt="" class="flag flag-'.str_replace('en','us',substr($explodedLang[$i],0,2)).'"/>'.$vals;					
					$selectedlangcode = substr($explodedLang[$i],0,2);
				}else{
					$langOptions_anchortag .= '<a href="javascript:void(0)" class="dropdown-item ai-icon rzvy_set_language_atag" data-lng="'.$explodedLang[$i].'"><span class="ml-2">'.$vals.'</span></a>';
				}
				
				$langOptions .= '<li class="rzvy_set_language" value="'.$explodedLang[$i].'" '.$isSelected.'><img src="'.SITE_URL.'/includes/images/blank.gif" alt="" class="flag flag-'.str_replace('en','us',substr($explodedLang[$i],0,2)).'"/>'.$vals.'</li>';
				
				$langCodeInfo = explode('_',$explodedLang[$i]);
				$langCodes[]= $langCodeInfo[0];
				
				$lang_j++;
			}
		}
	} 
}