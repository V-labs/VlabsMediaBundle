Deleting media
--------------

###Automatically display a checkbox for deletion

In your entity form type, you can use two options provided by the bundle to automatically display a non mapped checkbox under your media :

    ->add('document', 'vlabs_file', array(
                'required' => false,
                'add_del' => true,
                'del_label' => 'Delete ?'
            ))
            
###Real deletion

This part needs to be improved, actually the removal is not done automatically. You must always handle it in your controller.

For example, if your form is called `image`, you must call the following code to permanently delete the media :

    if ($form->isValid()) {
        if($form->has('delImage') && $form->get('delImage')->getData() == true) {
            $entity->setImage(null);
        }
        
        $em->persist($entity);
        $em->flush();
        
        return $this->redirect($this->generateUrl('foo'));
    }
    
The del file name will always be **delFieldName**

Note that if it's an image, it will also be removed from cache.

Documentation
-------------

+   [Installation & usage](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/1-bundle-setup-and-usage.md)
+   [Configuration reference](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/2-configuration-reference.md)
+   [Templating](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/3-templating.md)
+   [Deleting Media](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/4-deleting-media.md)
+   [Custom and/or stand alone handlers](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/5-custom-stand-alone-handlers.md)
+   [Custom filters](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/6-custom-stand-alone-filters.md)
+   [Gaufrette handler](https://github.com/V-labs/VlabsMediaBundle/blob/master/Resources/doc/7-gaufrette-handler.md)