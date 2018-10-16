<?php namespace Sipwise\Api\Calls\Cf;


use Sipwise\Api\AbstractApi;
/**
 * Description of CfDestinationSets
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class CfDestinationSets extends AbstractApi
{
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);
        
        $resolver->setDefined('subscriber_id')
            ->setAllowedTypes('subscriber_id', array('string', 'integer'));
        
        $resolver->setDefined('name')
            ->setAllowedTypes('name', 'string');
        
        return $this->get('/api/cfdestinationsets', $resolver->resolve($parameters));
    }
    
    public function show($cfdestinationsetId)
    {
        return $this->get('/api/cfdestinationsets/'.$this->encodePath($cfdestinationsetId));
    }
    
    public function add(array $parameters  = [])
    {
        $resolver = $this->createOptionsResolver($parameters);
        
        return $this->post('/api/cfdestinationsets', $resolver->resolve($parameters));
    }
    
    public function update($cfdestinationsetId, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);
        return $this->put('/api/cfdestinationsets/'.$this->encodePath($cfdestinationsetId), $resolver->resolve($parameters));
    }
    
    public function edit($cfdestinationsetId, array $parameters = [])
    {
        $this->createEditOptionsResolver($parameters);
        
        $this->patch('/api/cfdestinationsets/'.$this->encodePath($cfdestinationsetId), $parameters);
    }
    
    public function remove($cfDestinationId)
    {   
        $this->delete('/api/cfdestinationsets/'.$this->encodePath($cfDestinationId));
    }
}
