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
     * Constructor
     *
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

    /**
     * Returns TRUE if Akismet thinks the data is spam
     *
     * @param array $data
     * @return boolean
     */
    public function isSpam(array $data)
    {
        $data['blog'] = $this->client->getConfig('blog_url');
        $request = $this->client->post('/1.1/comment-check', null, http_build_query($data));

        return 'true' == (string) $request->send()->getBody();
    }
}
