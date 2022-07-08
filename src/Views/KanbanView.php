<?php

namespace Performing\Taskday\Status\Views;

use Taskday\Base\View;

class KanbanView extends View
{
  public $types = [];

  public function add(string $type, array $options) 
  {
      $this->types[$type] = $options;
  }

  public function toArray() 
  {
      return array_merge(parent::toArray(),[
          'types' => $this->types
      ]);
  }
}