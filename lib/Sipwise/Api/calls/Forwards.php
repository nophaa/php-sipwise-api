<?php

namespace Sipwise\Api\Calls;

use Sipwise\Api\AbstractApi;

/**
 * Description of CallForwards.
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class Forwards extends AbstractApi
{
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        $resolver->setDefined('subscriber_id')
            ->setAllowedTypes('subscriber_id', 'integer');

        return $this->get('/api/callforwards', $resolver->resolve($parameters));
    }

    public function show($subscriber_id)
    {
        return $this->get('/api/callforwards/'.$this->encodePath($subscriber_id));
    }

    public function add(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        return $this->post('/api/callforwards/', $resolver->resolve($parameters));
    }

    public function update($callforward_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        return $this->put('/api/callforwards]/'.$this->encodePath($callforward_id), $resolver->resolve($parameters));
    }

    public function edit($callforward_id, array $parameters = [])
    {
        $this->createEditOptionsResolver($parameters);

        return $this->patch('/api/callforwards/'.$this->encodePath($callforward_id), $parameters);
    }

    public function remove($callforward_id)
    {
        return $this->delete('/api/callforwards/'.$this->encodePath($callforward_id));
    }
}
