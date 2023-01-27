<?php

namespace App\Wordle;

class Game
{
    public $wordlist = ['abuse','adore','adult','agent','anger','apple','award','basis','beach','birth','block','blood','board','brain','bread','break','brown','buyer','cause','chain','chair','chest','chief','child','china','claim','class','clock','coach','coast','court','cover','cream','crime','cross','crowd','crown','cycle','dance','death','depth','doubt','draft','drama','dream','dress','drink','drive','earth','enemy','entry','error','event','faith','fault','field','fight','final','floor','focus','force','frame','frank','front','fruit','glass','grant','grass','green','group','guide','heart','henry','horse','hotel','house','image','index','input','issue','japan','jones','judge','knife','laura','layer','level','lewis','light','limit','lunch','major','march','match','metal','model','money','month','motor','mouth','music','night','noise','north','novel','nurse','offer','order','other','owner','panel','paper','party','peace','peter','phase','phone','piece','pilot','pitch','place','plane','plant','plate','point','pound','power','press','price','pride','prize','proof','queen','radio','range','ratio','reply','right','river','round','route','rugby','scale','scene','scope','score','sense','shape','share','sheep','sheet','shift','shirt','shock','sight','simon','skill','sleep','smile','smith','smoke','sound','south','space','speed','spite','sport','squad','staff','stage','start','state','steam','steel','stock','stone','store','study','stuff','style','sugar','table','taste','terry','theme','thing','title','total','touch','tower','track','trade','train','trend','trial','trust','truth','uncle','union','unity','value','video','visit','voice','waste','watch','water','while','white','whole','woman','world','youth'];

    private $solution = null;
    private $turn = 0;
    private $isComplete = false;

    public function __construct()
    {
        $this->solution = $this->wordlist[rand(0, count($this->wordlist) -1)];
//        $this->solution = "abuse";
        //dd($this->wordlist);
    }


    public function guessRow($row): Row {
        $pspell = pspell_new("en");

        if(strlen($row) != 5 || (pspell_check($pspell, $row) === false && in_array($row, $this->wordlist)) == true) {
            throw new \Exception('Invalid word: '. $row);
        }

        $row = new Row(str_split($row));

        $answer = $row->validate($this->solution);
        $this->turn++;
        if($answer->isSolution()) {
            $this->isComplete = true;
            die('Je hebt gewonnen. Het woord was '. $this->solution. '. Je hebt er '. $this->turn . ' beurten over gedaan');
        }

        if($this->turn === 5)
        {
            die('Je hebt verloren. Het woord was: ' . $this->solution);
        }

        return $answer;
    }

    public function isComplete()
    {
        return $this->isComplete;
    }
}
