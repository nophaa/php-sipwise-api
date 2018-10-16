<?php namespace Sipwise\Api;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Description of CustomerContacts
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class CustomerContacts extends AbstractApi
{
    /**
     * @param array $parameters {
     *
     *     @var int    $profile_id                  Limit by profile_id status.
     *     @var string $username                    Limit by username.
     *     @var string $order_by                    Return customercontacts ordered by id, contract_id, uuid,
     *                                              or username, domain_id, status, primary_number_id, external_id, contact_id.
     *     @var string $order_by_direction          Return customercontacts sorted in asc or desc order. Default is desc.
     *     @var string $webusername                 Return list of customercontacts matching the webusername.
     *     @var string $webpassword                 Return list of customercontacts matching the webpassword.
     *     @var string $domain                      Return list of customercontacts matching the domain.
     *     @var int    $customer_id                 Limit by customercontacts that the $customer_id.
     *     @var int    $customer_external_id        Limit by customercontacts by the customer_external_id.
     *     @var int    $subscriber_external_id      Limit by customercontacts by the subscriber_external_id.
     *     @var int    $reseller_id                 Limit by reseller_id.
     *     @var string $alias                       Limit by alias.
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
        $resolver->setDefined('profile_id')
            ->setAllowedTypes('profile_id', 'integer');
        
        $resolver->setDefined('username')
            ->setAllowedTypes('username', array('integer','string'));
        
        $resolver->setDefined('webusername')
            ->setAllowedTypes('webusername', 'string');
        
        $resolver->setDefined('webpassword')
            ->setAllowedTypes('webpassword', 'string');
        
        $resolver->setDefined('domain')
            ->setAllowedTypes('domain', 'string');
        
        $resolver->setDefined('customer_id')
            ->setAllowedTypes('customer_id', array('integer','string'));
        
        $resolver->setDefined('customer_external_id')
            ->setAllowedTypes('customer_external_id', 'integer');
        
        $resolver->setDefined('subscriber_external_id')
            ->setAllowedTypes('subscriber_external_id', 'integer');
        
        $resolver->setDefined('reseller_id')
            ->setAllowedTypes('reseller_id', 'integer');
        
        $resolver->setDefined('alias')
            ->setAllowedTypes('alias', array('integer', 'string'));
        
        $resolver->setDefined('order_by')
            ->setAllowedTypes('order_by', 'string');
        
        $resolver->setDefined('order_by_direction')
            ->setAllowedTypes('order_by_direction', 'string');

        return $this->get('/api/customercontacts/', $resolver->resolve($parameters));
    }

    /**
     * @param int|string $subscription_id
     * @param array $parameters {
     *
     *     @var bool   $statistics                    Include project statistics.
     *     @var bool   $with_custom_attributes        Include project custom attributes.
     * }
     * @return mixed
     */
    public function show($subscriber_id = null)
    {
        return $this->get('/api/customercontacts/'.$this->encodePath($subscriber_id));
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function create(array $parameters = array())
    {
        $resolver = $this->createOptionsResolver($parameters);
        
        $resolver->setDefined('customer_id')
            ->setAllowedTypes('customer_id', 'integer');
        
        $resolver->setDefined('domain_id')
            ->setAllowedTypes('domain_id', 'integer');
        
        $resolver->setDefined('username')
            ->setAllowedTypes('username', array('integer','string'));
        
        $resolver->setDefined('password')
            ->setAllowedTypes('password', 'string');
        
        $resolver->setRequired(['customer_id', 'domain_id', 'password', 'username']);
        
        return $this->post('/api/customercontacts/', $resolver->resolve($parameters));
    }

    /**
     * @param int $subscriber_id
     * @param array $params
     * @return mixed
     */
    public function update($subscriber_id, array $parameters)
    {
        $resolver = $this->createOptionsResolver($parameters);
        
        $resolver->setDefined('customer_id')
            ->setAllowedTypes('customer_id', 'integer');
        
        $resolver->setDefined('domain_id')
            ->setAllowedTypes('domain_id', 'integer');
        
        $resolver->setDefined('username')
            ->setAllowedTypes('username', ['integer','string']);

        $resolver->setDefined('password')
            ->setAllowedTypes('password', 'string');
        
        $resolver->setRequired(['customer_id','domain_id', 'password', 'username']);
        
        return $this->put('/api/customercontacts/'.$this->encodePath($subscriber_id), $resolver->resolve($parameters));
    }
    
    public function edit($subscriber_id, array $parameters)
    {
        $this->createOptionsResolver($parameters);
        
        return $this->patch('/api/customercontacts/'.$this->encodePath($subscriber_id), $parameters);
    }

    /**
     * @param int $subscriber_id
     * @return mixed
     */
    public function remove($subscriber_id)
    {
        return $this->delete('/api/customercontacts/'.$this->encodePath($subscriber_id));
    }
}
