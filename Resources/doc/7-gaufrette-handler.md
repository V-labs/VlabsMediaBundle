Use other filesystems with Gaufrette (beta)
-------------------------------------------

###Setup Gaufrette and your handler

This bundle provides an implementation of the [Gaufrette library](https://github.com/KnpLabs/Gaufrette), through the [KnpGaufretteBundle](https://github.com/KnpLabs/KnpGaufretteBundle).

Gaufrette offers different filesystems implementation, trought the adapter pattern (local, ftp, amazon s3, dropbox, etc. ..) and KnpGaufretteBundle allows the management of different filesystems via services.

The **handler** node of your entities **identifier** can receive a Gaufrette filesystem identifier :

    knp_gaufrette:
        adapters:
            vlabs_local:
                local:
                    directory:  /var/www/my_website/web
                    create:     true
            vlabs_ftp:
                ftp:
                    host: myhost
                    username: myusername
                    password: $3cr3Tp4ssw0rd
                    directory: /
                    create: true
                    mode: FTP_BINARY
                    passive: true
        filesystems:
            vlabs_gaufrette_local_fs:
                adapter: vlabs_local
            vlabs_gaufrette_ftp_fs:
                adapter: vlabs_ftp
    
    vlabs_media:
        cdn:
            media: http://www.mediaserver.com
        mapping: 
            document_entity:
              class: My\FooBundle\Entity\Document
              handler: vlabs_media.handler.local_file_system
            image_entity:
              class: My\FooBundle\Entity\Image
              handler: vlabs_gaufrette_ftp_fs

As you can see, we directly use the filesystem identifier **vlabs_gaufrette_ftp_fs** for our handler key. 
To work, we need that the filesystem identifier starts with **vlabs_gaufrette**. 
Internaly, the bundle is working with the [GaufretteHandler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Handler/GaufretteHandler.php) wich use the Gaufrette filesystem.


Note that the **directory** Gaufrette key is used as base url for upload path. So your media will be uploaded at :

    <directory>/<upload_dir>  # upload_dir from @Vlabs\Media annotation

The target directory must be reachable with HTTP.

###Use the @Vlabs\Cdn() annotation

To recover your media, if it is located on another server, you can use the **@Vlabs\Cdn()** annotation to provide the host.

    /**
     * My\FooBundle\Entity\Image
     */
    class Image extends VlabsFile
    {
        /**
         * @var string $path
         *
         * @Assert\Image()
         * @Vlabs\Cdn(base_url="media")
         */
        private $path;
        
        /* ...*/
    }

The annotation is using the **media** configuration identifier to retrieve the correct host.

Note that you can setup a **default** configuration identifier wich will be used for all not set **@Vlabs\Cdn()** annotation in your entities.
If no **default** identifier is set, host will be null, the media is supposed to live in the same server as the application.

Documentation
-------------

+   [Installation & usage](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/1-bundle-setup-and-usage.md)
+   [Configuration reference](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/2-configuration-reference.md)
+   [Templating](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/3-templating.md)
+   [Deleting Media](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/4-deleting-media.md)
+   [Custom and/or stand alone handlers](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/5-custom-stand-alone-handlers.md)
+   [Custom filters](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/6-custom-stand-alone-filters.md)
+   [Gaufrette handler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/7-gaufrette-handler.md)
