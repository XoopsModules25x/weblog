<?php
/**
 * Created by PhpStorm.
 * User: Mamba
 * Date: 8/4/13
 * Time: 12:30 PM
 */

return preg_replace(
    array(
         '/%b/e',
         '/%B/e',
         '/%C/e',
         '/%([-#]?)d/e',
         '/%D/e',
         '/%e/e',
         '/%([-#]?)H/e',
         '/%([-#]?)I/e',
         '/%([-#]?)m/e',
         '/%([-#]?)M/e',
         '/%n/',
         '/%p/e',
         '/%R/e',
         '/%([-#]?)S/e',
         '/%t/',
         '/%T/e',
         '/%x/e',
         '/%X/e',
         '/%y/e',
         '/%Y/',
         '/%%/'
    ),
    array(
         '$this->strftime(Horde_Nls::getLangInfo(constant(\'ABMON_\' .
         (int)$this->_month)))',
         '$this->strftime(Horde_Nls::getLangInfo(constant(\'MON_\' .
         (int)$this->_month)))',
         '(int)($this->_year / 100)',
         'sprintf(\'%\' . (\'$1\' ? \'\' : \'02\') . \'d\', $this->_mday)',
         '$this->strftime(\'%m/%d/%y\')',
         'sprintf(\'%2d\', $this->_mday)',
         'sprintf(\'%\' . (\'$1\' ? \'\' : \'02\') . \'d\', $this->_hour)',
         'sprintf(\'%\' . (\'$1\' ? \'\' : \'02\') . \'d\', $this->_hour == 0 ?
         12 : ($this->_hour > 12 ? $this->_hour - 12 : $this->_hour))',
         'sprintf(\'%\' . (\'$1\' ? \'\' : \'02\') . \'d\', $this->_month)',
         'sprintf(\'%\' . (\'$1\' ? \'\' : \'02\') . \'d\', $this->_min)',
         "\n",
         '$this->strftime(Horde_Nls::getLangInfo($this->_hour < 12 ? AM_STR :
         PM_STR))',
         '$this->strftime(\'%H:%M\')',
         'sprintf(\'%\' . (\'$1\' ? \'\' : \'02\') . \'d\', $this->_sec)',
         "\t",
         '$this->strftime(\'%H:%M:%S\')',
         '$this->strftime(Horde_Nls::getLangInfo(D_FMT))',
         '$this->strftime(Horde_Nls::getLangInfo(T_FMT))',
         'substr(sprintf(\'%04d\', $this->_year), -2)',
         (int)$this->_year,
         '%'
    ),
    $format);


$count = 100;

$start = microtime(true);
for ($i = 0; $i < $count; $i++) {
     $str = "abc";
     $str = preg_replace_callback('/a/', function($a) { return strtoupper($a[0]); }, $str);
     $str = preg_replace_callback('/b/', function($a) { return strtoupper($a[0]); }, $str);
     $str = preg_replace_callback('/c/', function($a) { return strtoupper($a[0]); }, $str);
     $str = preg_replace_callback('/a/', function($a) { return strtolower($a[0]); }, $str);
     $str = preg_replace_callback('/b/', function($a) { return strtolower($a[0]); }, $str);
     $str = preg_replace_callback('/c/', function($a) { return strtolower($a[0]); }, $str);
}
echo "Completed in " . (microtime(true) - $start) . " Seconds\n";

$start = microtime(true);
for ($i = 0; $i < $count; $i++) {
     $str = "abc";
     $str = preg_replace(array(
           '/a/e',
             '/b/e',
             '/c/e',
             '/a/e',
             '/b/e',
             '/c/e',
         ),
         array(
             'strtoupper(\'$1\')',
             'strtoupper(\'$1\')',
             'strtoupper(\'$1\')',
             'strtolower(\'$1\')',
             'strtolower(\'$1\')',
             'strtolower(\'$1\')',
         ),
         $str
     );
}
echo "Completed in " . (microtime(true) - $start) . " Seconds\n";


$tagName = strtolower($node->tagName);
if ($callback = $this->handlers[$tagName]) {
    $dump .= call_user_func(
        $callback,
        $tagName,
        $this->wash_attribs($node),
        $this->dumpHtml($node, $level),
        $this
    );
}