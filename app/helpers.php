<?php
    if(!function_exists('is_menu_request')){
        function is_menu_request($menu)
        {
            if(is_array($menu)){
                  $first_menu = request()->segment(1);
                  if( array_search($first_menu, $menu)){
                        if( request()->is($first_menu) || request()->is($first_menu.'/*') ){
                              return $first_menu;
                        }
                  }
            } else if( request()->is($menu) || request()->is($menu.'/*') ){
                  return true;
            }
            return false;
        }
    }