--TEST--
Weak\Reference - dump representation of extended reference class
--SKIPIF--
<?php if (!extension_loaded("weak")) print "skip"; ?>
--FILE--
<?php

/** @var \Testsuite $helper */
$helper = require '.testsuite.php';

$obj = new stdClass();

class ExtendedReferenceTrackingDtor extends \Weak\Reference
{
    public function __destruct()
    {
        echo 'Dtoring ', get_class($this), PHP_EOL;
    }

}

$wr = new ExtendedReferenceTrackingDtor($obj);

$wr = null;

$helper->line();
?>
EOF
--EXPECT--
Dtoring ExtendedReferenceTrackingDtor

EOF
