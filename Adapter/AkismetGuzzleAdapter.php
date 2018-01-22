<?php

namespace Ornicar\AkismetBundle\Adapter;

use GuzzleHttp\Client;
use Ornicar\AkismetBundle\Adapter\Exception\InvalidResponseException;

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
        $response = $this->client->post('/1.1/comment-check', ['form_params' => $data])->getBody()->getContents();

        if (!in_array($response, ['true', 'false'])) {
            throw new InvalidResponseException($response);
        }

        return 'true' == $response;
    }

    public function submitSpam(array $data)
    {
        $data['blog'] = $this->blogUrl;

        $response = $this->client->post('/1.1/submit-spam', ['form_params' => $data])->getBody()->getContents();
        if ('Thanks for making the web a better place.' !== $response) {
            throw new InvalidResponseException($response);
        }
    }
}
