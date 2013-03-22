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
    ]
);

$export->get->bar = new Export(
    $export,
    [
        'className' => function() {
            return get_class($this->parent()->masterObj);
        },

        'calledClass' => function() {
            return get_called_class();
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


var_dump(assert(
    '$export->get->name() === "dieselcode"',
    'Name should equal dieselcode'
));

$export->set->name('evilwalrus');

var_dump(assert(
    '$export->get->name() === "evilwalrus"',
    'Name should equal to evilwalrus'
));

var_dump(assert(
    '$export->get->bar->className() === "Foo"',
    'Class name should be one of Foo'
));

var_dump(assert(
    '$export->get->bar->calledClass() === "Export"',
    'Called class should be one of Export'
));

echo PHP_EOL . `php -v`;


?>