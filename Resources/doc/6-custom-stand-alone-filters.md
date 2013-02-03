Custom filters
----------------------------------

###Create your own filter

Each filter must be a service, and must implement the [FilterInterface](https://github.com/V-labs/VlabsMediaBundle/blob/master/Filter/FilterInterface.php).

The bundle currently works with two filters : image resizing & image cropping. You can imagine every filters you want, a filter is just another representation of a media.
It also deals with cache for storing its new representation (resized file for exemple).

To create your own filter, just declare it as a service in your bundle configuration, and tag it with the **vlabs_media.filter** keyword.
You also have to define an **alias** which will be used by the twig extension in your views.

services.xml

    <service id="acme_media.filter.my_filter" class="%acme_media.filter.my_filter.class%" parent="vlabs_media.filter.abstract_filter">
        <tag name="vlabs_media.filter" alias="my_filter" />
    </service>

Here we are extending the [AbstractFilter](https://github.com/V-labs/VlabsMediaBundle/blob/master/Filter/AbstractFilter.php) provided by the bundle.
He is already handling directory creation into the cache subfolders, and cached file retrieval.

He is also calling the **doFilter()** method which is the real filter. Check the [resize filter](https://github.com/V-labs/VlabsMediaBundle/blob/master/Filter/ImageResizeFilter.php) for exemple.


If you want to reimplement the entire interface, you must inject the **cache_dir** directory from the container (the one you specified in the configuration).

    <service id="acme_media.filter.my_filter" class="%acme_media.filter.my_filter.class%">
        <argument>%vlabs_media.cache_dir%</argument>
        <tag name="vlabs_media.filter" alias="my_filter" />
    </service>

Every filter can be used as a stand alone service.

###Use your filter

In twig, you can now use your filter by calling :

    {{ entity.media|vlabs_filter('my_filter', { option1 : 'foo', option2: 'bar' }} ) }}

Documentation
-------------

+   [Installation & usage](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/1-bundle-setup-and-usage.md)
+   [Configuration reference](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/2-configuration-reference.md)
+   [Templating](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/3-templating.md)
+   [Deleting Media](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/4-deleting-media.md)
+   [Custom and/or stand alone handlers](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/5-custom-stand-alone-handlers.md)
+   [Custom filters](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/6-custom-stand-alone-filters.md)
+   [Gaufrette handler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/7-gaufrette-handler.md)