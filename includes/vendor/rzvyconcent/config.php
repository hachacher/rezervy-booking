<?php 
$rzvy_cookiesconcent_status = $obj_settings->get_option("rzvy_cookiesconcent_status");
if($rzvy_cookiesconcent_status == "Y"){  
	$rzvy_privacy_and_policy_link = $obj_settings->get_option("rzvy_privacy_and_policy_link");
	$rzvy_seo_ga_code = $obj_settings->get_option("rzvy_seo_ga_code");
	$rzvy_hotjar_tracking_code = $obj_settings->get_option("rzvy_hotjar_tracking_code");
	$rzvy_fbpixel_tracking_code = $obj_settings->get_option("rzvy_fbpixel_tracking_code");
	
	if(isset($rzvy_translangArr['this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website'])){
		$concentdesc = $rzvy_translangArr['this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website'];
	}else{
		$concentdesc = $rzvy_defaultlang['this_website_uses_cookies_to_ensure_you_get_the_best_experience_on_our_website'];
	}
	if(isset($rzvy_translangArr['collecting_anonymous_usage_data_to_improve_the_content_served_on_this_website'])){
		$concentservicedesc = $rzvy_translangArr['collecting_anonymous_usage_data_to_improve_the_content_served_on_this_website'];
	}else{
		$concentservicedesc = $rzvy_defaultlang['collecting_anonymous_usage_data_to_improve_the_content_served_on_this_website'];
	}
	if(isset($rzvy_translangArr['analytics'])){
		$analyticslabel = $rzvy_translangArr['analytics'];
	}else{
		$analyticslabel = $rzvy_defaultlang['analytics'];
	}
	if(isset($rzvy_translangArr['cookies_and_privacy'])){
		$concenttitle = $rzvy_translangArr['cookies_and_privacy'];
	}else{
		$concenttitle = $rzvy_defaultlang['cookies_and_privacy'];
	}
	?>
	<style>
	#rezervy-cookie-concent .cm-modal.cm-klaro{
		background-color: #fff !important;
	}
	#rezervy-cookie-concent .cm-modal.cm-klaro .cm-header{
		border: 0 !important;
	}
	#rezervy-cookie-concent .purposes,
	#rezervy-cookie-concent .cm-modal li,
	#rezervy-cookie-concent .cm-modal.cm-klaro .cm-header a,
	#rezervy-cookie-concent .cm-modal.cm-klaro .cm-header h1{
		color: #007bff !important;
	}
	#rezervy-cookie-concent .cm-modal.cm-klaro .cm-header p{
		color: #717171 !important;
	}
	
	.klaro .cookie-modal .cm-modal .cm-footer{
		border-top: unset !important;
	}
	</style>
	<script>
		var rzvyConsentConfig = {
			version: 1,
			elementID: 'rezervy-cookie-concent',
			htmlTexts: false,
			embedded: false,
			storageMethod: 'cookie',
			cookieName: 'rezervy',
			cookieExpiresAfterDays: 30,
			<?php if($rzvy_hotjar_tracking_code != "" || $rzvy_fbpixel_tracking_code != "" || $rzvy_seo_ga_code != ""){ ?>
				default: true,
				noAutoLoad: true,
				groupByPurpose: true,
				acceptAll: true,
				hideDeclineAll: false,
			<?php }else{ ?>
				default: false,
			<?php } ?>
			mustConsent: true,
			hideLearnMore: false,
			noticeAsModal: true,
			disablePoweredBy: true,
			lang: 'en',
			translations: {
				en: {
					privacyPolicyUrl: '<?php echo $rzvy_privacy_and_policy_link; ?>',
					consentModal: {
						title: '<?php echo $concenttitle; ?>',
						description: '<?php echo $concentdesc; ?>',
					},
					purposes: {
						analytics: '<?php echo $analyticslabel; ?>',
					},
					<?php if($rzvy_hotjar_tracking_code != ""){ ?>
					hotjarAnalytics: {
						description: '<?php echo $concentservicedesc; ?>'
					},
					<?php } ?>
					<?php if($rzvy_fbpixel_tracking_code != ""){ ?>
					facebookPixelAnalytics: {
						description: '<?php echo $concentservicedesc; ?>'
					},
					<?php } ?>
					<?php if($rzvy_seo_ga_code != ""){ ?>
					googleAnalytics: {
						description: '<?php echo $concentservicedesc; ?>'
					},
					<?php } ?>
				},
			},
			services: [
				<?php if($rzvy_hotjar_tracking_code != ""){ ?>
				{
					name: 'hotjarAnalytics',
					default: true,
					title: 'Hotjar Analytics',
					purposes: ['analytics'],
					cookies: [
						[/^_hotjarAnalytics.*$/i, '/', 'rezervy-hotjarAnalytics'],
						['hotjarAnalyticsid', '/', 'rezervy-hotjarAnalytics'],
					],
					required: false,
				},
				<?php } ?>
				<?php if($rzvy_fbpixel_tracking_code != ""){ ?>
				{
					name: 'facebookPixelAnalytics',
					default: true,
					title: 'Facebook Pixel Analytics',
					purposes: ['analytics'],
					cookies: [
						[/^_facebookPixelAnalytics.*$/i, '/', 'rezervy-facebookPixelAnalytics'],
						['facebookPixelAnalyticsid', '/', 'rezervy-facebookPixelAnalytics'],
					],
					required: false,
				},
				<?php } ?>
				<?php if($rzvy_seo_ga_code != ""){ ?>
				{
					name: 'googleAnalytics',
					default: true,
					title: 'Google Analytics',
					purposes: ['analytics'],
					cookies: [
						[/^_googleAnalytics.*$/i, '/', 'rezervy-googleAnalytics'],
						['_googleAnalyticsid', '/', 'rezervy-googleAnalytics'],
						[/^_googleAnalytics_gtm.*$/i, '/', 'rezervy-googleAnalytics'],
					],
					required: false,
				},
				<?php } ?>
			],
		};
		function getCookie(name) {
			var dc = document.cookie;
			var prefix = name + "=";
			var begin = dc.indexOf("; " + prefix);
			if (begin == -1) {
				begin = dc.indexOf(prefix);
				if (begin != 0) return null;
			}
			else
			{
				begin += 2;
				var end = document.cookie.indexOf(";", begin);
				if (end == -1) {
				end = dc.length;
				}
			}
			return decodeURI(dc.substring(begin + prefix.length, end));
		} 
		$(document).ready( function(){
			/* console.log(getCookie('rezervy')); */
			if (getCookie('rezervy') === null){
				klaro.show(rzvyConsentConfig, true);
				if($("#rezervy-cookie-concent .cm-purposes li").length==0){
					$("#rezervy-cookie-concent .cm-body").remove();
				}
			}
		});
	</script>
	<script src="<?php echo SITE_URL; ?>includes/vendor/rzvyconcent/rzvyconcent.js?<?php echo time(); ?>"></script>
	<?php 
}
?>