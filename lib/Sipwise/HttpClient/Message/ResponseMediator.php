<?php

namespace Sipwise\HttpClient\Message;

use Psr\Http\Message\ResponseInterface;

/**
 * Utilities to parse response headers and content.
 */
class ResponseMediator
{
    /**
     * Return the response body as a string or json array if content type is application/json.
     *.
     * @param ResponseInterface $response
     *
     * @return array|string
     */
    public static function getContent(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();
        $statusCode = $response->getStatusCode();
        if (in_array($statusCode, [201,204])){
            $location_header = self::getHeader($response, 'Location');
            $created_id = basename($location_header);
            $body = ['header' => self::getHeaders($response)];
            if (!empty($created_id)){
                $body['created_id'] = $created_id;
            }
        }
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
            $content = json_decode($body, true);
            if (JSON_ERROR_NONE === json_last_error()) {
                return $content;
            }
        }

        return $body;
    }

    /**
     * Extract pagination URIs from Link header.
     *
     * @param ResponseInterface $response
     *
     * @return array|null
     */
    public static function getPagination(ResponseInterface $response)
    {
        if (!$response->hasHeader('Link')) {
            return null;
        }

        $header = self::getHeader($response, 'Link');
        $pagination = array();
        foreach (explode(',', $header) as $link) {
            preg_match('/<(.*)>; rel="(.*)"/i', trim($link, ','), $match);

            if (3 === count($match)) {
                $pagination[$match[2]] = $match[1];
            }
        }

        return $pagination;
    }
    
    /**
     * Get the value for all headers.
     *
     * @param ResponseInterface $response
     * 
     *
     * @return string|null
     */
    public static function getHeaders(ResponseInterface $response)
    {
        return $response->getHeaders();
    }

    /**
     * Get the value for a single header.
     *
     * @param ResponseInterface $response
     * @param string $name
     *
     * @return string|null
     */
    private static function getHeader(ResponseInterface $response, $name)
    {
        $headers = $response->getHeader($name);

        return array_shift($headers);
    }
}
