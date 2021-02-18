<?php
 // soap --это стандарт  передачи и общения между веб службами через http используя XML
//щписание функции веб - сервера
function getStock($id){
    $stock = array(
        "1"=>100,
    "2"=>200,
    "3"=>300,
    "4"=>400,
    "5"=>500
    );
    if(isset($stok[$id])){
        $quatity = $stock[$id];
        return $quatity;
    }else{
        throw new SoapFault("Server", "несуществующий id товара");
    }
}
//отключение кеширования WSDL-документа
 ini_set("soap.wsdl_cache_enabled", "0");

//создаем soap сервер

$server = new SoapServer(
    "http:////stock."///wsdl- язык описания веб служб на базе XML
);   // документ описания службы что есть что нету что да как  расширение  документа .wsdl
$server->addFunction("getStock");
//запуск сервера
$server->handle();