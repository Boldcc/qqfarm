<?php

/**
 * 模板操作类
 * Create by seaif@zealv.com
 */

class STemplate {
	private static $instance = null;
	private $force; //强制编译
	private $cplDir; //编译路径
	private $tplId; //当前模板
	private $tplDir; //模板源路径
	private $tplBak; //备用模板路径
	private $RelPath; //需补全的相对路径
	private $LangPack; //国际化语言包
	/**
	 * 单列模式入口
	 */
	public static function getInstance($args = array()) {
		if(self::$instance == null) {
			$className = __class__;
			self::$instance = new $className($args);
		}
		return self::$instance;
	}
	/**
	 * 构造函数
	 */
	private function __construct($myCFG) {
		//合并外部参数
		$defCFG = array(
			'force' => false,
			'cplDir' => 'cpl/',
			'tplId' => 'zealv',
			'tplDir' => 'tpl/',
			'tplBak' => 'tpl/default/',
			'RelPath' => array('images/'),
			'LangPack' => array()
		);
		$CFG = array_merge($defCFG, array_intersect_key($myCFG, $defCFG));
		//初始化内部参数
		foreach($CFG as $key => $val)
			$this->$key = $val;
		$this->langName = $this->LangPack ? array_shift($this->LangPack) : 0;
		//修正模板及编译路径
		$this->tplDir .= $CFG['tplId'] . '/';
		$this->cplDir .= $CFG['tplId'] . '_' . ($this->langName ? $this->langName . '_' : '');
	}
	/**
	 * 加载已编译模板或返回其路径
	 * @param string $name 源模板名称
	 * @param string $cName 编译模板名称
	 * @param array  $vars 待导入的变量表
	 */
	public function show($name, $cName = '', $vars = array()) {
		$cplFile = $this->getCFile($name, $cName, $vars);
		if($cplFile) {
			extract($GLOBALS, EXTR_SKIP);
			extract($vars, EXTR_OVERWRITE);
			include $cplFile;
			return true;
		} else
			return false;
	}
	/**
	 * 返回已编译模板路径
	 * @param string $name 源模板名称
	 * @param string $cName 编译模板名称
	 * @param array  $vars 待导入的变量表
	 */
	public function getCFile($name, $cName = '', $vars = array()) {
		if($tplFile = $this->getTFile($name)) {
			$cName = $cName ? $cName : $name;
			$cplFile = $this->cplDir . str_replace('/', '_', $cName) . '.php';
			if($this->force || !is_file($cplFile) || filemtime($cplFile) < filemtime($tplFile)) {
				$this->vars = $vars; //待导入的变量表
				$content = file_get_contents($tplFile);
				$content = $this->compile($content);
				file_put_contents($cplFile, $content);
			}
			return $cplFile;
		} else
			return '';
	}
	/**
	 * 返回源模板路径
	 * @param string $name 源模板名称
	 */
	private function getTFile($name) {
		$tplFile = $this->tplDir . $name . '.htm';
		if(!file_exists($tplFile)) {
			$tplFile = $this->tplBak . $name . '.htm';
			if(!file_exists($tplFile)) return '';
		}
		return $tplFile;
	}
	/**
	 * 编译模板内容
	 * @param string $content 模板内容
	 */
	private function compile($content) {
		//导入变量表
		extract($GLOBALS, EXTR_SKIP);
		extract($this->vars, EXTR_OVERWRITE);
		//补全相对路径
		foreach($this->RelPath as $rep) {
			if($rep = trim($rep)) {
				if(substr($rep, -1) != '/') $rep .= '/';
				$content = str_replace($rep, $this->tplDir . $rep, $content);
			}
		}
		//替换模板标签
		$content = preg_replace(
			array(
				'/<\?php.*\?>/iUs',
				'/(\{|<!--\{):(.+)(\}-->|\})/iU',
				'/(\{|<!--\{)(if|for|foreach)\s(.+)(\}-->|\})/iU',
				'/(\{|<!--\{)elseif(.+)(\}-->|\})/iU',
				'/(\{|<!--\{)else(\}-->|\})/iU',
				'/(\{|<!--\{)\/(if|for|foreach)(\}-->|\})/iU',
				'/(\{|<!--\{)php\s(.+)(\}-->|\})/iUs',
				'/(\{|<!--\{)tpl\s(.+)(\}-->|\})/iUe',
				'/(\{|<!--\{)lang\s(.+)(\}-->|\})/iUe'
			),
			array(
				'', //忽略原生PHP代码
				'<?php echo \\2; ?>', //输出
				'<?php \\2(\\3) { ?>', //逻辑开始
				'<?php } elseif(\\2) { ?>', //逻辑
				'<?php } else { ?>', //逻辑
				'<?php } ?>', //逻辑结束
				'<?php \\2 ?>', //执行PHP代码
				'$this->_cpl("\\2")', //嵌套模板
				'$this->_lang("\\2")' //语言标签
			), 
			$content
		);
		//删除空行及行首空格
		$content = preg_replace('/^([\r\n])\s*/', '', $content);
		$content = preg_replace('/([\r\n])\s*/', '\\1', $content);
		return $content;
	}
	/**
	 * 处理嵌套模板
	 */
	private function _cpl($name) {
		if($tplFile = $this->getTFile($name)) {
			$content = file_get_contents($tplFile);
			$content = $this->compile($content);
			return "\n{$content}\n";
		}
		return $name;
	}
	/**
	 * 替换语言标签
	 */
	private function _lang($word) {
		if(array_key_exists($word, $this->LangPack)) {
			$word = $this->LangPack[$word];
		}
		return $word;
	}
}

?>