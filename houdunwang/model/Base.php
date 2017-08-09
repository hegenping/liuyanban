<?php
namespace houdunwang\model;
//启动pdo所在空间完成数据库操作
use PDO;
//启动PDOException空间
use PDOException;
//创建一个Base类执行数据库的查看修改的一些操作
class Base {
	//保存PDO对象的静态属性
	private static $pdo = null;
	//保存表名属性
	private $table;
//		保存where
	private $where;
//	调用BASE时自动执行此方法
	public function __construct($table) {
//		执行连接数据库
		$this->connect();
		$this->table = $table;
	}


//	 链接数据库

	private function connect() {
		//如果构造方法多次执行，那么此方法也会多次执行，用静态属性可以把对象保存起来不丢失，
		//第一次self::$pdo为null，那么就正常链接数据库
		//第二次self::$pdo已经保存了pdo对象，不为NULL了，这样不用再次链接mysql了。
		if ( is_null( self::$pdo ) ) {
			try {
//				调取要连接的地址和数据库的名称
				$dsn = 'mysql:host='.c('database.db_host').';dbname=' . c('database.db_name');
//				调取要连接的用户名和密码
				$pdo = new PDO( $dsn, c('database.db_user'), c('database.db_password') );
				$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				$pdo->exec( "SET NAMES " . c('database.db_charset') );
				//把PDO对象放入到静态属性中
				self::$pdo = $pdo;
				//捕获PDO异常错误 $e 是异常对象
			} catch ( PDOException $e ) {
//				捕获这个异常对象就停止运行并输出SQL错误和这个异常错误语句
				exit( $e->getMessage() );
			}
		}

	}


//	 获取全部数据
//	创建一个get方法获取对应的所有数据
	public function get() {
//		获取对应的表的数据，将对应的表添加到获取所有数据的SQL语句中
		$sql    = "SELECT * FROM {$this->table}";
//		通过有结果集的操作执行sql语句中完成对应的数据
		$result = self::$pdo->query( $sql );
		//获得关联数组
		$data = $result->fetchAll( PDO::FETCH_ASSOC );
//		返回给当前对象
		return $data;
	}



//	 查询单条数据
	public function find($id){
//		获得主键
		$priKey = $this->getPriKey();
//		组合sql语句
		$sql = "SELECT * FROM {$this->table} WHERE {$priKey}={$id}";
		$data = $this->q($sql);
//		返回$data里面数组的值
		return current($data);
	}


	public function save($post){
		//查询当前表信息
		$tableInfo = $this->q("DESC {$this->table}");
		$tableFields = [];
		//获取当前表的字段 [title,click]
		foreach ($tableInfo as $info){
			$tableFields[] = $info['Field'];
		}

		$filterData = [];
		foreach ($post as $f => $v){
			//如果属于当前表的字段，那么保留，否则就过滤
			if(in_array($f,$tableFields)){
				$filterData[$f] = $v;
			}
		}


		//字段
		$field = array_keys($filterData);
		$field = implode(',',$field);
		//值
		$values = array_values($filterData);
		$values = '"' . implode('","',$values)  . '"';

		$sql = "INSERT INTO {$this->table} ({$field}) VALUES ({$values})";
		return $this->e($sql);
	}


//	 修改

	public function update($data){
		if(!$this->where){
			exit('update必须有where条件');
		}
		//Array
//		(
//			[title] => 标题,
//			[click] => 100,
//		)
		$set = '';
		foreach ( $data as $field => $value ) {
			$set .= "{$field}='{$value}',";
		}
		$set = rtrim($set,',');
		$sql = "UPDATE {$this->table} SET {$set} WHERE {$this->where}";
		return $this->e($sql);
	}



//	  where条件

	public function where($where){
		$this->where = $where;
		return $this;
	}


//	 摧毁数据

	public function destory(){
		if(!$this->where){
			exit('delete必须有where条件');
		}
		$sql = "DELETE FROM {$this->table} WHERE {$this->where}";
		return $this->e($sql);
	}



//	 获得主键

	private function getPriKey(){
		$sql = "DESC {$this->table}";
		$data = $this->q($sql);
		//主键
		$primaryKey = '';
		foreach ($data as $v){
			if($v['Key'] == 'PRI'){
				$primaryKey = $v['Field'];
				break;
			}
		}
		return $primaryKey;
	}




//	  执行有结果集的操作

	public function q( $sql ) {
		try {
//			执行从Model 里传来的sql语序
			$result = self::$pdo->query( $sql );
			return $result->fetchAll( PDO::FETCH_ASSOC );
			//捕获PDO异常错误 $e 是异常对象
		} catch ( PDOException $e ) {
//			如果捕获这个异常对象就停止运行并输出SQL错误和这个异常错误语句
			exit( "SQL错误：" . $e->getMessage() );
		}
	}


//	  执行没有结果集的操作

	public function e( $sql ) {
		try {
			//            执行从Model 里传来的sql语序
			$afRows = self::$pdo->exec( $sql );
			return $afRows;
			//捕获PDO异常错误 $e 是异常对象
		} catch ( PDOException $e ) {
//			如果捕获这个异常对象就停止运行并输出SQL错误和这个异常错误语句
			exit( "SQL错误：" . $e->getMessage() );
		}
	}
}