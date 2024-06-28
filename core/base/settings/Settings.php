<?php

namespace core\base\settings;


class Settings{

    static private $_instance;
    
    private $templateArr = [
        'text'=>['name1'=>1]
    ];
    // Массив маршрутов 
    private $routes = [
        'admin'=> [
            'name' => 'admin',
            'path' => 'core/admin/controller/',
            // изменять вид пути на пользовательский, красивый
            'hrUrl' => false
        ],
        'settings' => [ 
            'path'=> 'core/base/settings/'
        ],
        'plugins' => [
            'path' =>'core/plugins/',
            'hrUrl' => false
        ],
        'user' => [
            'path' => 'core/user/controllers',
            'hrUrl' => true,
            'routes' => [

            ]
        ],
        'default' => [
            'controller' => 'indexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];


    

    



    // Констуктор и клон помогают с приватным доступом позволяют избежать обращений к данному классу и создания копии из вне
    private function __construct(){

    }
    private function __clone(){

    }

    static public function get ($property){
        return self::instance()->$property;

    }
    public function __get($property){
        return $this->$property = null;
 }

// Объявление свойств и методов класса статическими позволяет обращаться к ним без создания экземпляра класса. 
    static public function instance(){
        // self:: - ключевое свойство для обращения к статическим свойствам и методам внутри себя самого или к константам
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
        // new self() или new self - создание экземпляра класса внутри себя самого
        return self::$_instance = new self;
    }










     


    public function clueProprties($class){

        $baseProperties = [];

        foreach($this as  $name => $item){
            $property = $class::get($name);
// Если массивом является свойство текущего класса и массивом является свойство класса ShopSettings, 
            if(is_array($property) && is_array($item)){
// то мы склеиваем эти массивы
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                
            }
// Если запрашиваемого свойства нет в ShopSettings  то мы его берем из базового класса Settings
            if(!$property) $baseProperties[$name] = $this->$name;
        }

            return $baseProperties;
    }

    public function arrayMergeRecursive(){
        $arrays = func_get_args();
        $base = array_shift($arrays);
        foreach ($arrays as $array){
            foreach($array as $key => $value){
//идем по массиву ShopSettings рекурсивно 
            if (is_array($value) && isset($base[$key]) &&is_array($base[$key])){ 
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                }else{   
                    if(is_int($key)){
// при отсутсвии в базовом массиве нашего значения, добавляем его
                        if(!in_array($value, $base)) array_push($base, $value);
                        continue;
                    }
    // в случае если строка, заменяем базовое значение на значение плагина
    // при отсутствии значения так же добавляем его в плагин 
                    $base[$key] = $value;
                }

            }
        }
 
        return $base;
    }
    
}


