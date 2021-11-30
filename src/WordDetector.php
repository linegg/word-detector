<?php
namespace WD;

/**
 * WordDetector
 *
 * @author    yuanzhilin
 * @since     2021/11/29
 * @copyright 2021 IGG Inc.
 */
class WordDetector
{
    public $wordsTree = [];

    public function __construct(){

    }

    public function buildTree($wordsList){
        foreach($wordsList as $v0){
            $x = &$this->wordsTree;

            $wordArr = str_split($v0);
            foreach($wordArr as $v1){
                if(!isset($x[$v1])){
                    // here can not be true
                    $x[$v1] = false;
                }
                $x = &$x[$v1];
            }
        }
    }

    public function search($str){
        $strArr = str_split($str);
        $strLen = mb_strlen($str);

        foreach($strArr as $k => $v){

            $wLen = $this->subSearch($str,$k);
            echo $wLen;exit();
        }
    }

    public function subSearch($str,$i): int{
        $str = substr($str,$i);
        $strArr = str_split($str);

        $wLen = 0;

        $p = &$this->wordsTree;
        foreach($strArr as $k => $v){
            $wLen ++;

            // can not find first word
            if(!isset($p[$v])){
                break;
            }

            if($p[$v] !== false){
                $p = &$p[$v];
            }
        }

        // sensitive world's length
        return $wLen;
    }
}