<?php


namespace houdunwang\core;

//创建Controller类执行提示性的文字和模板
class Controller {
	//    定义私有变量传递，默认返回地址
	private $url = 'window.history.back()';
	//	定义一个变量，来储存地址template
	private $template;
//	创建msg属性接收操作完成的提示性的文字
	private $msg;


//	 跳转
//	创建一个跳转方法，默认返回上一级
	protected function setRedirect($url){
//		将跳转的地址接收，返回到appRun触发__toString方法载入跳转页面
		$this->url = "location.href='{$url}'";
//		返回当前的对象
		return $this;
	}


//成功的时候
//	建一个success方法显示成功后的提示语，并返回到当前对象
	protected function success($msg){
		//		保存子类传来的提示语，返回到当前对象
		$this->msg = $msg;
//		要载入的页面地址赋予template,触发__tostring方法来完成载入模板
		$this->template = './view/success.php';
//		返回当前对象触发__tostring方法
		return $this;
	}


//	  失败的时候
//	建一个error方法执行失败的时候的操作提示
	protected function error($msg){
		//		保存子类传来的提示语，返回到当前对象
		$this->msg = $msg;
		//		要载入的页面地址赋予template,触发__tostring方法来完成载入模板
		$this->template = './view/error.php';
		//		返回当前对象触发__tostring方法
		return $this;
	}

	public function __toString() {
//		触发__tostring方法载入跳转界面，完成跳转
		include $this->template;
//		返回一个空数组 ，因为这个方法必须反回字符串
		return '';
	}
}