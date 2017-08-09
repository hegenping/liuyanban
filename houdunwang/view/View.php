<?php


namespace houdunwang\view;

//houdunwang\view创建类View
class View {
//	创建一个自动载入的静态方法执行一个不存在的方法时，会触发此类方法
	public static function __callStatic( $name, $arguments ) {
// 将当前空前中base类对应方法返回的对象值返回到entry类中的index方法中
		return call_user_func_array([new Base(),$name],$arguments);
	}
}