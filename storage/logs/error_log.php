[2024-11-22 16:20:51] [ERROR] Call to a member function query() on null in E:\Jongapp\app\lib\classes\db_Archive.class.php on line 112
Stack trace:
#0 E:\Jongapp\app\lib\classes\db_Archive.class.php(173): db_Archive->query()
#1 E:\Jongapp\app\module\core\app.php(41): db_Archive->select()
#2 E:\Jongapp\app\lib\functions\global.func.php(212): app->{closure}()
#3 E:\Jongapp\app\module\core\app.php(50): joinData()
#4 E:\Jongapp\app\lib\classes\Application.class.php(30): app->init()
#5 E:\Jongapp\app\lib\classes\Application.class.php(19): Application->init()
#6 E:\Jongapp\app\sys.php(105): Application->__construct()
#7 E:\Jongapp\app\sys.php(33): sys::loadItem()
#8 E:\Jongapp\app\sys.php(26): sys::loadSysClass()
#9 E:\Jongapp\index.php(21): sys::app()
#10 {main}
Context:
Array
(
    [errno] => 2
    [errstr] => Undefined array key "dbType"
    [errfile] => lib\classes\db_Archive.class.php
    [errline] => 41
)

[2024-11-22 16:20:53] [ERROR] Call to a member function query() on null in E:\Jongapp\app\lib\classes\db_Archive.class.php on line 112
Stack trace:
#0 E:\Jongapp\app\lib\classes\db_Archive.class.php(173): db_Archive->query()
#1 E:\Jongapp\app\module\core\app.php(41): db_Archive->select()
#2 E:\Jongapp\app\lib\functions\global.func.php(212): app->{closure}()
#3 E:\Jongapp\app\module\core\app.php(50): joinData()
#4 E:\Jongapp\app\lib\classes\Application.class.php(30): app->init()
#5 E:\Jongapp\app\lib\classes\Application.class.php(19): Application->init()
#6 E:\Jongapp\app\sys.php(105): Application->__construct()
#7 E:\Jongapp\app\sys.php(33): sys::loadItem()
#8 E:\Jongapp\app\sys.php(26): sys::loadSysClass()
#9 E:\Jongapp\index.php(21): sys::app()
#10 {main}
Context:
Array
(
    [errno] => 2
    [errstr] => Undefined array key "dbType"
    [errfile] => lib\classes\db_Archive.class.php
    [errline] => 41
)

[2024-11-22 16:21:44] [ERROR] Call to a member function query() on null in E:\Jongapp\app\lib\classes\db_Archive.class.php on line 112
Stack trace:
#0 E:\Jongapp\app\lib\classes\db_Archive.class.php(173): db_Archive->query()
#1 E:\Jongapp\app\module\core\app.php(41): db_Archive->select()
#2 E:\Jongapp\app\lib\functions\global.func.php(212): app->{closure}()
#3 E:\Jongapp\app\module\core\app.php(50): joinData()
#4 E:\Jongapp\app\lib\classes\Application.class.php(30): app->init()
#5 E:\Jongapp\app\lib\classes\Application.class.php(19): Application->init()
#6 E:\Jongapp\app\sys.php(105): Application->__construct()
#7 E:\Jongapp\app\sys.php(33): sys::loadItem()
#8 E:\Jongapp\app\sys.php(26): sys::loadSysClass()
#9 E:\Jongapp\index.php(21): sys::app()
#10 {main}
Context:
Array
(
    [errno] => 2
    [errstr] => Undefined array key "dbType"
    [errfile] => lib\classes\db_Archive.class.php
    [errline] => 41
)

[2024-11-22 16:23:23] [ERROR] Call to a member function query() on null in E:\Jongapp\app\lib\classes\db_Archive.class.php on line 112
Stack trace:
#0 E:\Jongapp\app\lib\classes\db_Archive.class.php(173): db_Archive->query()
#1 E:\Jongapp\app\module\core\app.php(41): db_Archive->select()
#2 E:\Jongapp\app\lib\functions\global.func.php(215): app->{closure}()
#3 E:\Jongapp\app\module\core\app.php(50): joinData()
#4 E:\Jongapp\app\lib\classes\Application.class.php(30): app->init()
#5 E:\Jongapp\app\lib\classes\Application.class.php(19): Application->init()
#6 E:\Jongapp\app\sys.php(105): Application->__construct()
#7 E:\Jongapp\app\sys.php(33): sys::loadItem()
#8 E:\Jongapp\app\sys.php(26): sys::loadSysClass()
#9 E:\Jongapp\index.php(21): sys::app()
#10 {main}
Context:
Array
(
    [errno] => 2
    [errstr] => Undefined array key "dbType"
    [errfile] => lib\classes\db_Archive.class.php
    [errline] => 41
)

[2024-11-22 16:23:42] [ERROR] mysqli object is already closed in E:\Jongapp\app\lib\classes\db_Archive.class.php on line 107
Stack trace:
#0 E:\Jongapp\app\lib\classes\db_Archive.class.php(107): mysqli->query()
#1 E:\Jongapp\app\lib\classes\db_Archive.class.php(173): db_Archive->query()
#2 E:\Jongapp\app\module\core\app.php(41): db_Archive->select()
#3 E:\Jongapp\app\lib\functions\global.func.php(215): app->{closure}()
#4 E:\Jongapp\app\module\core\app.php(50): joinData()
#5 E:\Jongapp\app\lib\classes\Application.class.php(30): app->init()
#6 E:\Jongapp\app\lib\classes\Application.class.php(19): Application->init()
#7 E:\Jongapp\app\sys.php(105): Application->__construct()
#8 E:\Jongapp\app\sys.php(33): sys::loadItem()
#9 E:\Jongapp\app\sys.php(26): sys::loadSysClass()
#10 E:\Jongapp\index.php(21): sys::app()
#11 {main}
Context:
Array
(
    [errno] => 2
    [errstr] => mysqli::__construct(): (HY000/1049): Unknown database 'apps'
    [errfile] => lib\classes\db_Archive.class.php
    [errline] => 68
)

