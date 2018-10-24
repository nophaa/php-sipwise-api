<?php
namespace Sipwise\Exception;

use Sipwise\Exception\ExceptionInterface;

class BadMethodCallException extends \BadMethodCallException implements ExceptionInterface
{
}

