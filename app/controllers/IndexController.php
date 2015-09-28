<?php

class IndexController extends BaseController{

    public function getIndex() {
        $posters = Poster::orderBy('created_at', 'DESC')->get();

        return View::make('index', array(
            'posters'  => $posters
        ));
    }
}