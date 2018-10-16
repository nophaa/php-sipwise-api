<?php

namespace Sipwise\Api\VoiceMail;

use Sipwise\Api\AbstractApi;

/**
 * Description of VoiceMailSettings.
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class VoiceMailSettings extends AbstractApi
{
    public function all(array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        $resolver->setDefined('subscriber_id')
            ->setAllowedTypes('subscriber_id', ['string', 'integer']);

        $resolver->setRequired('subscriber_id');

        return $this->get('/api/voicemailsettings', $resolver->resolve($parameters));
    }

    public function show($voicemailsettings)
    {
        return $this->get('/api/voicemailsettings/'.$this->encodePath($voicemailsettings));
    }

    public function update($voicemailsettings, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver($parameters);

        $resolver->setDefined('pin')
            ->setAllowedTypes('pin', ['string', 'integer']);

        $resolver->setDefined('sms_number')
            ->setAllowedTypes('sms_number', ['string', 'integer']);

        $resolver->setRequired(['pin', 'sms_number']);

        return $this->put('/api/voicemailsettings/'.$this->encodePath($voicemailsettings), $resolver->resolve($parameters));
    }

    public function edit($voicemailsettings, array $parameters = [])
    {
        $this->createEditOptionsResolver($parameters);

        return $this->patch('/api/voicemailsettings/'.$this->encodePath($voicemailsettings), $parameters);
    }
}
