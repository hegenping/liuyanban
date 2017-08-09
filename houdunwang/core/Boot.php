<?php
namespace houdunwang\core;
//框架启动类
class Boot{
//    创建一个run方法完成初始化应用和执行框架的操作
	public static function run(){
//        初始化框架。内部调用静态方法也可以调用普通方法，但静态方法可以储存数据，刷新的时候就不用再次重新加载，所以读取数据比较快
//      初始化方法，即是设置好规范以及标准
		self::init();
//        执行应用.调用appRun方法执行模板和调用应用类操作
		self::appRun();

	}
//        创建一个框架appRun方法
	private static function appRun(){
//        判断是否含有$_GET['s']参数,没有$_GET['s']时，默认访问：home/entry/index
		$s=isset($_GET['s']) ? strtolower($_GET['s']):'home/entry/index';
//        把$s转化成一个数组，方便组合自动调用方法时调用对应空间的类名和调用对应的方法名
		$arr=explode('/',$s);
//        home可能是前台应用也可能是后台应用所以不是固定的，所以不能写死，可以定义一个常量，在组合模板的时候也会用到
//        1把应用比如"home"定义为常量APP
//        2在houdunwang/viem/View.php文件里的View类的make方法组合模板路径，
//        需要的应用比如：home的名字
//        3home是默认应用，有可能为admin后台应用，所以不能写死home
		define('APP',$arr[0]);
//         把controller里面的控制器类文件定义为常量，因为可能是其它作用类
		define('CONTROLLER',$arr[1]);
//            把方法定义为常量，因为方法有多种所以将$arr[2]的值存入常量中
		define('ACTION',$arr[2]);
//            组合类名，将$arr[1]的首字母改为大写
		$className ="\app\\{$arr[0]}\controller\\" . ucfirst($arr[1]);
//            自动调用控制器里的方法，因为输出对象时会触发tostring方法，所以将返回的对象输出
		echo call_user_func_array([new $className,$arr[2]],[]);
	}
//    初始化 创建一个框架init方法
	private static function init(){
//        开启session.如果开启了session执行左边即可，否则执行右边开始session
		session_id()||session_start();
//        设置区时东八区
		date_default_timezone_set("PRC");
//        定义一个常量判断是否POST提交方式
		define('IS_POST',$_SERVER['REQUEST_METHOD']=='POST'? true:false);
	}
}