Templating
----------

There are two twig filters to manipulate your media in templates :

###The vlabs_media() filter

    {{ <media_object>|vlabs_media(<template>, <options_array> }}

+    **media_objet** : for exemple, entity.image
+    **template** : Here you can either use the identifier of the template defined in the configuration, either the full path to a template (if you do not want to set it in the configuration).

So if your configuration looks like :

    templates:
            form_doc: MyFooBundle:Templates:form_doc.html.twig
            image: MyFooBundle:Templates:image.html.twig

Those 3 calls will works :

    {{ entity.image|vlabs_media('image') }}
    {{ entity.doc|vlabs_media('MyFooBundle:Templates:form_doc.html.twig') }}
    {{ entity.resume|vlabs_media('MyFooBundle:Templates:pdf.html.twig') }}

Note that the third file must exists.


**Using media properties and templates options**

The second parameter is an array. You can pass all the data that you want, they will be accesible in the template.
You also have access to all the media properties.
    
    {{ entity.image|vlabs_media('image', { name : 'John Doe' } ) }}

will give you access in the `MyFooBundle:Templates:image.html.twig` template to :

    <img src="{{ media.path }}" alt="{{ options.name }}" />

If your media is extending from [Vlabs\MediaBundle\Entity\BaseFile](https://github.com/V-labs/VlabsMediaBundle/blob/master/Entity/BaseFile.php), you will also have access to all this properties.


There are six templates provided by default with the bundle, you can (must?) replace. Just change the path of template in the configuration.

    default: VlabsMediaBundle:Templates:default.html.twig  # return the media path
    image: VlabsMediaBundle:Templates:image.html.twig  # return a `img` tag with path as src
    form_doc: VlabsMediaBundle:Form:form_doc.html.twig # return a `a` tag with path as href and 'View' as label 
    form_image: VlabsMediaBundle:Form:form_image.html.twig # return a `img` tag with path as src
    vlabs_file: VlabsMediaBundle:Form:vlabs_file.html.twig
    vlabs_del: VlabsMediaBundle:Form:vlabs_del_file.html.twig

The last four lines are template used in forms.


###The vlabs_filter() filter

For all your images, you can use the vlabs_filter() filter to trigger resizing & caching for your media :

    {{ entity.image|vlabs_filter(<filter_name>, <options_array> ) }}
    
The bundle provides two buit-in filters :

+    **resize** wich handle 4 parameters : width (numeric) / height (numeric) / upscale (boolean) / keepRatio (boolean)
+    **crop** wich handle 2 mandatory parameters : width (numeric) / heigth (numeric)

Note that you can combine filter & media, every filter deals with a BaseFileInterface :

    {{ entity.image|vlabs_filter('resize', { width : 300, height : 300, keepRatio : true } )|vlabs_media('image') }}


###Using form templates & twig form helper

To manage your media in forms, two templates are available:

+    **[VlabsMediaBundle:Form:vlabs_file.html.twig](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/views/Form/vlabs_file.html.twig)** : the base template for the vlabs_file type. Display an image preview if it's one (90x90) or use the **form_doc** base template to render other medias.
+    **[VlabsMediaBundle:Form:vlabs_del_file.html.twig](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/views/Form/vlabs_del_file.html.twig)** : the base template for the delete checkbox. Should be overrided with your needs.

Both templates are tagged as `form.type` and can be used in form process.

The **vlabs_file** template is using a helper called **getBaseFile** to retrieve the **BaseFileInterface** object before calling the twig filters.


Documentation
-------------

+   [Installation & usage](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/1-bundle-setup-and-usage.md)
+   [Configuration reference](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/2-configuration-reference.md)
+   [Templating](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/3-templating.md)
+   [Deleting Media](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/4-deleting-media.md)
+   [Custom and/or stand alone handlers](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/5-custom-stand-alone-handlers.md)
+   [Custom filters](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/6-custom-stand-alone-filters.md)
+   [Gaufrette handler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/7-gaufrette-handler.md)