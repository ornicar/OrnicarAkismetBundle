Provides a service to interact with http://akismet.com
Uses ``Buzz`` to send the HTTP request.
Automatically grabs the request data.
Allows to switch to a stub implementation for fixtures and tests.
Can silent exceptions on production.

Installation
============

Install Buzz
------------

Buzz is a lightweight HTTP client for PHP 5.3. Get it here https://github.com/kriswallsmith/Buzz

Add AkismetBundle to your src/ dir
-------------------------------------

::

    $ git submodule add git://github.com/Ornicar/AkismetBundle.git vendor/bundles/Ornicar/AkismetBundle

Add the Ornicar namespace to your autoloader
----------------------------------------

::

    // app/autoload.php

    $loader->registerNamespaces(array(
        'Ornicar' => __DIR__.'/../vendor/bundles',
        // your other namespaces
    );

Add AkismetBundle to your application kernel
-----------------------------------------

::

    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            new Ornicar\AkismetBundle\OrnicarAkismetBundle(),
            // ...
        );
    }

Configure your project
----------------------

You need an api key to use Akismet, and a site url::

    # app/config/config.yml

    ornicar_akismet:
        api_key:        xxxxxxxxxxxxx
        url:            http://lichess.org

Usage
=====

You can now you the akismet service to check your data for spam::

    $akismet = $container->get('ornicar_akismet');

    $isSpam = $akismet->isSpam(array(
        'comment_author'  => 'Jack',
        'comment_content' => 'The moon core is made of cheese'
    ));

You don't have to specify the request informations, like the user IP.
AkismetBundle takes care of it for you.

More options
============

Use a stub implementation
-------------------------

During fixtures and tests, you don't want to send HTTP requests to akismet.com.
Just change the service implementation to the stub,
and all content will be considered as not beeing spam,
without sending any request::

    # app/config/config_dev.yml

    ornicar_akismet:
        service:        ornicar_akismet.akismet_stub

AkismetBundle provides two implementations, ``ornicar_akismet.akismet_real`` and ``ornicar_akismet.akismet_stub``.
Feel free to add your custom service implementation if needed.

Disable exceptions
------------------

On production, you don't want your site to break if, for some reason, akismet.com is unreachable.
Ignore all akismet exception in your prod environment::

    # app/config/config_prod.yml

    ornicar_akismet:
        throw_exceptions: false

By default, this value is set to ``%kernel.debug``.
