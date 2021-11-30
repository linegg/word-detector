<?php
/**
 *
 * @author    yuanzhilin
 * @since     2021/11/29
 * @copyright 2021 IGG Inc.
 */

require_once 'WordDetector.php';

$e = ['ab','ac'];

$x = new \WD\WordDetector();
$x->buildTree($e);
$x->search('abc');