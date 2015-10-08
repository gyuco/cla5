<?php 

namespace lib\services;

class Assets {

    private $assets_path;
    private $js_position;
	private $collections = array();
    
	private $css = array();
	private $js = array();

	function __construct(array $options = array()) {
		if($options) {
			$this->config($options);
        }
	}

	public function config(array $config) {

		if(isset($config['assets_path'])) {
			$this->assets_path = $config['assets_path'];
        }
		if(isset($config['assets_path'])) {
			$this->js_position = $config['js_position'];
        }
        if(isset($config['collections']) and is_array($config['collections'])) {
			$this->collections = $config['collections'];
        }

		return $this;
	}

	public function add($asset) {
		if(is_array($asset)) {
			foreach($asset as $a) {
				$this->add($a);
            }
		} elseif(isset($this->collections[$asset])) {
			$this->add($this->collections[$asset]);
		} else {
			$info = pathinfo($asset);
			if(isset($info['extension'])) {
				$ext = strtolower($info['extension']);
				if($ext == 'css') {
					$this->addCss($asset);
                } elseif($ext == 'js') {
					$this->addJs($asset);
                } 
			}
		}

		return $this;
	}

	public function addCss($asset) {
		if(is_array($asset)) {
			foreach($asset as $a) {
				$this->addCss($a);
            }

			return $this;
		}

		if( ! $this->isRemoteLink($asset)) {
			$asset = $this->buildLocalLink($asset, $this->assets_path);
        }
		if( ! in_array($asset, $this->css)) {
			$this->css[] = $asset;
        }

		return $this;
	}

	public function addJs($asset) {
		if(is_array($asset)) {
			foreach($asset as $a) {
				$this->addJs($a);
            }

			return $this;
		}

		if( ! $this->isRemoteLink($asset)) {
			$asset = $this->buildLocalLink($asset, $this->assets_path);
        }
		if( ! in_array($asset, $this->js)) {
			$this->js[] = $asset;
        }

		return $this;
	}

    public function renderDom($template) {
        $this->css($template);
        $this->js($template);
    }
	public function css($template) {
		if( ! $this->css) {
			return null;
        }

		foreach($this->css as $file) {
            $template->appendHeadElement('link', array('rel'=>'stylesheet', 'href'=>$file, 'type'=>'text/css') );
        }
	}

	public function js($template) {
		if( ! $this->js) {
			return null;
        }

		foreach($this->js as $file) {
            if ($this->js_position == 'head') {
                $template->appendHeadElement('script', array('src'=>$file, 'type'=>'text/javascript') );
            }
            else {
                $template->appendElement('body', 'script', array('src'=>$file, 'type'=>'text/javascript') );
            }
        }
	}

	public function registerCollection($collectionName, Array $assets) {
		$this->collections[$collectionName] = $assets;
		return $this;
	}

	public function reset() {
		return $this->resetCss()->resetJs();
	}

	public function resetCss() {
		$this->css = array();
		return $this;
	}

	public function resetJs() {
		$this->js = array();

		return $this;
	}

	private function buildBuffer(array $links) {
		$buffer = '';
		foreach($links as $link) {
			if($this->isRemoteLink($link)) {
				if('//' == substr($link, 0, 2)) {
					$link = 'http:' . $link;
                }
			}
			else {
				$link = $this->public_dir . DIRECTORY_SEPARATOR . $link;
			}

			$buffer .= file_get_contents($link);
		}

		return $buffer;
	}

	private function buildLocalLink($asset, $dir) {
		$package = $this->assetIsFromPackage($asset);

		if($package === false) {
			return $dir . '/' . $asset;
        }
		return '/packages/' . $package[0] . '/' .$package[1] . '/' . ltrim($dir, '/') . '/' .$package[2];
	}

	private function assetIsFromPackage($asset) {
		if(preg_match('{^([A-Za-z0-9_.-]+)/([A-Za-z0-9_.-]+):(.*)$}', $asset, $matches)) {
			return array_slice($matches, 1, 3);
        }
		return false;
	}

	private function isRemoteLink($link) {
		return ('http://' == substr($link, 0, 7) or 'https://' == substr($link, 0, 8) or '//' == substr($link, 0, 2));
	}

	public function getCss() {
		return $this->css;
	}

	public function getJs() {
		return $this->js;
	}
}
