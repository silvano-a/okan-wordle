<?php

namespace App\Wordle;

class WordleGameSolver
{
    /** @var Game */
    private $game;

    private $guesses = [];

    private $possibleWords = [];

    public function start(Game $game)
    {
        $this->game = $game;
        $this->possibleWords = $game->wordlist;

        $row = $this->game->guessRow("adore");

        $this->guesses []= $row;

        while (true) {
            $this->analyseWords($row);
            $row = $this->game->guessRow(reset($this->possibleWords));
            $this->guesses []= $row;
        }


        //dd($this->guesses);


    }

    private function analyseWords(Row $row)
    {
        foreach ($row->getArrayWord() as $key => $arrayLetter) {
            if ($arrayLetter['position'] === Row::POSITION_CORRECT) {
                $this->filterIsCorrectLetter($arrayLetter['letter'], $key);
            }

            if ($arrayLetter['position'] === Row::POSITION_NONE) {
                $this->filterIsNoneLetter($arrayLetter['letter'], $key);
            }

            if($arrayLetter['position'] === Row::POSITION_WRONG) {
                $this->filterHasCorrectLetter($arrayLetter['letter']);
                $this->filterIsNoneLetter($arrayLetter['letter'], $key);
            }
        }
    }

    private function filterHasCorrectLetter(string $letter) {
        $this->possibleWords = array_filter($this->possibleWords, function (string $word) use ($letter) {
            return strpos($word, $letter) !== false;
        });
    }

    private function filterIsCorrectLetter(string $letter, int $position): void
    {
        $this->possibleWords = array_filter($this->possibleWords, function (string $word) use ($letter, $position) {
            return $word[$position] === $letter;
        });
    }

    private function filterIsNoneLetter(string $letter, int $position)
    {
        $this->possibleWords = array_filter($this->possibleWords, function (string $word) use ($letter, $position) {
            return $word[$position] !== $letter;
        });
    }
}
