Custom and/or stand alone handlers
----------------------------------

###Create your own handler

Each handler must be a service, and must implement the [MediaHandlerInterface](https://github.com/V-labs/VlabsMediaBundle/blob/master/Handler/MediaHandlerInterface.php).

The bundle currently works with a handler for managing local files. You can imagine handler to manage media on any file systems.

To make an entity managed by one of your handler, simply add it to the bundle configuration, after declaring the handler as a service in your bundle.

services.xml

    <service id="my_foo.handler.amazon_s3" class="My\FooBundle\Handler\AmazonS3" parent="vlabs_media.handler.abstract_handler" />
    
Here we are extending the [AbstractHandler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Handler/AbstractHandler.php) provided by the bundle. 
He is already managing the [BaseFile](https://github.com/V-labs/VlabsMediaBundle/blob/master/Entity/BaseFile.php) creation, [Namer](https://github.com/V-labs/VlabsMediaBundle/blob/master/Tools/Namer.php) tool injection, and all the getters & setters.


If you want to reimplement the entire interface, you must inject a **Namer** object in your handler (which you can manage via the configuration, simply call the getter with the **vlabs_media.namer** service).

    <service id="my_foo.handler.amazon_s3" class="My\FooBundle\Handler\AmazonS3">
        <call method="setNamer">
            <argument type="service" id="vlabs_media.namer" />
        </call>
    </service>

And then, simply put it on your wanted entity :

    mapping: 
        document_entity:
          class: My\FooBundle\Entity\Document
          handler: my_foo.handler.amazon_s3
          
###Stand alone handlers

As you have seen, all handlers are services. So you can use them directly in the controller outside the form process.

     $s3Handler = $this->get('my_foo.handler.amazon_s3');
     
The only thing you need to do is to manually define a class for the media currently managed and for the upload directory.

    $s3Handler->setUploadDir('my/path/for/media');
    $s3Handler->setMediaClass('My\FooBundle\Entity\Document');
    
Then you can access all the other methods safely.

Documentation
-------------

+   [Installation & usage](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/1-bundle-setup-and-usage.md)
+   [Configuration reference](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/2-configuration-reference.md)
+   [Templating](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/3-templating.md)
+   [Deleting Media](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/4-deleting-media.md)
+   [Custom and/or stand alone handlers](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/5-custom-stand-alone-handlers.md)
+   [Custom filters](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/6-custom-stand-alone-filters.md)
+   [Gaufrette handler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/7-gaufrette-handler.md)