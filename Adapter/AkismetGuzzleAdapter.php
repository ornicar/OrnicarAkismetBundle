<?php

namespace Ornicar\AkismetBundle\Adapter;

use Guzzle\Service\Client;

class AkismetGuzzleAdapter implements AkismetAdapterInterface
{
    /**
     * @var Client Guzzle client
     */
    protected $client;

    /**
     * @param string $blogUrl
     * @param strint $apiKey
     */
    public function __construct($blogUrl, $apiKey)
    {
        $this->client = new Client('http://{{ api_key }}.rest.akismet.com', array(
            'api_key'  => $apiKey,
            'blog_url' => $blogUrl
        ));
    }

    public function isSpam(array $data): bool
    {
        $data['blog'] = $this->client->getConfig('blog_url');
        $request = $this->client->post('/1.1/comment-check', null, http_build_query($data));

        return 'true' == (string) $request->send()->getBody();
    }

    public function submitSpam(array $data): bool
    {
        $data['blog'] = $this->client->getConfig('blog_url');
        $request = $this->client->post('/1.1/submit-spam', null, http_build_query($data));

        $request->send()->getBody();
    }
}
