<?php declare(strict_types=1);

namespace Parser\Contracts;

interface CounterInterface
{
  /**
   * Получение счетчиков.
   *
   * @return array
   */
  public function getCounts(): array;
}
