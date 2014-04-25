<?php

namespace cla;

use \IteratorAggregate;
use \ArrayIterator;

class Collection implements IteratorAggregate
{
  protected $items = array();

  public function __construct($collection = null) 
  {
    if($collection instanceof Collection)
    {
        $this->addCollection($collection);
    }
    elseif(is_array($collection)) 
    {
        $this->fromArray($collection);
    }
  }

  public function getIterator()
  {
    return new ArrayIterator($this->items);
  }

  public function addCollection(Collection $collection)
  {
    foreach ($collection as $key => $item) {
      $this->add($key, $item);
    }
  }
  
  public function count()
  {
    return count($this->items);
  }

  public function contains($value)
  {
    foreach ($this->items as $item) {
      if($item === $value) {
        return true;
      }
    }
    return false;
  }
  
  private function fromArray($array)
  {
    $this->clear();
    foreach ($array as $key => $value) {
      $this->add($key, $value);
    }
  }

  public function add($key, $value)
  {
    $this->items[$key] = $value;
  }

  public function delete($key)
  {
    unset($this->items[$key]);
  }

  public function clear()
  {
    $this->items = array();
  }

  public function __get($key)
  {
    return isset($this->items[$key]) ? $this->items[$key] : null;
  }

  public function __set($key, $value)
  {
    $this->items[$key] = $value;
  }

}