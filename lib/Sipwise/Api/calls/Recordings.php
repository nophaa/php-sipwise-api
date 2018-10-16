<?php

namespace Sipwise\Api\Calls;

use Sipwise\Api\AbstractApi;

/**
 * Description of Call Recordings.
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class Recordings extends AbstractApi
{
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        return $this->get('/api/callrecordings', $resolver->resolve($parameters));
    }

    public function add(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        return $this->post('/api/callrecordings', $resolver->resolve($parameters));
    }

    public function update($callrecord_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        return $this->put('/api/callrecordings/'.$this->encodePath($callrecord_id), $resolver->resolve($parameters));
    }

    public function edit($callrecord_id, array $parameters = [])
    {
        $this->createEditOptionsResolver($parameters);

        return $this->patch('/api/callrecordings/'.$this->encodePath($callrecord_id), $parameters);
    }

    public function remove($callrecord_id)
    {
        return $this->delete('/api/callrecordings/'.$this->encodePath($callrecord_id));
    }
}
