<?php declare(strict_types=1);

namespace Parser\Contracts;


use Parser\TokenList;

interface TokenizerInterface
{
  /**
   * Получение токенов.
   *
   * @return TokenList
   */
  public function getTokens(): TokenList;
}
