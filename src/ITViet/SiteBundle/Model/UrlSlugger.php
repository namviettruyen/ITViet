<?php

namespace ITViet\SiteBundle\Model;

class UrlSlugger
{
    private $charConverter;

    public function __construct($charConverter)
    {
        $this->charConverter = $charConverter;
    }

    public function slug($str, $params)
    {
        if ($params['toascii'])
            $str = $this->charConverter->toPlainLatin($str);

        $str = str_replace(array('(',')','-','=',',','/','#','?','+','!','@','$','%','^','&','*',"\t","\r","\n","　"), " ", $str);
        $str = preg_replace('/[\s]{2,}/', ' ', $str);
        $str = preg_replace('/[\s]$/', '', $str);
        $str = str_replace(' ', '-', $str);

        if ($params['tolower'])
            $str = strtolower($str);

        return $str;
    }
}
