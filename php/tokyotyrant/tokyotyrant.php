<?php
/**
 * PHP 版本的TokyoTyrand client
 */

/**
 * Class STokyoTyrant
 */
class TokyoTyrant {

	private $fp;
	private $option = array(
		self::OPTION_CONNECT_TIMEOUT    => 1000, //ms
		self::OPTION_RECV_TIMEOUT       => 1000, //ms
	);
	const OPTION_CONNECT_TIMEOUT    = 1;
	const OPTION_RECV_TIMEOUT       = 2;

	public function connect($host, $port) {
		if ($this->fp){
			fclose($this->fp);
		}
		$this->fp = @stream_socket_client("tcp://$host:$port", $errno, $errstr, $this->option[self::OPTION_CONNECT_TIMEOUT]/1000, STREAM_CLIENT_CONNECT);
		if (!$this->fp) {
			throw new Db_Exception($errno, $errstr);
		}
		@stream_set_timeout($this->fp, 0, $this->option[self::OPTION_RECV_TIMEOUT]);
	}

	/**
	 * 获取已连接的IP、Port信息
	 * @return string
	 */
	public function get_remote_name() {
		return @stream_socket_get_name($this->fp, true);
	}

	/**
	 * 关闭连接
	 */
	public function close() {
		if ($this->fp) @fclose($this->fp);
	}
	public function get($key) {
		$cmd = $this->t1($this->arr_cmd_name['get'], $key);
		$this->send($cmd);
		$this->sock_succ();
		return $this->sock_str();
	}
	public function put($key, $val) {
		$cmd = $this->t2($this->arr_cmd_name['put'], $key, $val);
		$this->send($cmd);
		return $this->sock_succ();
	}

	public function out($key) {
		$cmd = $this->t1($this->arr_cmd_name['out'], $key);
		$this->send($cmd);
		$this->sock_succ();
		return $this->sock_succ();
	}

	public function stat() {
		$cmd = $this->t0($this->arr_cmd_name['stat']);
		$this->send($cmd);
		$this->sock_succ();
		return $this->sock_str();
	}
	//其实是一个reset的功能
	public function list_init() {
		$cmd = $this->t0($this->arr_cmd_name['iterinit']);
		$this->send($cmd);
		return $this->sock_succ();
	}
	public function list_next() {
		$cmd = $this->t0($this->arr_cmd_name['iternext']);
		$this->send($cmd);
		try{
			$this->sock_succ();
			return $this->sock_str();
		} catch (Exception $e){
			return false;
		}
	}

	public function traverse($callback, $num = 0) {
		$this->list_init();
		$i = $num;
		while(true) {
			try{
				$key = $this->list_next();
			} catch (Exception $e){
				break;
			}
			$result = call_user_func_array($callback, array($key));
			if ($result === false) break;
			if ($num > 0 && --$i == 0) break;
		}
	}
	private function send($cmd) {
		fwrite($this->fp, $cmd);
	}
	private function recv($len) {
		if (feof($this->fp)) {
			throw new Exception('connection closed');
		}
		$result = fread($this->fp, $len);
		if ($result === false) {
			throw new Exception('recv data fail');
		}
		return $result;
	}

	public function sock_eof() {
		return feof($this->fp);
	}
	private function sock_succ() {
		$code = ord($this->recv(1));
		if ($code !== 0) {
			throw new Exception('resonse code not succ', $code);
		}
		return true;
	}
	private function sock_str() {
		return $this->recv($this->sock_len());
	}

	private function sock_len() {
		$v = unpack('N', $this->recv(4));
		return $v[1];
	}
	private function t0($code) {
		return chr($this->magic) . chr($code);
	}
	private function t1($code, $key) {
		return pack('ccN', $this->magic, (int)$code, strlen($key)).$key;
	}
	private function t2($code, $key, $value) {
		return pack('ccNN', $this->magic, (int)$code, strlen($key), strlen($value)).$key.$value;
	}
	private $magic = 0xc8;
	private $arr_cmd_name = array(
		'put' => 0x10,
		'putkeep' => 0x11,
		'putcat' => 0x12,
		'putshl' => 0x13,
		'putnr' => 0x18,
		'out' => 0x20,
		'get' => 0x30,
		'mget' => 0x31,
		'vsiz' => 0x38,
		'iterinit' => 0x50,
		'iternext' => 0x51,
		'fwmkeys' => 0x58,
		'addint' => 0x60,
		'adddouble' => 0x61,
		'ext' => 0x68,
		'sync' => 0x70,
		'vanish' => 0x71,
		'copy' => 0x72,
		'restore' => 0x73,
		'setmst' => 0x78,
		'rnum' => 0x80,
		'size' => 0x81,
		'stat' => 0x88,
		'misc' => 0x90
	);
}
