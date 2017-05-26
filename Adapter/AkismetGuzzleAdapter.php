<?php

namespace Ornicar\AkismetBundle\Adapter;

use GuzzleHttp\Client;

class AkismetGuzzleAdapter implements AkismetAdapterInterface
{
    /**
     * @var string
     */
    protected $blogUrl;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param string $blogUrl
     * @param strint $apiKey
     */
    public function __construct($blogUrl, $apiKey)
    {
        $this->blogUrl = $blogUrl;
        $this->client = new Client(['base_uri' => sprintf('http://%s.rest.akismet.com', $apiKey)]);
    }

    public function isSpam(array $data): bool
    {
        $data['blog'] = $this->blogUrl;
        $response = $this->client->post('/1.1/comment-check', ['form_params' => $data]);

        return 'true' == $response->getBody()->getContents();
    }

    public function submitSpam(array $data)
    {
        $data['blog'] = $this->blogUrl;
        $this->client->post('/1.1/submit-spam', ['form_params' => $data]);
    }
}
