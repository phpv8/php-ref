--TEST--
Weak\Reference - clone reference
--SKIPIF--
<?php if (!extension_loaded("weak")) print "skip"; ?>
--FILE--
<?php

require '.stubs.php';

use WeakTests\ExtendedReference;

use function \Weak\{
    weakrefcount,
    weakrefs
};

/** @var \Testsuite $helper */
$helper = require '.testsuite.php';

$obj = new \stdClass();


$notifier = function (Weak\Reference $ref) use ($helper) {
    echo 'Notified: ';
    $helper->dump($ref);
};

$wr = new ExtendedReference($obj, $notifier, [42]);

$helper->dump($wr);
$helper->line();

$wr2 = clone $wr;

$helper->dump($wr2);
$helper->line();


?>
EOF
--EXPECT--
object(WeakTests\ExtendedReference)#4 (2) refcount(3){
  ["test":"WeakTests\ExtendedReference":private]=>
  array(1) refcount(2){
    [0]=>
    int(42)
  }
  ["referent":"Weak\Reference":private]=>
  object(stdClass)#2 (0) refcount(2){
  }
}

object(WeakTests\ExtendedReference)#5 (2) refcount(3){
  ["test":"WeakTests\ExtendedReference":private]=>
  array(1) refcount(3){
    [0]=>
    int(42)
  }
  ["referent":"Weak\Reference":private]=>
  object(stdClass)#2 (0) refcount(2){
  }
}

EOF
