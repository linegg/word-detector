<?php
/**
 *
 * @author    yuanzhilin
 * @since     2021/11/29
 */

require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'WordDetector.php';

$content = 'hello a-bxx,you are a efg!';
$badWords = ['ab','efg'];

$wd = new \Linegg\WordDetector\WordDetector();
$wd->buildTree($badWords);
$isIllegal = $wd->isIllegal($content);
List($matchTime, $matchWords, $strWords, $replaceStr) = $wd->search($content, 0, true);

echo "match times:{$matchTime}.\n";
echo "match words:\n";
echo print_r($matchWords, true);
echo "raw str:\n";
echo print_r($strWords, true);
echo "after replaced:{$replaceStr}\n";

