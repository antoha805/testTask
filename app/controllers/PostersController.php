<?php

/**
 * Created by PhpStorm.
 * User: antoha805
 * Date: 03.09.2015
 * Time: 19:26
 */
class PostersController extends BaseController
{
    public function getAdd()
    {
        return View::make('posters/add');
    }

    public function postAdd()
    {
        $data = Input::all();
        $validation = Validator::make($data, Poster::getValidationRules());
        if ($validation->fails()) {
            return View::make('message', array('OKs' => [], 'errors' => $validation->errors()->all()));
        }
        $image_dir = 'images/';
        $image_sml_name = "no_image_sml.jpg";
        $image_name = "no_image.jpg";
        if (Request::hasFile('image')) {
            $image = Request::file('image');
            $image_name = time().$image->getBasename();
            $image->move($image_dir, $image_name);

        }
        $data['image_sml'] = $image_dir.$image_sml_name;
        $data['image'] = $image_dir.$image_name;
        $data['author_ip'] = $this->getIP();
        $data['author_browser'] = $_SERVER['HTTP_USER_AGENT'];
        $data['author_country'] = "Ukraine";
        Poster::create($data);
        return View::make('message', array('OKs' => ['Poster created'], 'errors' => ['']));


        /*$data = Input::all();

        $validation = Validator::make($data, Poster::getValidationRules());
        if ($validation->fails()) {
            return View::make('message', array('OKs' => [], 'errors' => $validation->errors()->all()));
        }
        $image_sml = "images/no_image_sml.jpg";
        $image = "images/no_image.jpg";
        if (!empty($_FILES['image']['name'])) {
            $date = time();
            Input::upload('image', 'images', $date.$_FILES['image']['name']);

            if (preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/', $_FILES['image']['name'])) {
                $filename = $_FILES['image']['name'];
                $source = $_FILES['image']['tmp_name'];
                $image = $path_directory . $date . $filename;
                move_uploaded_file($source, $image);
                $im = null;
                if (preg_match('/[.](GIF)|(gif)$/', $filename)) $im = imagecreatefromgif($image);
                if (preg_match('/[.](PNG)|(png)$/', $filename)) $im = imagecreatefrompng($image);
                if (preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) $im = imagecreatefromjpeg($image);
                $w = 100;
                $w_src = imagesx($im);
                $h_src = imagesy($im);
                $dest = imagecreatetruecolor($w, $w);
                if ($w_src > $h_src)
                    imagecopyresampled($dest, $im, 0, 0,
                        round((max($w_src, $h_src) - min($w_src, $h_src)) / 2),
                        0, $w, $w, min($w_src, $h_src), min($w_src, $h_src));
                if ($w_src < $h_src)
                    imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w,
                        min($w_src, $h_src), min($w_src, $h_src));
                if ($w_src == $h_src)
                    imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src);
                imagejpeg($dest, $path_directory . $date . ".jpg");
                $image_sml = $path_directory . $date . ".jpg";
            } else return View::make('message', array('OKs' => [], 'errors' => ["Not correct image file"]));
        }
        $data['image_sml'] = $image_sml;
        $data['image'] = $image;
        $data['author_ip'] = $this->getIP();
        $data['author_browser'] = $_SERVER['HTTP_USER_AGENT'];
        $data['author_country'] = "Ukraine";
        Poster::create($data);
        return View::make('message', array('OKs' => ['Poster created'], 'errors' => ['']));*/
    }

    private function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getPoster($id)
    {
        $poster = Poster::find($id);
        if (!$poster) App::abort(404);
        return View::make('posters/view', array('poster' => $poster));
    }

    public function sortPosters($direction)
    {
        $posters = Poster::orderBy('created_at', $direction)->get();
        return View::make('posters/all_previews', array(
                'posters' => $posters)
        );
    }
}