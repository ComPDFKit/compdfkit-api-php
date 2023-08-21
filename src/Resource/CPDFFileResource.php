<?php

namespace ComPDFKit\Resource;

use ComPDFKit\Client\CPDFClient;
use ComPDFKit\Exception\CPDFException;

class CPDFFileResource
{
    public $filepath;

    public $fileKey;

    public $fileUrl;

    public $options;

    public $client;

    public function __construct($filepath, CPDFClient $client)
    {
        $this->client = $client;
        $this->filepath = $filepath;
    }

    /**
     * @param string $type Watermark type (text: text type watermark, image: image type watermark)
     * @return $this
     */
    public function setType($type){
        $this->options['type'] = $type;

        return $this;
    }

    /**
     * @param string $scale zoom (image type attribute)
     * @return $this
     */
    public function setScale($scale){
        $this->options['scale'] = $scale;

        return $this;
    }

    /**
     * @param string $opacity Transparency 0~1
     * @return $this
     */
    public function setOpacity($opacity){
        $this->options['opacity'] = $opacity;

        return $this;
    }

    /**
     * @param string $rotation Rotation angle, a positive number means counterclockwise rotation
     * @return $this
     */
    public function setRotation($rotation){
        $this->options['rotation'] = $rotation;

        return $this;
    }

    /**
     * @param string $targetPages Page number, page number from start, for example: 1,2,4,6
     * @return $this
     */
    public function setTargetPages($targetPages){
        $this->options['targetPages'] = $targetPages;

        return $this;
    }

    /**
     * @param string $vertalign Vertical alignment: top, center, bottom
     * @return $this
     */
    public function setVertalign($vertalign){
        $this->options['vertalign'] = $vertalign;

        return $this;
    }

    /**
     * @param string $horizalign Horizontal alignment: left, center, right
     * @return $this
     */
    public function setHorizalign($horizalign){
        $this->options['horizalign'] = $horizalign;

        return $this;
    }

    /**
     * @param string $xoffset horizontal offset
     * @return $this
     */
    public function setXOffset($xoffset){
        $this->options['xoffset'] = $xoffset;

        return $this;
    }

    /**
     * @param string $yoffset vertical offset
     * @return $this
     */
    public function setYOffset($yoffset){
        $this->options['yoffset'] = $yoffset;

        return $this;
    }

    /**
     * @param string $imagePath Image watermark path
     * @return $this
     */
    public function setImagePath($imagePath){
        $this->options['imagePath'] = $imagePath;

        return $this;
    }

    /**
     * @param string $content Text watermark text
     * @return $this
     */
    public function setContent($content){
        $this->options['content'] = $content;

        return $this;
    }

    /**
     * @param string $textColor Text color, eg: #FFFFFF
     * @return $this
     */
    public function setTextColor($textColor){
        $this->options['textColor'] = $textColor;

        return $this;
    }

    /**
     * @param string $front  Setting watermark layer
     * "0" false: No need to pin it to the top
     * "1" true: Pin it to the top
     * null  No need to pin it to the top
     * @return $this
     */
    public function setFront($front){
        $this->options['front'] = $front;

        return $this;
    }

    /**
     * @param string $fullScreen Whether to fill the entire page
     * @return $this
     */
    public function setFullScreen($fullScreen){
        $this->options['fullScreen'] = $fullScreen;

        return $this;
    }

    /**
     * @param string $horizontalSpace horizontal spacing
     * @return $this
     */
    public function setHorizontalSpace($horizontalSpace){
        $this->options['horizontalSpace'] = $horizontalSpace;

        return $this;
    }

    /**
     * @param string $verticalSpace vertical spacing
     * @return $this
     */
    public function setVerticalSpace($verticalSpace){
        $this->options['verticalSpace'] = $verticalSpace;

        return $this;
    }

    /**
     * @param string $extension Extended information, base 64 encoding
     * @return $this
     */
    public function setExtension($extension){
        $this->options['extension'] = $extension;

        return $this;
    }

    /**
     * @param mixed $pageOptions
     * @return $this
     */
    public function setPageOptions($pageOptions){
        $this->options['pageOptions'] = $pageOptions;

        return $this;
    }

    /**
     * @param string $targetPage page number
     * @return $this
     */
    public function setTargetPage($targetPage){
        $this->options['targetPage'] = $targetPage;

        return $this;
    }

    /**
     * @param string $width Page width (default 595)
     * @return $this
     */
    public function setWidth($width){
        $this->options['width'] = $width;

        return $this;
    }

    /**
     * @param string $height page height (842)
     * @return $this
     */
    public function setHeight($height){
        $this->options['height'] = $height;

        return $this;
    }

    /**
     * @param string $number Number of pages to insert (default 1)
     * @return $this
     */
    public function setNumber($number){
        $this->options['number'] = $number;

        return $this;
    }

    /**
     * @param string $quality Compressed document quality in the range 0-100, e.g. 50
     * @return $this
     */
    public function setQuality($quality){
        $this->options['quality'] = $quality;

        return $this;
    }

    /**
     * @param string $contentOptions extractContentOptions（1:OnlyText、2:OnlyTable、3:AllContent）
     * @return $this
     */
    public function setContentOptions($contentOptions){
        $this->options['contentOptions'] = $contentOptions;

        return $this;
    }

    /**
     * @param string $worksheetOptions createWorksheetOptions（1:ForEachTable、2:ForEachPage、3:ForTheDocument）
     * @return $this
     */
    public function setWorksheetOptions($worksheetOptions){
        $this->options['worksheetOptions'] = $worksheetOptions;

        return $this;
    }

    /**
     * @param string $isCsvMerge Whether to merge CSV (1: Yes, 0: No)
     * @return $this
     */
    public function setIsCsvMerge($isCsvMerge){
        $this->options['isCsvMerge'] = $isCsvMerge;

        return $this;
    }

    /**
     * @param string $lang language：Supported types and definitions.
     * <p>
     * auto - automatic classification language.
     * english - English.
     * chinese - Simplified Chinese.
     * chinese_tra - Traditional Chinese.
     * korean - Korean.
     * japanese - Japanese.
     * latin - Latin.
     * devanagari - Sanskrit alphabet.
     * @return CPDFFileResource
     */
    public function setLang($lang){
        $this->options['lang'] = $lang;

        return $this;
    }

    /**
     * @param string $isContainAnnot Typesetting method (1: flow layout, 0: box layout) Default box layout
     * @return $this
     */
    public function setIsContainAnnot($isContainAnnot){
        $this->options['isContainAnnot'] = $isContainAnnot;

        return $this;
    }

    /**
     * @param string $isContainImg Whether to include pictures (1: yes, 0: no)
     * @return $this
     */
    public function setIsContainImg($isContainImg){
        $this->options['isContainImg'] = $isContainImg;

        return $this;
    }

    /**
     * @param string $isFlowLayout Whether to include comments (1: Yes, 0: No)
     * @return $this
     */
    public function setIsFlowLayout($isFlowLayout){
        $this->options['isFlowLayout'] = $isFlowLayout;

        return $this;
    }

    /**
     * @param string $imgDpi Value range 72-1500 (default 300)
     * @return $this
     */
    public function setImgDpi($imgDpi){
        $this->options['imgDpi'] = $imgDpi;

        return $this;
    }

    /**
     * @param $taskId
     * @param null $password
     * @param null $language 1:English, 2:Chinese
     * @return CPDFClient
     * @throws CPDFException
     */
    public function uploadFile($taskId, $password = null, $language = null){
        $fileInfo = $this->client->uploadFile($taskId, $this->filepath, $password, $this->options, $language);
        $this->fileKey = $fileInfo['fileKey'];
        $this->fileUrl = $fileInfo['fileUrl'];

        return $fileInfo;
    }
}