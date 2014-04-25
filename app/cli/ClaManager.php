<?php

namespace app\cli;

echo '
                             _._._                       _._._
                            _|   |_                     _|   |_
                            | ... |_._._._._._._._._._._| ... |
                            | ||| |  o CLA FRAMEWORK o  | ||| |
                            | """ |  """    """    """  | """ |
                       ())  |[-|-]| [-|-]  [-|-]  [-|-] |[-|-]|  ())
                      (())) |     |---------------------|     | (()))
                     (())())| """ |  """    """    """  | """ |(())())
                     (()))()|[-|-]|  :::   .-"-.   :::  |[-|-]|(()))()
                     ()))(()|     | |~|~|  |_|_|  |~|~| |     |()))(()
                        ||  |_____|_|_|_|__|_|_|__|_|_|_|_____|  ||
                     ~ ~^^ @@@@@@@@@@@@@@/=======\@@@@@@@@@@@@@@ ^^~ ~
                          ^~^~                                ~^~^
                                    All you need is CLA
';

use cla\cli\ClaManagerDefault;

//./pux compile -o /var/www/cla/app/system/cache/routes.php /var/www/cla/app/system/config/routes.php

class ClaManager extends ClaManagerDefault {

    function  __construct($argv) {
        parent::__construct($argv);
    }
    
    public static $hello_world = "demo method";
    public function hello_world() {
        echo 'hello world';
    }
}



?>
