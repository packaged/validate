<?php

namespace Packaged\Validate;

trait DatasetValidatorTrait
{
  protected $_data = [];

  public function setData(array $data): self
  {
    $this->_data = $data;
    return $this;
  }

  public function getData(): array
  {
    return $this->_data;
  }
}
