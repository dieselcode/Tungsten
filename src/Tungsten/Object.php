<?php
/**
 * Tungsten Javascript-like Object for PHP
 * Copyright (c) 2013, Andrew Heebner, All rights reserved.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.
 */

namespace Tungsten;

class Object extends \ArrayObject
{

    private $parent = null;
    private $object = null;

    /**
     * Usage:
     * - create new object: new Object($propertiesAndMethods)
     * - inherit parent object: new Object($parentObject)
     * - extend parent object: new Object($parentObject, $propertiesAndMethods)
     */
    public function __construct()
    {
        $args = func_get_args();

        if (count($args) == 2 && is_object($args[0]) && is_array($args[1])) {
            $this->parent = $args[0];
            $this->object = new \ArrayObject($args[1], \ArrayObject::ARRAY_AS_PROPS);
        } elseif (count($args) == 1 && is_array($args[0])) {
            $this->object = new \ArrayObject($args[0], \ArrayObject::ARRAY_AS_PROPS);
        }

        parent::__construct(array($this->parent, $this->object), \ArrayObject::ARRAY_AS_PROPS);
    }

    public function parent()
    {
        return $this->parent->object;
    }

    public function __get($key)
    {
        $value = null;

        if ($this->object->offsetExists($key)) {
            $value = $this->object->offsetGet($key);
        } elseif (!empty($this->parent) && is_object($this->parent->object)) {
            $value = $this->parent->object->{$key};
        }

        return $value;
    }

    public function __set($key, $value)
    {
        $this->object->offsetSet($key, $value);
    }

    public function __call($method, array $args)
    {
        return is_callable($this->object->{$method}) ?
            call_user_func_array($this->object->{$method}->bindTo($this), $args) :
            null;
    }

}

?>