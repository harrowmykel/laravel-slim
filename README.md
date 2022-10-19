# Laravel Slim
Enjoy the benefit of control over basic routing like laravel. e.g website/movies/23 without the bulkiness that comes with it


## How To

edit router.php

add routes to $routes array


```php
example.com/product/24
$path_0 = Router::getUrlParamInPosition(0, "index");//returns product
$path_1 = Router::getUrlParamInPosition(1, "index"); //returns 24 
 
Router::currentUrl(); //returns example.com
Router::currentUrl(["a"=>"b"]); //returns example.com?a=b

```