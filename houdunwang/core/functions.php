<?php

//打印函数
//创建一个P函数，完成操作
function p($var){
//	原样输出要查看的数据
	echo '<pre style="background: #ccc;padding: 10px;border-radius: 5px;">';
//	查看数据信息
	print_r($var);
	echo '</pre>';
}

//建一个c函数连接数据库操作
function c($path){
//	调用C函数传过来的参数把它转为数组，完成载入数据库参数的文件操作
	$arr = explode('.',$path);


//	引入数据库参数所在文件，将内容赋值予$config
	$config = include '../system/config/' . $arr[0] . '.php';
//	看返回的数组对应键值是否存在，存在就直接使用，不存在则返回null
	return isset($config[$arr[1]]) ? $config[$arr[1]] : NULL;
}






