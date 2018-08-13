<?php

class Parser {
    private $html;
    private $mathes;
    //private $requ = 'https://yandex.ru/search/?text='; для настоящих запросов
    private $requ = '123.htm';
    public $posForNextSearch = 0;
    function __construct($keyWord) {
        //$this->requ = $this->requ.$keyWord; для настоящих запросов
        $this->html = file_get_contents($this->requ);
    }

    function getList(){
        $reg = '/\<div class="organic__url-text"\>.*\<\/div\>/imU';
        preg_match_all($reg, $this->html, $this->mathes);
        return $this->mathes;
    }

    function searchByClass($tag, $className){
       /* $reg = '/<div[^\<\>]class=["\']organic__url-text["\'].*?>/im';
        preg_match_all($reg, $this->html, $this->mathes);
        return $this->mathes;*/
       $fullTag = '<'.$tag.' class="'.$className.'">';
       if($this->posForNextSearch > 0){
           if(strpos(substr($this->html, $this->posForNextSearch), $fullTag)) {
               $pos = $this->posForNextSearch + strpos(substr($this->html, $this->posForNextSearch), $fullTag);
           } else {
               return null;
           }
       } else {
           if(!$pos = strpos($this->html, $fullTag)){
               return null;
           }
       }
       $length = 0;
       $substr = substr($this->html, $pos);
       $tmpSubstr = substr($substr, 4); $length += 4; //Чтоб не натыкаться каждый раз на первый тэг
       $tmpPosOpen; $tmpPosClose;
       $balance = 1;
       while($balance != 0){
           $tmpPosOpen = strpos($tmpSubstr, '<div');
           $tmpPosClose = strpos($tmpSubstr, '</div')+strlen($tag)+3;
           if($tmpPosOpen < $tmpPosClose){
               $balance++;
               $length += $tmpPosOpen + 4;
               $tmpSubstr = substr($tmpSubstr, $tmpPosOpen + 4);
           } else {
               $balance--;
               $length += $tmpPosClose;
               $tmpSubstr = substr($tmpSubstr, $tmpPosClose);
           }
           if($balance > 1000 || $balance < -1000){
               return null;
           }
       }
       $this->posForNextSearch = $pos + $length;
       return substr($substr, 0, $length);
    }

    function getAllElementsFromArray($array){
        foreach ($array as $element){
            if(is_array($element)){
                $this->getAllElementsFromArray($element);
            } else {
                echo $element;
            }
        }
    }

    function getHtml(){
        return $this->html;
    }

    function getAdress(){
        return $this->requ;
    }
}

?>