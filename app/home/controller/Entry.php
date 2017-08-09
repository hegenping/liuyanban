<?php

namespace app\home\controller;
//houdunwang\core\命名空间下的Controller类
use houdunwang\core\Controller;
//houdunwang\\命名空间下的View类
use houdunwang\view\View;
//下面要使用Arc类里面的sql方法
use system\model\Arc;
//houdunwang\model\命名空间下的Model类
use houdunwang\model\Model;
//类名导入使用验证码
use Gregwar\Captcha\CaptchaBuilder;

//创建Entry类完成载入数据，模板的操作
class Entry extends Controller {

//	 默认首页
//	创建index方法来载入数据和页面的操作
	public function index(){
		//文章表数据
//		用Arc方法获取Arc表的数据传给arcData
		$arcData = Arc::get();

//		用户点击提交按钮执行下面的代码
		//增加
		if(IS_POST){
//			获得用户提交的验证码
			if(strtolower($_POST['captcha']) != strtolower($_SESSION['phrase'])){
//				如果验证码错误，就返回提示的错误语在Boot类里面echo输出
				return $this->error('验证码错误');
			}
//			在Arc寻找save方法，没有就在它父类找。
//			再return到Model然后return到这里
			Arc::save($_POST);
//			将成功的信息传到success方法中跳转地址传到setRedirect方法中然后返回，跳转到index.php
			return $this->success('添加成功')->setRedirect('index.php');
		}
		//将返回的对象返回到houdunwang/core/boot文件中的boot类中的appRun方法中，完成模板操作和显示页面
		return View::make()->with(compact('arcData'));
	}


//	 删除
//	创建一个remove方法,完成删除操作
	public function remove(){
//		用链式调用先调用where方法再调用destory方法，找到where方法return到Model类,再return到这里
		Arc::where("aid={$_GET['aid']}")->destory();
//		将删除成功的信息传给success方法，跳转地址传到setRedirect.然后返回来完成跳转到index.php
		return $this->success('删除成功')->setRedirect('index.php');
	}


//	 修改
//	创建一个update方法，完成修改操作
	public function update(){
		//        用一个变量接收要修改内容的id
		$aid = $_GET['aid'];
//			点击提交按钮执行下面代码
		if(IS_POST){
//			用链式调用先调用where方法再调用update方法
			Arc::where("aid={$aid}")->update($_POST);
//			将成功的信息传到success方法中将跳转地址传送到setRedirect.然后返回来完成跳转到index.php
			return $this->success('修改成功')->setRedirect('index.php');
		}
//		调用Arc的find方法寻找到表中这个id的所有数据
		$oldData = Arc::find($aid);
//		view类空间的base类中的make方法载入修改页面，将对应的数据载入显示修改内容
		return View::make()->with(compact('oldData'));
	}
//	验证码
	public function captcha(){
//		验证码头部
		header('Content-type: image/jpeg');
//		生成验证码
		$builder = new CaptchaBuilder();
//		创建验证码
		$builder->build();
//		输出验证码
		$builder->output();
		//把值存入到session
		$_SESSION['phrase'] = $builder->getPhrase();
	}

}