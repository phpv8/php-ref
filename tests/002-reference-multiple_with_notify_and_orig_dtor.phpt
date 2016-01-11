--TEST--
Weak\Reference - multiple weak references with notifiers, original object destructor called once and after all notifiers
--SKIPIF--
<?php if (!extension_loaded("weak")) print "skip"; ?>
--FILE--
<?php

/** @var \Testsuite $helper */
$helper = require '.testsuite.php';

require '.stubs.php';

$obj = new \WeakTests\TrackingDtor();

$callback1 = function (Weak\Reference $reference) {
    echo 'Weak notifier 1 called', PHP_EOL;
};

$callback2 = function (Weak\Reference $reference) {
    echo 'Weak notifier 2 called', PHP_EOL;
};

$wr1 = new Weak\Reference($obj, $callback1);
$wr2 = new Weak\Reference($obj, $callback2);

$obj = null;

?>
EOF
--EXPECT--
WeakTests\TrackingDtor's destructor called
Weak notifier 2 called
Weak notifier 1 called
EOF
