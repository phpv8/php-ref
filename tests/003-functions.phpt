--TEST--
Weak\functions - test functions
--SKIPIF--
<?php if (!extension_loaded("weak")) print "skip"; ?>
--FILE--
<?php

use function Weak\{
    refcounted,
    refcount,
    weakrefcounted,
    weakrefcount,
    weakrefs,
    object_handle
};

use Weak\Reference;

/** @var \Testsuite $helper */
$helper = require '.testsuite.php';


$obj1 = new stdClass();
$obj2 = new stdClass();
$obj3 = new stdClass();

$test = $obj1;

$helper->header('Test Weak\refcounted');
$helper->export_annotated('refcounted($obj1)', refcounted($obj1));
$helper->export_annotated('refcounted($obj2)', refcounted($obj2));
$helper->export_annotated('refcounted(new stdClass())', refcounted(new stdClass()));
// in zts strings are refcounted, in non-zts - not
$helper->export_annotated('refcounted(null)', refcounted(null));
$helper->export_annotated('refcounted(42)', refcounted(42));
$helper->line();

$helper->header('Test Weak\refcount');
$helper->export_annotated('refcount($obj1)', refcount($obj1));
$helper->export_annotated('refcount($obj2)', refcount($obj2));
$helper->export_annotated('refcount(new stdClass())', refcount(new stdClass()));
$helper->export_annotated('refcount(null)', refcount(null));
$helper->export_annotated('refcount(42)', refcount(42));
$helper->line();



$ref1a = new Reference($obj1);
$ref1b = new Reference($obj1);
$ref2 = new Reference($obj2);


$helper->header('Test Weak\weakrefcounted');
$helper->export_annotated('weakrefcounted($obj1)', weakrefcounted($obj1));
$helper->export_annotated('weakrefcounted($obj2)', weakrefcounted($obj2));
$helper->export_annotated('weakrefcounted($obj3)', weakrefcounted($obj3));
$helper->line();

$helper->header('Test Weak\weakrefcount');
$helper->export_annotated('weakrefcount($obj1)', weakrefcount($obj1));
$helper->export_annotated('weakrefcount($obj2)', weakrefcount($obj2));
$helper->export_annotated('weakrefcount($obj3)', weakrefcount($obj3));
$helper->line();


$helper->header('Test Weak\weakrefs');

$helper->assert('Multiple weak refs reported for object with weakrefs()', weakrefs($obj1), [$ref1a, $ref1b]);
$helper->dump(weakrefs($obj1));
$helper->line();

$helper->assert('Single weak refs reported for object with weakrefs()', weakrefs($obj2), [$ref2]);
$helper->dump(weakrefs($obj2));
$helper->line();

$helper->assert('No weak refs reported for object with weakrefs()', weakrefs($obj3), []);
$helper->dump(weakrefs($obj3));
$helper->line();


$helper->header('Test Weak\refcount');
$helper->export_annotated('object_handle($obj1)', object_handle($obj1));
$helper->export_annotated('object_handle($obj2)', object_handle($obj2));
$helper->line();

$helper->export_annotated('spl_object_hash($obj1)', spl_object_hash($obj1));
$helper->export_annotated('spl_object_hash($obj2)', spl_object_hash($obj2));
$helper->line();

?>
--EXPECTF--
Test Weak\refcounted:
---------------------
refcounted($obj1): boolean: true
refcounted($obj2): boolean: true
refcounted(new stdClass()): boolean: true
refcounted(null): boolean: false
refcounted(42): boolean: false

Test Weak\refcount:
-------------------
refcount($obj1): integer: 2
refcount($obj2): integer: 1
refcount(new stdClass()): integer: 0
refcount(null): integer: 0
refcount(42): integer: 0

Test Weak\weakrefcounted:
-------------------------
weakrefcounted($obj1): boolean: true
weakrefcounted($obj2): boolean: true
weakrefcounted($obj3): boolean: false

Test Weak\weakrefcount:
-----------------------
weakrefcount($obj1): integer: 2
weakrefcount($obj2): integer: 1
weakrefcount($obj3): integer: 0

Test Weak\weakrefs:
-------------------
Multiple weak refs reported for object with weakrefs(): ok
array(2) refcount(2){
  [0]=>
  object(Weak\Reference)#5 (1) refcount(2){
    ["referent":"Weak\Reference":private]=>
    object(stdClass)#2 (0) refcount(3){
    }
  }
  [1]=>
  object(Weak\Reference)#6 (1) refcount(2){
    ["referent":"Weak\Reference":private]=>
    object(stdClass)#2 (0) refcount(3){
    }
  }
}

Single weak refs reported for object with weakrefs(): ok
array(1) refcount(2){
  [0]=>
  object(Weak\Reference)#7 (1) refcount(2){
    ["referent":"Weak\Reference":private]=>
    object(stdClass)#3 (0) refcount(2){
    }
  }
}

No weak refs reported for object with weakrefs(): ok
array(0) refcount(2){
}

Test Weak\refcount:
-------------------
object_handle($obj1): integer: 2
object_handle($obj2): integer: 3

spl_object_hash($obj1): string: '%s'
spl_object_hash($obj2): string: '%s'
