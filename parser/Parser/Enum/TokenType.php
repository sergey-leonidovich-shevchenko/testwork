<?php declare(strict_types=1);

namespace Parser\Enum;

abstract class TokenType {
  public const T_END = 0;
  public const T_OPEN = 1;
  public const T_CLOSE = 2;
  public const T_TEXT = 3;
}
