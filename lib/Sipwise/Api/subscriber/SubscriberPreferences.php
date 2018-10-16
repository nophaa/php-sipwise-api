<?php

namespace Sipwise\Api\subscriber;

use Sipwise\Api\AbstractApi;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * Description of SubscriberPreferences.
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class SubscriberPreferences extends AbstractApi
{
    /**
     * @param array $parameters {
     *
     *     @var int    $reseller_id                 Limit by reseller id.
     *     @var string $contact_id                  Limit by contact id.
     *     @var string $order_by                    Return customers ordered by id, contract_id, uuid,
     *                                              or username, domain_id, status, primary_number_id, external_id, contact_id.
     *     @var string $order_by_direction          Return customers sorted in asc or desc order. Default is desc.
     * }
     *
     * @throws UndefinedOptionsException If an option name is undefined
     * @throws InvalidOptionsException   If an option doesn't fulfill the
     *                                   specified validation rules
     *
     * @return mixed
     */
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        $resolver->setDefined('reseller_id')
                 ->setAllowedTypes('reseller_id', 'integer');

        $resolver->setDefined('contact_id')
                 ->setAllowedTypes('contact_id', 'integer');

        $resolver->setDefined('order_by')
            ->setAllowedTypes('order_by', 'string');

        $resolver->setDefined('order_by_direction')
            ->setAllowedTypes('order_by_direction', 'string');

        return $this->get('/api/subscriberpreferences', $resolver->resolve($parameters));
    }

    public function show($subscriberpref_id)
    {
        return $this->get('/api/subscriberpreferences/'.$this->encodePath($subscriberpref_id));
    }

    public function update($subscriberpref_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        return $this->put('/api/subscriberpreferences/'.$this->encodePath($subscriberpref_id), $resolver->resolve($parameters));
    }

    public function edit($subscriberpref_id, array $parameters = [])
    {
        $this->createEditOptionsResolver($parameters);

        return $this->patch('/api/subscriberpreferences/'.$this->encodePath($subscriberpref_id), $parameters);
    }
}
