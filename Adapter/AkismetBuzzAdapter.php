<?php

namespace Ornicar\AkismetBundle\Adapter;

use Buzz\Message;
use Buzz\Client;

class AkismetBuzzAdapter implements AkismetAdapterInterface
{
    /**
     * Your website url
     *
     * @var string
     */
    protected $blogUrl;

    /**
     * Your api key
     *
     * @var string
     */
    protected $apiKey;

    public function __construct($blogUrl, $apiKey)
    {
        $this->blogUrl = $blogUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * Tells if the data looks like spam
     *
     * @param array $data
     * @return boolean
     */
    public function isSpam(array $data)
    {
        $data['blog'] = $this->blogUrl;

        $request = new Message\Request('POST', '/1.1/comment-check', $this->getHost());
        $request->setContent(http_build_query($data));
        $request->addHeader('Content-Type: application/x-www-form-urlencoded');
        $request->addHeader(sprintf('Content-Length: %d', strlen($request->getContent())));
        $response = new Message\Response();

        $client = new Client\Curl();
        $client->send($request, $response);

        return 'true' == $response->getContent();
    }

    protected function getHost()
    {
        return sprintf('http://%s.rest.akismet.com', $this->apiKey);
    }
}
