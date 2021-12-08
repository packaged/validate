<?php

namespace Packaged\Validate;

interface IDataSetValidator
{
  /**
   * @param array $data
   *
   * @return $this
   */
  public function setData(array $data);

  /**
   * @return array
   */
  public function getData(): array;
}
