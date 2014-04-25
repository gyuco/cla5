<?php

namespace cla\cli;

abstract class Console {

    public static $stdin;

    private static function GetStdin() {

        if (!self::$stdin) {
                self::$stdin = fopen('php://stdin', 'r');
        }

        return self::$stdin;
        
    }

    public static function Pause() {
            fgets(self::GetStdin(), 8192);
    }

    public static function ClearScreen() {
            echo chr(033), "cm";
    }

    public static function GetLine($buffer = 8192) {
            return fgets(self::GetStdin(), $buffer);
    }

    public static function format_msg($string,$tag='blink',$stdout=FALSE){
        # define some escaped commands code
        $codes = array( # some cool stuff
                        'reset'      => 0,   'bold'       => 1,
                        'underline'  => 4,   'nounderline'=> 24,
                        'blink'      => 5,   'reverse'    => 7,
                        'normal'     => 22,  'blinkoff'   => 25,
                        'reverse'    => 7,   'reverseoff' => 27,
                        # some foreground colors
                        'black'      => 30,  'red'        => 31,
                        'green'      => 32,  'brown'      => 33,
                        'blue'       => 34,  'magenta'    => 35,
                        'cyan'       => 36,  'grey'       => 37,
                        # Some background colors
                        'bg_black'   => 40,  'bg_red'     => 41,
                        'bg_green'   => 42,  'bg_brown'   => 43,
                        'bg_blue'    => 44,  'bg_magenta' => 45,
                        'bg_cyan'    => 46,  'bg_white'   => 47,
                      );
        if(substr_count($tag,'|')){ # parse multiple tags
          $tags = explode('|',$tag);
          $str='';
          foreach($tags as $tag){
            $str[]= isset($codes[$tag])?$codes[$tag]:30;
          }
          $str = "\033[".implode(';',$str).'m'.$string."\033[0m";
        }else{
          if( in_array($codes[$tag],array(4,5,7)) ){
            $end = "\033[2".$codes[$tag].'m';
          }else{
            $end = "\033[0m";
          }
          $str = "\033[".(isset($codes[$tag])?$codes[$tag]:30).'m'.$string.$end;
        }
        if(! $stdout)
          return $str;
        fwrite(STDOUT,"$str\n");
      }
  
}

?>
