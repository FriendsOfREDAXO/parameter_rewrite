<?php

/**
 * parameter_rewrite Addon.
 *
 * @author Peter Thiel
 *
 * @var rex_addon
 */

class parameter_rewrite {
	
	public static function rewrite($ep){
		$params = $ep->getParams();
		
		if(rex_config::get('parameter_rewrite', 'params-starter') != ''){
			$paramsStarter = rex_config::get('parameter_rewrite', 'params-starter');
		
			$id = $params['id'];
			$clang = $params['clang'];
			
			foreach (rex_yrewrite::$paths['paths'] as $i_domain => $i_id) {
				if (isset(rex_yrewrite::$paths['paths'][$i_domain][$id][$clang])) {
					$domain = rex_yrewrite::getDomainByName($i_domain);
					$path = $domain->getUrl() . rex_yrewrite::$paths['paths'][$i_domain][$id][$clang];
					break;
				}
			}

			// params
			$urlparams = '';
			if (isset($params['params'])) {
				$urlparams = rex_string::buildQuery($params['params'], $params['separator']);
			}
			
			$urlparams = str_replace('=','/',$urlparams);
			$urlparams = str_replace('&amp;','/',$urlparams);
			$urlparams = $urlparams == '' ? '' : $paramsStarter.'/'.$urlparams.'/';
		
			return $path . $urlparams;
		}
    }
	
	public static function prepare($ep){
		if(rex_config::get('parameter_rewrite', 'params-starter') != ''){
			$path = $_SERVER[REQUEST_URI];
			$paramsStarter = rex_config::get('parameter_rewrite', 'params-starter');
					
			if(strpos($path,$paramsStarter))
			{
				$tmp = explode($paramsStarter.'/',$path);
				$path = ltrim($tmp[0],'/');
				$vars = explode('/',$tmp[1]);
				for($c=0;$c<count($vars);$c+=2)
				{
					if($vars[$c]!='')
					{
						$_GET[$vars[$c]] = urldecode($vars[$c+1]);
						$_REQUEST[$vars[$c]] = urldecode($vars[$c+1]);
					}
				}
			}
			
			$articleId = null;
			$clangId = null;
			
			foreach (rex_yrewrite::$paths['paths'][rex_yrewrite::getCurrentDomain()->getName()] as $i_id => $i_cls) {
				foreach (rex_clang::getAllIds() as $clang_id) {
					if (isset($i_cls[$clang_id]) && $i_cls[$clang_id] == $path) {
						$articleId = $i_id;
						$clangId = $clang_id;
					}
				}
			}

			return ['article_id' => $articleId, 'clang' => $clangId];
		}
	}
}