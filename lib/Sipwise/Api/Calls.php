<?php
namespace Sipwise\Api;

use Sipwise\Api\AbstractApi;
use Sipwise\Api\Calls\Recordings;
use Sipwise\Api\Calls\Forwards;
use Sipwise\Api\Calls\Cf\CfMappings;
use Sipwise\Api\Calls\Cf\CfDestinationSets;

/**
 * Description of Calls
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class Calls extends AbstractApi
{   
    public function all(array $parameters = [])
    {
        //allowed values
        $status = array('ok', 'busy', 'noanswer', 'cancel', 'offline', 'timeout', 'other');
        $rated =  array('oke', 'unrated', 'failed');
        $type = array('call', 'cfu', 'cfb', 'cft', 'cfna','cfs');
        
        $resolver = $this->createOptionsResolver($parameters);
        
        $resolver->setDefined('tz')
            ->setAllowedTypes('tz', 'string');
        
        $resolver->setDefined('use_owner_tz')
            ->setAllowedTypes('use_owner_tz', 'string');
        
        $resolver->setDefined('subscriber_id')
            ->setAllowedTypes('subscriber_id', array('string', 'integer'));
        
        $resolver->setDefined('customer_id')
            ->setAllowedTypes('customer_id', array('string', 'integer'));
         
        $resolver->setDefined('alias_field')
            ->setAllowedTypes('alias_field', 'string');
        
        $resolver->setDefined('status')
            ->setAllowedTypes('status', 'string')
            ->setAllowedValues('status', $status);
         
        $resolver->setDefined('status_ne')
            ->setAllowedTypes('status_ne', 'string')
            ->setAllowedValues('status_ne', $status);
        
        $resolver->setDefined('rating_status')
            ->setAllowedTypes('rating_status', 'string')
            ->setAllowedValues('rating_status', $rated);
        
       $resolver->setDefined('rating_status_ne')
            ->setAllowedTypes('rating_status_ne', 'string')
            ->setAllowedValues('rating_status_ne', $rated);
       
        $resolver->setDefined('type')
            ->setAllowedTypes('type', 'string')
            ->setAllowedValues('type', $type);
        
        $resolver->setDefined('type_ne')
            ->setAllowedTypes('type_ne', 'string')
            ->setAllowedValues('type_ne', $type);
        
        $resolver->setDefined('type_ne')
            ->setAllowedTypes('type_ne', 'string')
            ->setAllowedValues('type_ne', $type);
        
        $resolver->setDefined('direction')
            ->setAllowedTypes('direction', 'string')
            ->setAllowedValues('direction', array('in', 'out'));
        
        $resolver->setDefined('start_ge')
            ->setAllowedTypes('start_ge', 'timestamp');
        
        $resolver->setDefined('start_le')
            ->setAllowedTypes('start_le', 'timestamp');
        
        $resolver->setDefined('call_id')
            ->setAllowedTypes('call_id', array('integer', 'string'));
        
        $resolver->setDefined('own_cli')
            ->setAllowedTypes('own_cli', array('integer', 'string'));
        
        $resolver->setDefined('order_by')
            ->setAllowedTypes('order_by', 'string');
        
        $resolver->setDefined('order_by_direction')
            ->setAllowedTypes('order_by_direction', 'string');
        
        return $this->get('/api/calllists', $resolver->resolve($parameters));
    }
    
    public function show($subscriber_id)
    {
        return $this->get('/api/calllists/?subscriber_id='.$this->encodePath($subscriber_id));
    }
    
    public function forwards()
    {
        return new Forwards($this->client);
    }
    
    public function recording()
    {
        return new Recordings($this->client);
    }
    
    public function cfMappings()
    {
        return new CfMappings($this->client);
    }
    
    public function cfDestinationSets()
    {
        return new CfDestinationSets($this->client);
    }
    
}
