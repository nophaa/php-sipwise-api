<?php

namespace Sipwise\Api\Calls\Cf;

use Sipwise\Api\AbstractApi;

/**
 * Description of Cf Mappings.
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class CfMappings extends AbstractApi
{
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        $resolver->setDefined('order_by')
            ->setAllowedTypes('order_by', 'string');

        $resolver->setDefined('order_by_direction')
            ->setAllowedTypes('order_by_direction', 'string');

        return $this->get('/api/cfmappings/', $resolver->resolve($parameters));
    }

    public function show($subscriber_id)
    {
        return $this->get('/api/cfmappings/'.$this->encodePath($subscriber_id));
    }

    public function update($subscriber_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        $resolver->setDefined('cfb')
            ->setAllowedTypes('cfb', 'array');

        $resolver->setDefined('cfna')
            ->setAllowedTypes('cfna', 'array');

        $resolver->setDefined('cfs')
            ->setAllowedTypes('cfs', 'array');

        $resolver->setDefined('cft')
            ->setAllowedTypes('cft', 'array');

        $resolver->setDefined('cft_ringtimeout')
            ->setAllowedTypes('cft_ringtimeout', 'array');

        $resolver->setDefined('cfu')
            ->setAllowedTypes('cfu', 'array');

        return $this->put('/api/cfmappings/'.$this->encodePath($subscriber_id), $resolver->resolve($parameters));
    }

    public function edit($subscriber_id, array $parameters = [])
    {
        $this->createEditOptionsResolver($parameters);

        return $this->patch('/api/cfmappings/'.$this->encodePath($subscriber_id), $parameters);
    }
}
