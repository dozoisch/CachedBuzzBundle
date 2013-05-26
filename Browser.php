<?php

namespace Dozoisch\CachedBuzzBundle;

use Buzz\Browser as BuzzBrowser;
use Buzz\Client\ClientInterface;

class Browser extends BuzzBrowser {

    /**
     *
     * @var Cacher
     */
    private $cacher;

    function __construct(Cacher $cacher, ClientInterface $httpClient = null, FactoryInterface $factory = null) {
        parent::__construct($httpClient, $factory);
        $this->cacher = $cacher;
    }

    /**
     * Sends a request.
     *
     * @param string $url     The URL to call
     * @param string $method  The request method to use
     * @param array  $headers An array of request headers
     * @param string $content The request content
     *
     * @return MessageInterface The response object
     */
    public function call($url, $method, $headers = array(), $content = '') {
        $request = $this->factory->createRequest($method);

        if (!$url instanceof Url) {
            $url = new Url($url);
        }

        $url->applyToRequest($request);

        $request->addHeaders($headers);
        $request->setContent($content);

        $data = $this->cacher->retrieveCachedResponse($request);
        
        if (!$data) {
            $response = $this->send($request);
            $this->cacher->cacheResponse($request, $response);
        } else {
            $response = $data['response'];
        }

        return $response;
    }

}

?>
