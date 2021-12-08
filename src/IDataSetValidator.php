<?php

namespace Packaged\Validate;

interface IDataSetValidator
{
  public function setData(array $data): self;

  public function getData(): array;
}
