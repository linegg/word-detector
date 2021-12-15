<?php
/**
 * WordDetector
 *
 * @author    yuanzhilin
 * @since     2021/11/29
 */
namespace WD;

class WordDetector
{
    // trie
    protected $wordsTree = [];
    // will ignore invalidWord
    protected $invalidWord = [' ','-'];

    protected $replaceWord = '*';

    public function setReplaceWord($w) {
        $this->replaceWord = $w;
    }

    public function setInvalidWords(array $arr) {
        $this->invalidWord = $arr;
    }

    public function getInvalidWords() : array{
        return $this->invalidWord;
    }

    public function getWordsTree(): array{
        return $this->wordsTree;
    }

    public function __construct(){
        //nothing to do
    }

    public function buildTreeByFile(string $path) : bool{

        if(!file_exists($path)){
            return false;
        }

        $fp = fopen($path, 'r');
        $tArr = [];
        while(!feof($fp)){
            $tArr[] = fgets($fp);
        }

        fclose($fp);
        return $this->buildTree($tArr);
    }

    public function buildTree(array $wordsList) : bool{
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

        return true;
    }

    public function isIllegal($str): bool{
        List($matchTimes) = $this->search($str, 1);
        if (empty($matchTimes)) {
            return false;
        }

        return true;
    }

    /**
     * search
     *
     * @param string  $str     raw string
     * @param integer $limit   match number
     * @param boolean $replace whether to replace
     * @return array the result set
     * */
    public function search(string $str, int $limit = 0, bool $replace = false): array{
        $len = strlen($str);
        $matchWords = [];
        $strWords = [];
        $matchTimes = 0;

        $replacedStr = $str;
        // main loop,judge word by word
        if (!empty($this->wordsTree)) {
            for($i = 0;$i < $len;$i ++){
                List($matchFlag, $wLen, $matchWord, $strWord, $replacePart) = $this->subSearch($str, $i);

                if ($matchFlag) {
                    if ($replace) {
                        $replacedStr = substr_replace($replacedStr, $replacePart, $i, $wLen);
                    }

                    $i += ($wLen - 1);
                    $matchTimes ++;
                    $matchWords[] = $matchWord;
                    $strWords[] = $strWord;
                    if ($limit > 0 && $matchTimes >= $limit) {
                        break;
                    }
                }
            }
        }

        return [$matchTimes, $matchWords, $strWords, $replacedStr];
    }

    protected function subSearch($str, $i): array{
        $str = substr($str, $i);

        $strArr = str_split($str);
        $wLen = 0;

        $matchFlag = false;

        $p = &$this->wordsTree;
        $swordTemp = '';
        $strTemp = '';
        // invalid word will not be replaced
        $replace = '';

        // string loop
        foreach ($strArr as $k => $v) {
            $strTemp .= $v;

            if (in_array($v, $this->invalidWord)) {
                $wLen ++;
                $replace .= $v;
                continue;
            }

            $case = function($arg) {
                return $arg;
            };

            // can not find first word
            // if it is a English characters
            if (preg_match('/[a-zA-Z]/', $v)) {
                if (isset($p[strtoupper($v)])) {
                    $case = 'strtoupper';
                } elseif (isset($p[strtolower($v)])) {
                    $case = 'strtolower';
                } else {
                    break;
                }
            } else {
                if (!isset($p[$v])) {
                    break;
                }
            }

            $replace .= $this->replaceWord;
            $wLen ++;
            $tv = call_user_func($case, $v);
            $swordTemp .= $tv;

            if ($p[$tv] !== false) {
                $p = &$p[$tv];
            } else {
                $matchFlag = true;
                break;
            }
        }

        // sensitive world's length
        return [$matchFlag, $wLen, $swordTemp, $strTemp, $replace];
    }
}