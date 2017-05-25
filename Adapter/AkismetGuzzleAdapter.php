<?php

namespace Ornicar\AkismetBundle\Adapter;

use Guzzle\Service\Client;

class AkismetGuzzleAdapter implements AkismetAdapterInterface
{
    /**
     * @var string
     */
    protected $blogUrl;

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
        $this->blogUrl = $blogUrl;
        $this->client = new Client(sprintf('http://%s.rest.akismet.com', $apiKey));
    }

    public function isSpam(array $data): bool
    {
        $data['blog'] = $this->blogUrl;
        $request = $this->client->post('/1.1/comment-check', null, http_build_query($data));

        return 'true' == (string) $request->send()->getBody();
    }

    public function submitSpam(array $data)
    {
        $data['blog'] = $this->blogUrl;
        $request = $this->client->post('/1.1/submit-spam', null, http_build_query($data));

        $request->send()->getBody();
    }
}
