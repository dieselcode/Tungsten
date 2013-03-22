<?php

include '../vendor/autoload.php';

class Export extends \Tungsten\Object {}

class Foo
{
	public function bar()
	{
		return 'test';
	}
}


$export = new Export(
    [
        'masterObj' => new Foo(),
        'user' => [
            'name' => 'dieselcode',
            'real' => 'Andrew',
            'omg'  => 'hax'
        ]
    ]
);

$export->get = new Export(
    $export,   // extend the base export object
    [
        'name' => function() {
            return $this->parent()->user['name'];
        },

        'realName' => function() {
            return $this->parent()->user['name'];
        },

        'omg' => function() {
            return $this->parent()->user['omg'];
        },

        'bar' => function() {
            return $this->parent()->masterObj->bar();
        },
    ]
);

$export->set = new Export(
    $export,
    [
        'name' => function ($value) {
            $this->parent()->user['name'] = $value;
        },
    ]
);

var_dump( assert('$export->get->name() === "dieselcode"', 'Name should equal dieselcode') );
$export->set->name('foo');
var_dump( assert('$export->get->name() === "foo"', 'Name should equal foo') );

echo PHP_EOL . `php -v`;

?>