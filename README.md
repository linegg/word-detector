# word-detector - An easy-to-use vocabulary detection class for PHP

## Introduction
Simple and easy to use sensitive word detection package, and supports ignoring English case.

## Installation
```sh
composer require linegg/word-detector
```

## A Simple Example
```php
<?php

// string to be detected
$content = 'hello a-bxx,you are a efg!';

// vocabulary array
$badWords = ['ab','efg'];

$wd = new \WD\WordDetector();

// build a trie
$wd->buildTree($badWords);

// configure negligible characters,such as' - ',' ~ '
$wd->setInvalidWords([' ','-']);

// return values includes hit times, hit words, original words and replaced strings
List($matchTime, $matchWords, $strWords, $replaceStr) = $wd->search($content, 0, true);