<?php return Pux\Mux::__set_state(array(
   'routes' => 
  array (
    0 => 
    array (
      0 => true,
      1 => '#^    /product
    /(?P<id>[^/]+?)
$#xs',
      2 => 
      array (
        0 => '\\lib\\controllers\\Demo',
        1 => 'itemAction',
      ),
      3 => 
      array (
        'id' => 'product',
        'method' => 1,
        'variables' => 
        array (
          0 => 'id',
        ),
        'regex' => '    /product
    /(?P<id>[^/]+?)
',
        'tokens' => 
        array (
          0 => 
          array (
            0 => 3,
            1 => '/product',
          ),
          1 => 
          array (
            0 => 2,
            1 => '/',
            2 => '[^/]+?',
            3 => 'id',
          ),
        ),
        'compiled' => '#^    /product
    /(?P<id>[^/]+?)
$#xs',
        'pattern' => '/product/:id',
      ),
    ),
    1 => 
    array (
      0 => false,
      1 => '/',
      2 => 
      array (
        0 => '\\lib\\controllers\\Home',
        1 => 'indexAction',
      ),
      3 => 
      array (
        'id' => 'home',
        'method' => 1,
      ),
    ),
  ),
   'staticRoutes' => 
  array (
  ),
   'routesById' => 
  array (
  ),
   'submux' => 
  array (
  ),
   'id' => NULL,
   'expand' => true,
)); /* version */