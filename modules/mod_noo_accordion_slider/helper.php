<?php
/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_accordion_slider
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

require_once JPATH_SITE . '/components/com_content/helpers/route.php';

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');



abstract class modNooAccordionSliderHelper {
	public static function getList(&$params){
		$data = array();
		$display_form = strtolower($params->get('display_form','joomla_content'));
		if ($display_form == 'joomla_content'){
			 if ($params->get('enable_cache')) {
			 	$cache = JFactory::getCache();
                $cache->setCaching(true);
                $cache->setLifeTime($params->get('cache_time', 30) * 60);
                $rows = $cache->get(array((new modNooAccordionSliderHelper()), 'getListCategories'), array($params));

			 }else{
			 	$data = modNooAccordionSliderHelper::getListCategories($params);
			 }
		}else if ($display_form=='k2'){
			if ($params->get('enable_cache')) {
			 	$cache = JFactory::getCache();
                $cache->setCaching(true);
                $cache->setLifeTime($params->get('cache_time', 30) * 60);
                $rows = $cache->get(array((new modNooAccordionSliderHelper()), 'getK2ListCategories'), array($params));

			 }else{
			 	$data = modNooAccordionSliderHelper::getK2ListCategories($params);
			 }
		}else if ($display_form == 'folder_image'){
			$data = self::getImageFolder($params);
		}
		return $data;
	}
	
	public static function getK2ListCategories(&$params) {
		if (class_exists('K2Model')){
			if (file_exists(JPATH_SITE.'/components/com_k2/helpers/route.php')){
				require_once (JPATH_SITE.'/components/com_k2/helpers/route.php');
			}
			jimport('joomla.filesystem.file');
			$app = JFactory::getApplication();
			$user = JFactory::getUser();
			$db = JFactory::getDbo();

			$jnow = JFactory::getDate();
			$now = $jnow->toSql();
			$nullDate = $db->getNullDate();

			$query = $db->getQuery(true);

			$cids = $params->get('k2catid',array());

			JArrayHelper::toInteger($cids);

			if (count($cids) > 0){
				foreach ($cids as $k=>$cid){
					if (!$cid)
						unset($cids[$k]);
				}
			}

			$query->select('k2c.*,k2c.name AS title')
				->from('#__k2_categories AS k2c');

			if (count($cids) > 0){
				$query->where('k2c.id IN ('.implode(',',$cids).')');
			}

			$query->where('k2c.published = 1')
				->where('k2c.trash = 0')
				->where("k2c.access IN(".implode(',', $user->getAuthorisedViewLevels()).")");
			if ($app->getLanguageFilter())
			{
				$languageTag = JFactory::getLanguage()->getTag();
				$query->where("k2c.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').")");
			}
			$ordering = $params->get('sort_order_field','order DESC');
			$query->order('k2c.'.$ordering);

			$db->setQuery($query);
			//$categories = new stdClass();
			$categories = $db->loadObjectList();
			//var_dump($categories);die;
			foreach ($categories as $category){
				$category->image = '';
				$category->link = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($category->id.':'.urlencode($category->alias))));
				if ($params->get('show_image',1)){
					if (JFile::exists(JPATH_SITE .'/media/k2/categories/'.$category->id . '.jpg')){
	               		$image = JURI::root() . 'media/k2/categories/' . $category->id . '.jpg';
	               		$category->image = modNooAccordionSliderHelper::renderImage($category->name,$category->link, $image, $params, $params->get('image_width'), $params->get('image_height'));
					}else{
						$category->image = '';
					}
				}

				$category->description = self::trimChar($category->description,$params->get('description_max_chars',0));
			}
			return $categories;
		}
		return array();
	}
	
	/**
	 * Method get list image of folder
	 * 
	 * @param object $params
	 * 
	 * @return array images
	 */
	public static function getImageFolder(&$params) {
		$list = array();
		$images = new stdClass();
		if ($params->get('path_folder.images')){
			$images = json_decode($params->get('path_folder.images'));
		}
		$folder = $params->get('path_folder.folder');
		foreach ($images as $image){
			$image->description = $image->description;
			$image->image = self::renderImage($image->title, $image->link,$folder.'/'.$image->image, $params, $params->get('image_width', 100), $params->get('image_height', 100));
			$list[$image->position] = $image;
		}
		ksort($list);
		return $list;
	}
	
	/**
	 * Method get list articles
	 * @param array $params
	 * 
	 * @return array $items
	 */
	public static function getListCategories(&$params){
		
		$dispatcher	= JEventDispatcher::getInstance();
		
		// Get the dbo
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$cids = $params->get('catid',array());

		JArrayHelper::toInteger($cids);
		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));
		$model->setState('filter.published', 1);
		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);
		if (count($cids) > 0){
			foreach ($cids as $k=>$cid){
				if (!$cid)
					unset($cids[$k]);
			}
		}

		$query->select('joomlac.*, joomlac.lft AS ordering')
			->from('#__categories AS joomlac');

		if (count($cids) > 0){
			$query->where('joomlac.id IN ('.implode(',',$cids).')');
		}

		$query->where('joomlac.published = 1')
			->where("joomlac.access IN(".implode(',', $user->getAuthorisedViewLevels()).")");
		if ($app->getLanguageFilter())
		{
			$languageTag = JFactory::getLanguage()->getTag();
			$query->where("joomlac.language IN (".$db->Quote($languageTag).", ".$db->Quote('*').")");
		}
		$ordering = $params->get('sort_order_field','lft DESC');
		if ($ordering == 'ordering DESC') {
			$ordering = 'lft DESC';
		}
		$query->order('joomlac.'.$ordering);

		$db->setQuery($query);
		//$categories = new stdClass();
		$categories = $db->loadObjectList();

		foreach ($categories as $category)
		{
			
			if ($access || in_array($category->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$category->link = JRoute::_(ContentHelperRoute::getCategoryRoute($category->id));
			}
			else
			{
				$category->link = JRoute::_('index.php?option=com_users&view=login');
			}
			$category->image	= '';
			if ($params->get('show_image',1)){
				//get Params
				$tmp_params = new JRegistry;
				$tmp_params->loadString($category->params);

				if ($image	= $tmp_params->get('image')) {
	                $category->image = modNooAccordionSliderHelper::renderImage($category->title,$category->link, $image, $params, $params->get('image_width'), $params->get('image_height'));
	            } else {
	                $category->image = '';
	            }
			}
			$category->description = self::trimChar($category->description,$params->get('description_max_chars',0));

		}

		return $categories;
	}
	
	/**
     * Render image before display it
     * 
     * @param string $title
     * @param string $link
     * @param string $image
     * @param object $params
     * @param int $width
     * @param int $height
     * @param string $attrs
     * @param string $returnURL
     * 
     * @return string image
     */
    public static function renderImage($title, $link, $image, $params, $width = 0, $height = 0, $attrs = '', $returnURL = false){
    	if ($image) {
            $title = strip_tags($title);
            $thumbnailMode = $params->get('thumbnail_mode', 'crop');
            $aspect = $params->get('use_ratio', '1');
            $crop = $thumbnailMode == 'crop' ? true : false;
            $imageHelper = NooImageHelper::getInstance();
           
            if ($thumbnailMode != 'none' && $imageHelper->sourceExited($image)) {            	
                $imageURL = $imageHelper->resize($image, $width, $height, $crop, $aspect);                
                if ($returnURL) {
					
                    return $imageURL;
                }
                if ($imageURL != $image && $imageURL) {
                    $width = $width ? "width=\"$width\"" : "";
                    $height = $height ? "height=\"$height\"" : "";
                    $image = "<img src=\"{$imageURL}\"  alt=\"{$title}\" title=\"{$title}\" {$width} {$height} {$attrs} />";
                } else {
                    $image = "<img $attrs src=\"{$image}\"  $attrs  alt=\"{$title}\" title=\"{$title}\" />";
                }
            } else {
                if ($returnURL) {
                    return $image;
                }
                $width = $width ? "width=\"$width\"" : "";
                $height = $height ? "height=\"$height\"" : "";
                $image = "<img $attrs src=\"$image\" alt=\"{$title}\"   title=\"{$title}\" {$width} {$height} />";
            }
        } else {
            $image = '';
        }
    	if ($params->get('linked_image',1)){
			$image = '<a href="' . $link . '" title="' . $title . '">' . $image . '</a>';
		}
        // clean up globals
        return $image;
    }
    
	/**
	 * Method trim string with max specify
	 * @param string $string
	 * @param int $maxChar
	 * 
	 * @return string
	 */
	public static function trimChar($string,$maxChar = 50){
		
		if ($maxChar == '-1')
			return strip_tags($string);
		
		if ($maxChar == 0)
			return '';
			
		if (strlen($string) > $maxChar)
			return JString::substr(strip_tags($string),0,$maxChar).' ...';
			
		return $string;
	}
}

if (!class_exists('NooImageHelper')){
	if (!defined('DS'))
		define('DS',DIRECTORY_SEPARATOR);
		
	 jimport('joomla.filesystem.file');
	 jimport('joomla.filesystem.folder');
	 
	class NooImageHelper {
	/**
         * Identifier of the cache path.
         *
         * @access private
         * @param string $_cachePath
         */
        var $_cachePath;

        /**
         * Identifier of the path of source.
         *
         * @access private
         * @param string $_imageBase
         */
        var $_imageBase;

        /**
         * Identifier of the image's extensions
         *
         * @access public
         * @param array $types
         */
        var $types = array();

        /**
         * Identifier of the quantity of thumnail image.
         *
         * @access public
         * @param string $_quality
         */
        var $_quality = 90;

        /**
         * Identifier of the url of folder cache.
         *
         * @access public
         * @param string $_cacheURL
         */
        var $_cacheURL;


        /**
         * constructor
         */
        function __construct()
        {
            $this->types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp");
            $this->_imageBase = JPATH_SITE . DS .'/images' . DS;
            $this->_cachePath = $this->_imageBase . 'resized' . DS;
            $this->_cacheURL = 'images/resized/';
        }


        /**
         * get a instance of NooImageHelper object.
         *
         * This method must be invoked as:
         * <pre>  $NooImageHelper = &NooImageHelper::getInstace();</pre>
         *
         * @static.
         * @access public,
         */
        public static function &getInstance()
        {
            static $instance = null;
            if (!$instance) {
                $instance = new NooImageHelper();
            }
            return $instance;
        }


        /**
         * crop or resize image
         *
         *
         * @param string $image path of source.
         * @param integer $width width of thumnail
         * @param integer $height height of thumnail
         * @param boolean $aspect whether to render thumnail base on the ratio
         * @param boolean $crop whether to use crop image to render thumnail.
         * @access public,
         */
        function resize($image, $width, $height, $crop = true, $aspect = true)
        {
            // get image information


            if (!$width || !$height)
                return '';

            $image = str_replace(JURI::base(), '', $image);

            $imagSource = JPATH_SITE . DS . str_replace('/', DS, $image);

            if (!file_exists($imagSource) || !is_file($imagSource)) {
                return '';
            }
            $filetime = filemtime($imagSource);
            $size = getimagesize($imagSource);
            // if it's not a image.
            if (!$size) {
                return '';
            }

            // case 1: render image base on the ratio of source.
            $x_ratio = $width / $size[0];
            $y_ratio = $height / $size[1];

            // set dst, src
            $dst = new stdClass();
            $src = new stdClass();
            $src->y = $src->x = 0;
            $dst->y = $dst->x = 0;

            if ($width > $size[0])
                $width = $size[0];
            if ($height > $size[1])
                $height = $size[1];

            if ($crop) { // processing crop image
                $dst->w = $width;
                $dst->h = $height;
                if (($size[0] <= $width) && ($size[1] <= $height)) {
                    $src->w = $width;
                    $src->h = $height;
                } else {
                    if ($x_ratio < $y_ratio) {
                        $src->w = ceil($width / $y_ratio);
                        $src->h = $size[1];
                    } else {
                        $src->w = $size[0];
                        $src->h = ceil($height / $x_ratio);
                    }
                }
                $src->x = floor(($size[0] - $src->w) / 2);
                $src->y = floor(($size[1] - $src->h) / 2);
            } else { // processing resize image.
                $src->w = $size[0];
                $src->h = $size[1];
                if ($aspect) { // using ratio
                    if (($size[0] <= $width) && ($size[1] <= $height)) {
                        $dst->w = $size[0];
                        $dst->h = $size[1];
                    } else if (($size[0] <= $width) && ($size[1] <= $height)) {
                        $dst->w = $size[0];
                        $dst->h = $size[1];
                    } else if (($x_ratio * $size[1]) < $height) {
                        $dst->h = ceil($x_ratio * $size[1]);
                        $dst->w = $width;
                    } else {
                        $dst->w = ceil($y_ratio * $size[0]);
                        $dst->h = $height;
                    }
                } else { // resize image without the ratio of source.
                    $dst->w = $width;
                    $dst->h = $height;
                }
            }
            //
            $ext = substr(strrchr($image, '.'), 1);
            $thumnail = substr($image, 0, strpos($image, '.')) . "_{$width}_{$height}." . $ext;
            $imageCache = $this->_cachePath . str_replace('/', DS, $thumnail);

            if (file_exists($imageCache)) {
            	$filetimecache = filemtime($imageCache);
            	if($filetime<$filetimecache){
	                $smallImg = getimagesize($imageCache);
	                if (($smallImg[0] == $dst->w && $smallImg[1] == $dst->h)) {
	                    return $this->_cacheURL . $thumnail;
	                }
            	}
            }

            if (!file_exists($this->_cachePath) && !JFolder::create($this->_cachePath)) {
                return '';
            }

            if (!$this->makeDir($image)) {
                return '';
            }

            // resize image
            $this->_resizeImage($imagSource, $src, $dst, $size, $imageCache);

            return $this->_cacheURL . $thumnail;
        }


        /**
         * render image from other server. // this is pending.
         *
         * @param string $url the url of image.
         * @param array $host contain server information ( using parse_url() function to return this value )
         * @access public,
         */
        function resizeLinkedImage($url, $host)
        {

            if (!is_dir($this->_imageBase . DS . 'linked_images' . DS)) {
                if (!mkdir($this->_imageBase . DS . 'linked_images' . DS, 0755)) {
                    return '';
                }
            }
            //	mkdir($this->_imageBase . DS . 'linked_images'.DS . $host['host'] . DS, 0755);
            //
            $filePath = $this->_imageBase . 'linked_images/' . $host['host'] . '/' . 'testthu.jpg';
            JFile::exists($filePath);
            $source = file_get_contents($url);
            JFile::write($filePath, $source);
            $files = 'images/linked_images/' . $host['host'] . '/testthu.jpg';

            $output = $this->resize($files, 160, 80);

            //	if( $this->_storeImage ){
            //	JFile::delete( $filePath  );
            //	}


            return $output;
        }


        /**
         * check the folder is existed, if not make a directory and set permission is 755
         *
         *
         * @param array $path
         * @access public,
         * @return boolean.
         */
        function makeDir($path)
        {
            $folders = explode('/', ($path));
            $tmppath = $this->_cachePath;
            for ($i = 0; $i < count($folders) - 1; $i++) {
                if (!file_exists($tmppath . $folders[$i]) && !JFolder::create($tmppath . $folders[$i], 0755)) {
                    return false;
                }
                $tmppath = $tmppath . $folders[$i] . DS;
            }
            return true;
        }


        /**
         * process render image
         *
         * @param string $imageSource is path of the image source.
         * @param stdClass $src the setting of image source
         * @param stdClass $dst the setting of image dts
         * @param string $imageCache path of image cache ( it's thumnail).
         * @access public,
         */
        function _resizeImage($imageSource, $src, $dst, $size, $imageCache)
        {
            // create image from source.
            $extension = $this->types[$size[2]];
            $image = call_user_func("imagecreatefrom" . $extension, $imageSource);

            if (function_exists("imagecreatetruecolor") && ($newimage = imagecreatetruecolor($dst->w, $dst->h))) {

                if ($extension == 'gif' || $extension == 'png') {
                    imagealphablending($newimage, false);
                    imagesavealpha($newimage, true);
                    $transparent = imagecolorallocatealpha($newimage, 255, 255, 255, 127);
                    imagefilledrectangle($newimage, 0, 0, $dst->w, $dst->h, $transparent);
                }

                imagecopyresampled($newimage, $image, $dst->x, $dst->y, $src->x, $src->y, $dst->w, $dst->h, $src->w, $src->h);
            } else {
                $newimage = imagecreate($src->w, $src->h);
                imagecopyresized($newimage, $image, $dst->x, $dst->y, $src->x, $src->y, $dst->w, $dst->h, $size[0], $size[1]);
            }

            switch ($extension) {
                case 'jpeg':
                    call_user_func('image' . $extension, $newimage, $imageCache, $this->_quality);
                    break;
                default:
                    call_user_func('image' . $extension, $newimage, $imageCache);
                    break;
            }
            // free memory
            imagedestroy($image);
            imagedestroy($newimage);
        }


        /**
         * set quality image will render.
         */
        function setQuality($number = 9)
        {
            $this->_quality = $number;
        }


        /**
         * check the image is a linked image from other server.
         *
         *
         * @param string the url of image.
         * @access public,
         * @return array if it' linked image, return false if not
         */
        function isLinkedImage($imageURL)
        {
            $parser = parse_url($imageURL);
            return strpos(JURI::base(), $parser['host']) ? false : $parser;
        }


        /**
         * check the file is a image type ?
         *
         * @param string $ext
         * @return boolean.
         */
        function isImage($ext = '')
        {
            return in_array($ext, $this->types);
        }


        /**
         * check the image source is existed ?
         *
         * @param string $imageSource the path of image source.
         * @access public,
         * @return boolean,
         */
        function sourceExited($imageSource)
        {

            if ($imageSource == '' || $imageSource == '..' || $imageSource == '.') {
                return false;
            }
            $imageSource = str_replace(JURI::base(), '', $imageSource);
            $imageSource = rawurldecode($imageSource);
            return (file_exists(JPATH_SITE . '/' . $imageSource));
        }


        /**
         * check the image source is existed ?
         *
         * @param string $imageSource the path of image source.
         * @access public,
         * @return boolean,
         */
        function parseImage($row)
        {
            //check to see if there is an  intro image or fulltext image  first
			$images = "";
			if (isset($row->images)) {
				$images = json_decode($row->images);
			}			
			if((isset($images->image_fulltext) and !empty($images->image_fulltext)) || (isset($images->image_intro) and !empty($images->image_intro))){
				$image = (isset($images->image_intro) and !empty($images->image_intro))?$images->image_intro:((isset($images->image_fulltext) and !empty($images->image_fulltext))?$images->image_fulltext:"");
			}
			else {
				$regex = '/\<img.+src\s*=\s*\"([^\"]*)\"[^\>]*\>/';
				$text = '';
				$text .= (isset($row->fulltext))?$row->fulltext:'';
				$text .= (isset($row->introtext))?$row->introtext:'';
				preg_match($regex, $text, $matches);
				$images = (count($matches)) ? $matches : array();
				$image = count($images) > 1 ? $images[1] : '';
			}
			return $image;
        }
	}
}