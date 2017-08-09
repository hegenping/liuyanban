<?php

namespace houdunwang\view;

//创建一个Base类，当app/home/controller/entry中的entry类中的index方法调用View类时会调用这个类中的方法完成载入前台页面的操作和载入显示的数据

class Base {
	//创建一个属性默认为空数组，用来接收传来的数据保存分配变量的属性。
	private $data = [];
	//组合引入的模板路径
	private $template;


//	 分配变量
//	创建with方法来接收传来的数据并且返回给当前对象触发__tostring完成加载
	public function with($data){
//		将传递的数据赋值给$data
		$this->data = $data;
//		返回当前对象
		return $this;
	}


//	 制作模板

	public function make(){
		$this->template = '../app/' . APP . '/view/' . CONTROLLER . '/' . ACTION . '.php';
		//1.返回当前对象，
		//(1)返给\houdunwang\view\View里面的__callStatic
		//(2)View里面的__callStatic再返回给\app\home\controller\Entry里面的index方法(View::make())
		//(3)Entry里面的index方法又返回给\houdunwang\core\Boot里面的appRun方法，在appRun方法用了echo 输出这个对象
		//2.为了触发__toString
		return $this;
	}


//	  载入模板

	public function __toString() {
		//把键名变为变量名，键值变为变量值 相当于 $data = ['title'=>'我是文章标题'];
		extract($this->data);
		//载入模板
		include $this->template;
		//这个方法必须返回字符串
		return '';
	}
}