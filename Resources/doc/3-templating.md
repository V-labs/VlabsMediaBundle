Templating
----------

###Display your media using templates

There is on main twig function to manipulate your media in templates :

    {{ media(<media_objet>, <template>, <options_array>) }}

+    **media_objet** : for exemple, entity.image
+    **template** : Here you can either use the identifier of the template defined in the configuration, either the full path to a template (if you do not want to set it in the configuration).

So if your configuration looks like :

    templates:
            form_doc: MyFooBundle:Templates:form_doc.html.twig
            image: MyFooBundle:Templates:image.html.twig

Those 3 calls will works :

    {{ media(entity.image, 'image') }}
    {{ media(entity.doc, 'MyFooBundle:Templates:form_doc.html.twig') }}
    {{ media(entity.resume, 'MyFooBundle:Templates:pdf.html.twig') }}

Note that the third file must exists.


###Using media properties and templates options

The third parameter is an array. You can pass all the data that you want, they will be accesible in the template. The only specific parameter is **resize** which triggers resizing and caching.
You also have access to all the media properties.
    
    {{ media(entity.image, 'image', { resize : { 'width' : 300, 'height' : 300 }, name : 'John Doe' } ) }}

will give you access in the `MyFooBundle:Templates:image.html.twig` template to :

    <img src="{{ media.path }}" alt="{{ options.name }}" />

If your media is extending from [Vlabs\MediaBundle\Entity\BaseFile](https://github.com/V-labs/VlabsMediaBundle/blob/master/Entity/BaseFile.php), you will also have access to all this properties.


There are five templates provided by default with the bundle, you can (must?) replace. Just change the path of template in the configuration.

    default: VlabsMediaBundle:Templates:default.html.twig
    image: VlabsMediaBundle:Templates:image.html.twig
    form_doc: VlabsMediaBundle:Templates:form_doc.html.twig
    vlabs_file: VlabsMediaBundle:Form:vlabs_file.html.twig
    vlabs_del: VlabsMediaBundle:Form:vlabs_del_file.html.twig

The last two lines are template used in forms.


###Using form templates & twig form helper


