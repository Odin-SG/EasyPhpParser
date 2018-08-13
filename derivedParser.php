<?php
require_once 'parser.php';

class DerivedParser extends Parser {
    protected $page = 0;
    private $tmpPage;

    public function __construct($keyWord, $page = 0, $maxNote = 0){
        parent::__construct($keyWord, $maxNote);
        if($page > 0) {
            //Генерируем адрес страницы в зависимости от номера страницы
            $this->requ = '123' . '-' . $page . '.htm';
            $this->tmpPage = $this->page;
        }
    }

    function searchMorePage($tag, $otherPartsTag){
        $elementForReturn = '';
        while($element = parent::search($tag, $otherPartsTag)){
            $elementForReturn = $elementForReturn.$element;
        }
        $this->tmpPage++;
        if($this->tmpPage > 0) {
            $this->requ = '123' . '-' . $this->tmpPage . '.htm';
            if(file_exists($this->requ)){
                $this->html = file_get_contents($this->requ);
                $this->posForNextSearch = 0;
            }
        }
        return $elementForReturn;
    }
}
?>