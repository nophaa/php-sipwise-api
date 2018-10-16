<?php

namespace Sipwise\Api;

use Sipwise\Client;

/**
 * Api interface.
 */
interface ApiInterface
{
    public function __construct(Client $client);
}
