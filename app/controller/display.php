<?php
/**
 * Controller das galerias do app
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Display
{
    /**
     * Construtor
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Exibição de fotos da galeria
     *
     * @return void
     */
    public function gallery($gallery_id, $trash, $position)
    {
        $Photo = new \App\PhotoView($gallery_id, $position);
        $Gallery = $Photo->getGallery();

        $data['Photo'] = $Photo;
        $data['Gallery'] = $Gallery;
        \App\View::call('photo', $data);
    }
}
