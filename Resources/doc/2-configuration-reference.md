Bundle Configuration
--------------------

As we saw earlier, the configuration can be very simple :

    vlabs_media:
        image_cache:
            cache_dir: files/c
        mapping: 
            image_entity:
              class: My\FooBundle\Entity\Image

Here we use a single entity class **Image** with an **image_entity** identifier.


You can set up more complex configurations:

    vlabs_media:
        driver: odm
        namer: My\FooBundle\Tools\Namer
        templates:
            form_doc: MyFooBundle:Templates:form_doc.html.twig
            image: MyFooBundle:Templates:image.html.twig
        image_cache:
            cache_dir: files/c
        mapping:
            image_entity:
              class: My\FooBundle\Entity\Image
              handler: vlabs_media.handler.local_file_system
            user_picture:
              class: My\FooBundle\Entity\UserPicture
            student_resume:
              class: My\FooBundle\Entity\StudentResume
              handler: my_foo.handler.awesome_cloud

+    **driver** : can be **orm** for Doctrine ORM or **odm** for doctrine odm mongodb (default : orm).
+    **name** : used to rename file after upload, you can setup your own by implementing the [NamerInterface](https://github.com/V-labs/VlabsMediaBundle/blob/master/Tools/NamerInterface.php) (default : Vlabs\MediaBundle\Tools\Namer).
+    **templates** : both key & value are used, you can add as many templates as you want. See the [templating section](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/3-templating.md) for more informations.
+    **cache_dir** : the path for cached image files (mandatory).
+    **handler** : the DIC service identifier for the wanted handler for this entity (default : vlabs_media.handler.local_file_system).


Each time you create a new mapped media entity, do not forget to run :

    php app/console doctrine:schema:update --force


Documentation
-------------

+   [Installation & usage](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/1-bundle-setup-and-usage.md)
+   [Configuration reference](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/2-configuration-reference.md)
+   [Templating](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/3-templating.md)
+   [Deleting Media](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/4-deleting-media.md)
+   [Custom and/or stand alone handlers](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/5-custom-stand-alone-handlers.md)
+   [Custom filters](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/6-custom-stand-alone-filters.md)
+   [Gaufrette handler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/7-gaufrette-handler.md)