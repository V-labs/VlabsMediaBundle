###From 1.0 to 1.1

Twig methods media() and formPreview() no longer exist for the benefit of vlabs_filter() & vlabs_media() twig filters.

Calls like this:

    {{ media(entity.image, 'image', { resize : { 'width' : 300, 'height' : 300 }, name : 'John Doe' } ) }}

must be replaced by:

    {{ entity.image|vlabs_filter('resize',  { 'width' : 300, 'height' : 300 } )|vlabs_media('image', { name : 'John Doe' } ) }}

The template form field [vlabs_file](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/views/Form/vlabs_file.html.twig) is also changed. Please update yours if you overided it.