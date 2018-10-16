<?php
namespace Sipwise\Api;

use Sipwise\Api\AbstractApi;
use Sipwise\Api\VoiceMail\VoiceMailSettings;
/**
 * Description of VoiceMail
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class VoiceMails extends AbstractApi
{
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);
        
        $resolver->setDefined('tz')
            ->setAllowedTypes('tz', 'timestamp');
        
        $resolver->setDefined('use_owner_tz')
            ->setAllowedTypes('use_owner_tz', 'timestamp');
        
        $resolver->setDefined('subscriber_id')
            ->setAllowedTypes('subscriber_id', array('string', 'integer'));
        
        $resolver->setDefined('folder')
            ->setAllowedTypes('folder', 'string')
            ->setAllowedValues('folder', ['INBOX', 'Old', 'Work', 'Friends', 'Family', 'Cust1', 'Cust2', 'Cust3', 'Cust4', 'Cust5', 'Cust6']);
        
        
        return $this->get('/api/voicemails', $resolver->resolve($parameters));
    }
    
    public function settings()
    {
        return new VoiceMailSettings($this->client);
    }
    
    public function show($voicemailId)
    {
        return $this->get('/api/voicemails/'.$this->encodePath($voicemailId));
    }
    
    public function update($voicemailId, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);
        
        $resolver->setDefined('folder')
            ->setAllowedTypes('folder', 'string');
        
        $resolver->setRequired('folder');
        
        return $this->put('/api/voicemails/'.$this->encodePath($voicemailId), $resolver->resolve($parameters));
    }
    
    public function edit($voicemailId, array $parameters = [])
    {
        $this->createOptionsResolver($parameters);
        
        return $this->patch('/api/voicemails/'.$this->encodePath($voicemailId), $parameters);
    }
    
    public function remove($voicemailId)
    {
        return $this->delete('/api/voicemails/'.$this->encodePath($voicemailId));
    }
}