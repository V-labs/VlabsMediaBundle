Installation & usage
====================

This bundle provides an interface which defines physical media: an image, a pdf etc. ..

All media are considered as objects, and if they implement the **[BaseFileInterface](https://github.com/V-labs/VlabsMediaBundle/blob/master/Entity/BaseFileInterface.php)** they will be automatically persisted, moved and you will manipulate them as text fields in forms.


Installation
------------

composer.json

    {
        require: {
            "vlabs/media-bundle": ">=1.1"
        }
    }

AppKernel.php

    <?php

    $bundles = array(
        new Vlabs\MediaBundle\VlabsMediaBundle(),
    );
    
    
Usage
-----

This is a four step configuration :

**1)** You must create an object for your media. Let's say we are working with **Article** that contain an **Image**.
We work here with Doctrine ORM (the bundle also works with Doctrine ODM MongoDB).

So for our Image class :

    <?php

    namespace My\FooBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Vlabs\MediaBundle\Entity\BaseFile as VlabsFile;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * My\FooBundle\Entity\File
     *
     * @ORM\Entity
     * @ORM\Table(name="vlabs_image")
     */
    class Image extends VlabsFile
    {
        /**
         * @var string $path
         *
         * @ORM\Column(name="path", type="string", length=255)
         * @Assert\Image()
         */
        private $path;

        /**
         * Set path
         *
         * @param string $path
         * @return Image
         */
        public function setPath($path)
        {
            $this->path = $path;

            return $this;
        }

        /**
         * Get path
         *
         * @return string 
         */
        public function getPath()
        {
            return $this->path;
        }

    }
    
As you can see, our **Image** class is extending [VlabsFile](https://github.com/V-labs/VlabsMediaBundle/blob/master/Entity/BaseFile.php), which is an abstract class that implements the properties that you do not want to handle during a media processing.

The only property that is not implemented is **path**, this allow you to perform validations on your media. Here you use Symfony validators on the property.

**2)** Then, you can link your **Image** object with your main entity property :

    <?php

    namespace My\FooBundle\Entity;
    
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;
    use Vlabs\MediaBundle\Annotation\Vlabs;
    
    /**
     * My\FooBundle\Entity\Article
     *
     * @ORM\Table(name="article")
     * @ORM\Entity
     */
    class Article
    {

    /* other properties / setters & getters */

    /**
     * @var VlabsFile
     *
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"}, orphanRemoval=true))
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image", referencedColumnName="id")
     * })
     * 
     * @Vlabs\Media(identifier="image_entity", upload_dir="files/images")
     * @Assert\Valid()
     */
    private $image;

    /**
     * Set image
     *
     * @param My\FooBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return My\FooBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

As you can see, the bundle provides an annotation **@Vlabs\Media()** you need to put in place on the property of each media you want to manage.
The annotation use two options :
+    **identifier** : the internal name of your media, mandatory (can be what you want)
+    **upload_dir** : the path where the media will be uploaded (mandatory)

To enable your media validation, you must use the @Valid() annotation provided by Symfony. 
The validator will validate your media according to the constraints of the **Image path** property.


**3)** Now that your entities are configured, proceed to the bundle configuration.

config.yml

    vlabs_media:
        image_cache:
            cache_dir: files/c
        mapping: 
            image_entity:
              class: My\FooBundle\Entity\Image

Here we find the identifier **image_entity** that you configured on the entity. It can be what you want.
It just need to be the same between each property and configuration.
So you can use the same **identifier** and different **upload_dir** for each property using this identifier.

For all images (gif, jpeg & png mime types), the bundle implements a dynamic resizing. 
With twig, you manage display sizes and the bundle creates the image in cache if it does not exist. 
It will then automatically be called from cache.

You have to specify a path for the cached files.
Your server must have write permissions on the base media directory (files here).

Then, you can run the command to create the **vlabs_image** table with all the attributes and foreign key.

    php app/console doctrine:schema:update --force

**4)** Last step : form configuration

Here it's simple, just use the **vlabs_file** type  for all the fields that you want to manage with the bundle.

ArticleType.php

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'vlabs_file', array(
                    'required' => false
                ))
        ;
    }


### Display a media on front end

The bundle provided a twig method that you can call from any template :

    {{ article.image|vlabs_media('default') }} // will return the path on the filesystem

For all images, if you want to resize, you can use :
    
    // return the img tag for resized image
    {{ article.image|vlabs_filter('resize', { 'width' : 300, 'height' : 300 })|vlabs_media('image') }}


Documentation
-------------

+   [Installation & usage](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/1-bundle-setup-and-usage.md)
+   [Configuration reference](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/2-configuration-reference.md)
+   [Templating](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/3-templating.md)
+   [Deleting Media](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/4-deleting-media.md)
+   [Custom and/or stand alone handlers](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/5-custom-stand-alone-handlers.md)
+   [Custom filters](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/6-custom-stand-alone-filters.md)
+   [Gaufrette handler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/7-gaufrette-handler.md)
