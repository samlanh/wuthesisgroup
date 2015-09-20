<?php

class Application_Model_Decorator
{
	public function init()
	{
		/* Initialize action controller here */
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	public static function removeAllDecorator($form)
	{
		$elements=$form->getElements();
		foreach($elements as $element){
			$element->removeDecorator('HtmlTag');
			$element->removeDecorator('DtDdWrapper');
			$element->removeDecorator('Label');		
			$element->removeDecorator('Errors');	
		}
		
	}	
	public static function removeAllDecoratorExceptError($form)
	{
		$elements=$form->getElements();
		foreach($elements as $element){
			$element->removeDecorator('HtmlTag');
			$element->removeDecorator('DtDdWrapper');
			$element->removeDecorator('Label');
		}
	}
	public function getMenuLeft($arr_menu,$controller,$module=null){
		$menu='';
		$i=0;
		if(is_array($arr_menu)){
			foreach($arr_menu as $param=>$url){
				if($param==$controller){
					$menu.=$this->spanMenu($url);
				}else{
					if($module!=null){
						$uri=$this->baseUrl().'/'.$module.'/'.$param;
						$url=str_replace('href=""', 'href="'.$uri.'"', $url);
						$menu.=$url;
					}else{
						$menu.=$url;
					}
				}
				$i++;
			}
			return $menu;
		}
		return null;
	}
	public function spanMenu($url,$class="current-left"){
		$temp=str_replace('<a', '<span class="'.$class.'"', $url);
		$temp=str_replace('</a>', '</span>', $temp);
		return $temp;
	}
	public function baseUrl(){
		return Zend_Controller_Front::getInstance()->getBaseUrl();
	}
}