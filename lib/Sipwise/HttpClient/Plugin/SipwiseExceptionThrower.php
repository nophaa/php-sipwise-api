<?php

namespace Sipwise\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sipwise\Exception\ErrorException;
use Sipwise\Exception\RuntimeException;
use Sipwise\HttpClient\Message\ResponseMediator;

/**
 * A plugin to remember the last response.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Fabien Bourigault <bourigaultfabien@gmail.com>
 */
class SipwiseExceptionThrower implements Plugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        return $next($request)->then(function (ResponseInterface $response) {
            if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 600) {
                $content = ResponseMediator::getContent($response);
                if (is_array($content) && isset($content['message'])) {
                    if (400 == $response->getStatusCode()) {
                        $message = $this->parseMessage($content['message']);

                        throw new ErrorException($message, 400);
                    }
                }

                $errorMessage = null;
                if (isset($content['error'])) {
                    $errorMessage = $content['error'];
                    if (is_array($content['error'])) {
                        $errorMessage = implode("\n", $content['error']);
                    }
                } elseif (isset($content['message'])) {
                    $errorMessage = $this->parseMessage($content['message']);
                } else {
                    $errorMessage = $content;
                }

                throw new RuntimeException($errorMessage, $response->getStatusCode());
            }

            return $response;
        });
    }

    /**
     * @param mixed $message
     *
     * @return string
     */
    private function parseMessage($message)
    {
        $string = $message;

        if (is_array($message)) {
            $format = '"%s" %s';
            $errors = [];

            foreach ($message as $field => $messages) {
                if (is_array($messages)) {
                    $messages = array_unique($messages);
                    foreach ($messages as $error) {
                        $errors[] = sprintf($format, $field, $error);
                    }
                } elseif (is_int($field)) {
                    $errors[] = $messages;
                } else {
                    $errors[] = sprintf($format, $field, $messages);
                }
            }

            $string = implode(', ', $errors);
        }

        return $string;
    }
}
