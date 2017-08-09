<?php
namespace houdunwang\model;
//创建一个Model类,完成连接数据库的一些删除，修改操作
class Model {
	public static function __callStatic( $name, $arguments ) {
		$className = get_called_class();
		//system\model\Arc
		//strrchr字符串截取 变成 \Arc
		//ltrim 去除左边的\ 变成 Arc
		//strtolower 变成 arc
		$table = strtolower(ltrim(strrchr($className,'\\'),'\\'));
		return call_user_func_array([new Base($table),$name],$arguments);
	}
}