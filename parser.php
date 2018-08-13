<?php

class Parser {
    protected $html;
    //private $requ = 'https://yandex.ru/search/?text='; для настоящих запросов. Для этого нужно прокси
    protected $requ = '123.htm';
    protected $posForNextSearch = 0;
    protected $maxNote;
    protected $currNote = 0;
    function __construct($keyWord, $maxNote = 0) {
        //$this->requ = $this->requ.$keyWord; для настоящих запросов
        $this->html = file_get_contents($this->requ);
        $this->maxNote = $maxNote;
    }

    function search($tag, $otherPartsTag){
       /*   Регулярки не плохое решение, но сложное. Можно будет доработать
        $reg = '/<div[^\<\>]class=["\']organic__url-text["\'].*?>/im';
        preg_match_all($reg, $this->html, $this->mathes);
        return $this->mathes;*/
        if($this->maxNote <= $this->currNote && $this->maxNote != 0){
            return null;
        }
       $fullTag = '<'.$tag.' '.$otherPartsTag.'>';
       if($this->posForNextSearch > 0){
           //Если совпадений больше нет то конец
           if(strpos(substr($this->html, $this->posForNextSearch), $fullTag)) {
               $pos = $this->posForNextSearch + strpos(substr($this->html, $this->posForNextSearch), $fullTag);
           } else {
               return null;
           }
       } else {
           //Если не нашёл совпадений то увы
           if(!$pos = strpos($this->html, $fullTag)){
               return null;
           }
       }
       $length = 0; //Длинна подстроки от открывающего до закрывающего тэга
       $substr = substr($this->html, $pos); //Обрезаем html документ до начала тэга
       $tmpSubstr = substr($substr, 2); $length += 2; //Чтоб не натыкаться каждый раз на первый тэг
       $tmpPosOpen; $tmpPosClose; //Сохраняем позиции открывающего и закрывающего тэга. Считаем от подстроки
       $balance = 1; //Баланс открывающих и закрывающих тэгов
       while($balance != 0){
           $tmpPosOpen = strpos($tmpSubstr, '<div'); //Позиция открывающего тэга
           $tmpPosClose = strpos($tmpSubstr, '</div')+strlen($tag)+3; /*Позиция закрывающего тэга
           + длинна названия тэга + 3 символа <,/ и>. Это важно, так как иначе в результат не войдёт закрывающий тэг*/
           if($tmpPosOpen < $tmpPosClose){ //Так как позиции тэгов получены раньше, нужно сравнить, что получено раньше
               $balance++; //+1 открывающий тэг
               $length += $tmpPosOpen + 2; //Тоже чтоб не натыкаться не предыдущий тэг
               $tmpSubstr = substr($tmpSubstr, $tmpPosOpen + 2); //Обрезаем документ чтоб искать дальше
           } else {
               $balance--;
               $length += $tmpPosClose;
               $tmpSubstr = substr($tmpSubstr, $tmpPosClose);
           }
           if($balance > 1000 || $balance < -1000){
               return null; //В идеале на каждый открывающий тэг должен быть закрывающий, но если нет, метод не сойдёт с ума
           }
       }
       $this->posForNextSearch = $pos + $length; //Сохраняем позицию для поиска следующего тэга
        $this->currNote++;
       return substr($substr, 0, $length); //Возвращаем фрагмент от открывающего тэга до закрывающего
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