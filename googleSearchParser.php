<?php
/**
* GoogleSearchParser  -  Easy get google search data.
*
* @author       Author: Lee. (https://github.com/ZiJie-Li)
* @version      1.0
*/


require_once("simple_html_dom.php");

class GoogleSearchParser {

	public static $variable = array();
	public static $resultData = null;

	public function __construct($config = array()) {
		if (is_array($config)) {
			foreach ($config as $key => $value) {
				self::$variable[$key] = $value;
			}
		}

		if ( ! isset(self::$variable['language'])) $this->setLanguage();
		if ( ! isset(self::$variable['page'])) $this->setPage();
		if ( ! isset(self::$variable['format'])) $this->setFormat();
	}

	public function setkeyword($keyword = "") {
		if (is_string($keyword)) {
			self::$variable['keyword'] = $keyword;
		}
		elseif (is_array($keyword)) {
			foreach ($keyword as $num => $val) {
				if ($num > 0) self::$variable['keyword'] .= '+' . $val;
				else  self::$variable['keyword'] = $val;
			}
		}
		return $this;
	}

	public function setLanguage($language = "zh-TW") {
		self::$variable['language'] = $language;
		return $this;
	}

	public function setPage($page = 1) {
		//google 每10筆為一頁 one page of ten data
		self::$variable['page'] = ($page-1)*10;
		return $this;
	}

	public function setFormat($format = "json") {
		self::$variable['format'] = $format;
		return $this;
	}

	public function search() {

		if (empty(self::$variable['keyword'])) {
			self::$resultData = null;
			return $this;
		}

		$url = 'https://www.google.com.tw/search?';
		if ( ! empty(self::$variable['language'])) $url .= 'hl='.self::$variable['language'].'&';
		if ( ! empty(self::$variable['page'])) $url .= 'start='.self::$variable['page'].'&';
		$url .= 'q='.self::$variable['keyword'];

		$data = array();
		$html = file_get_html($url);

		$num = self::$variable['page'];

		foreach($html->find('div.g') as $key => $g) {
			if ( ! empty($g->find('h3.r', 0)) and ! empty($g->find('div.s', 0))) {
				if ( empty($g->find('h3.r', 0)->find('a', 0)) ) break;
				$url = urldecode($g->find('h3.r', 0)->find('a', 0)->href);
				if (strpos($url, "/url?q=") === false) break;
				$url = str_replace('/url?q=', "", $url);
				$url = substr($url, 0, strpos($url, "&amp;sa="));

				if ($url) {
					$data[$num]['title'] = $g->find('h3.r', 0)->find('a', 0)->plaintext;
					$data[$num]['url'] = $url;
					$data[$num]['cache_url'] = $g->find('div.s', 0)->find('cite', 0)->plaintext;
					$data[$num]['description'] = $g->find('div.s', 0)->find('span.st', 0)->plaintext;

					$num ++;
				}
				else break;
			}
		}

		self::$resultData = $data;

		return $this;
	}

	public function get() {
		return self::$resultData;
	}

	public function getSearch() {
		$this->search();
		return self::$resultData;
	}

	private function add_http($url) {
		if (strpos($url, "http") !== false) {
			return $url;
		}
		else {
			return 'http://' . $url;
		}
	}

	private function get_domain($url) {
		$pUrl = parse_url($url);
		if (!isset($pUrl["host"])) return false;
		if (!(gethostbyname($pUrl["host"]) == $pUrl["host"])) return $pUrl["host"];
		else return false;
	}

	private function simplifyUrl($url) {
		$url = str_replace("http://", "", $url);
		$url = str_replace("https://", "", $url);
		$url = preg_replace('/\s(?=)/', '', $url);
		$url = strtolower(trim($url));
		if (strpos("/", substr($url, -1)) !== false) $url = substr_replace($url, "", -1);
		$url = trim($url, "/");
		return $url;
	}

	private function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }
}

?>




