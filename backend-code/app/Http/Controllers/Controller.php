<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function filter_data($array)
  	{
  		$data = array();
  		foreach ($array as $key => $value) {
  			if (is_array($value)) {
  				$data[$key] = $value;
  			} else {
          $data[$key] = str_replace(array("'", '"',"`", ')', '('), array("","","","",""), strip_tags(rawurldecode($value)));
  			}
  		}

  		return $data;
  	}

    public function filter_value($value)
    {
      $response = filter_var(str_replace(array("'", '"',"`", ')', '('), array("","","","",""), strip_tags(rawurldecode($value))), FILTER_SANITIZE_SPECIAL_CHARS);
      return $response;
    }

    public function html_filter($html)
  	{
  		$html = preg_replace('#(onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavaible|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragdrop|ondragend|ondragenter|ondragleave|ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterupdate|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmoveout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload)\\s*=\\s*".*?"#is', '', $html);

  		$html = strip_tags($html, "<a><h1><h2><h3><h4><h5><h6><th><td><tr><tfoot><thead><tbody><table><img><br><span><u><b><i><small><strong><em><div><li><ul><ol><hr><p><footer><header><body><title><head><html><blockquote><iframe>");

  		return $html;
  	}

    public function filter_az_lang_data($array)
  	{
  		$data = array();
  		foreach ($array as $key => $value) {
  			if (is_array($value)) {
  				$data[$key] = $value;
  			} else {
          $data[$key] = filter_var(str_replace(array("'", '"',"`", ')', '('), array("","","","",""), strip_tags(rawurldecode($value))), FILTER_SANITIZE_SPECIAL_CHARS);
  			}
  		}

  		return $data;
  	}

    public function upload_image($image, $image_path, $resize = false, $width = 0, $height = 0, $name = '')
    {
      $img_name_full = '';
      $extension = $image->getClientOriginalExtension();

      if ($name) {
        $img_name_full = $name;
      } else {
        $img_name = \Illuminate\Support\Str::random(25);
        $img_name_full = $img_name.'.'.$extension;
      }

      $path = public_path($image_path);
      if ($extension == 'svg') {
        $image->move($path, $img_name_full);
      } else {
        $new_image = \Image::make($image->getRealPath());

        if ($resize) {
          $new_image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
          });
        }
        $new_image->save($path.'/'.$img_name_full);
      }

      return $img_name_full;
    }

    public function upload_pdf($image, $image_path)
    {
      $img_name = \Illuminate\Support\Str::random(25);
      $extension = $image->getClientOriginalExtension();
      $img_name_full = $img_name.'.'.$extension;

      $path = public_path($image_path);
      if ($extension == 'pdf') {
        $image->move($path, $img_name_full);
      }

      return $img_name_full;
    }
}
