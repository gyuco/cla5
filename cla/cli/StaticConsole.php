<?php

namespace cla\cli;

class StaticConsole extends Console {

	protected static $cmd_not_desc = "no info";
	protected static $cmd_not_found = "Error: comando desconocido o incorrecto.\n";
	protected static $arg_err_1 = "Error: los parametros tienen que ser una array 2D.\n";
	protected static $arg_err_2 = "Error: se esperaba count parametros ";
	protected static $arg_err_3 = "Error: se espraba una clase";

	protected static $quit = false;
	
	public static function Start($class) {
            while (!self::$quit){
                $a = rtrim(Console::GetLine());
                $cmd = preg_split('/[\s,]*\"([^\"]+)\"[\s,]*|[\s,]+/', $a, -1, PREG_SPLIT_NO_EMPTY| PREG_SPLIT_DELIM_CAPTURE );
                if (method_exists($class, @$cmd[0])){
                        call_user_func(array($class, $cmd[0]), $cmd );
                } else {
                        self::error(self::$cmd_not_found); self::help($class);
                }
            }
      }

	public static function quit(){
            echo "\narrivederci e forza ";
            echo Console::format_msg('J', 'bold|bg_white|black');
            echo Console::format_msg('U', 'bold|bg_black|white');
            echo Console::format_msg('V', 'bold|bg_white|black');
            echo Console::format_msg('E', 'bold|bg_black|white');
            echo Console::format_msg('N', 'bold|bg_white|black');
            echo Console::format_msg('T', 'bold|bg_black|white');
            echo Console::format_msg('U', 'bold|bg_white|black');
            echo Console::format_msg('S', 'bold|bg_black|white');
            echo "\n";
            self::$quit = true;
	}
	
	public static function chek_arg($arg, $min, $max = -1){
            if ($max == -1) $max = $min;
            $cnt = count($arg) - 1;
            if ( ($min == $max)&&($min != $cnt) ) {self::error(self::$arg_err_2."$min - $cnt taken.\n"); return 2; };
            if ( ($cnt < $min) || ($cnt > $max) ) {self::error(self::$arg_err_2."$min to $max - $cnt taken.\n"); return 2; };
            if (!self::array_is2D($arg)) {self::error(self::$arg_err_1); return  1; };

            return 0;
        }

	public static function help($class){
		if(is_object($class)){
  			$class = get_class($class);
		}

		$mths = get_class_methods($class);
		$vars = get_class_vars($class);
		$desc = array_intersect($mths, array_keys($vars) );
		$udesc = array_diff($mths, array_keys($vars) );

		foreach ($desc as $v){
			$print[$v] = $vars[$v];
		}
		
		foreach ($udesc as $v){
			$print[$v] = self::$cmd_not_desc;
		}

		//ksort($print);


		echo "\n═══════════════════════════════════════════MENU══════════════════════════════════════════════════════════\n\n";
			foreach ($print as $k => $v){
				$space_dots = null;
				if ($k != '__construct') {
					$c = strlen($k);
					$c1 = strlen($v);
					$tot = $c + $c1;
					$dots = 84 - $tot;
					$space_dots = self::dottize($dots);
					echo "\t".Console::format_msg($k, "bold")." ".$space_dots.Console::format_msg($v, "green")."\n";
				}
            }
        echo "\n═════════════════════════════════════════════════════════════════════════════════════════════════════════\n";
		}

		protected static function dottize($dots) {
			$space_dots = '';
			for ($i=0;$i<$dots;$i++) {
				$space_dots = $space_dots.'.';
			}
			return $space_dots;
		}
		
	protected static function array_is2D($array) {
		return is_array($array) ? count($array)===count($array, COUNT_RECURSIVE) : -1;
	}

	protected static function error($msg){
		echo $msg;
	}
	
}

?>
