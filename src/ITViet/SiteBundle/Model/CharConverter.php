<?php

namespace ITViet\SiteBundle\Model;

class CharConverter
{
    private $viChars;
    private $enChars;

    public function __construct() {
        $diacritics = array(
          $this->utf8(0x0300),
          $this->utf8(0x0301),
          $this->utf8(0x0309),
          $this->utf8(0x0323),
        );

        $this->viChars = array_merge($diacritics, array(
        'Ạ','Ắ','Ằ','Ặ','Ấ','Ầ','Ẩ','Ậ','Ẽ','Ẹ','Ế','Ề','Ể','Ễ','Ệ','Ố',
        'Ồ','Ổ','Ỗ','Ộ','Ợ','Ớ','Ờ','Ở','Ị','Ỏ','Ọ','Ỉ','Ủ','Ũ','Ụ','Ỳ',
        'Õ','ắ','ằ','ặ','ấ','ầ','ẩ','ậ','ẽ','ẹ','ế','ề','ể','ễ','ệ','ố',
        'ồ','ổ','ỗ','Ỡ','Ơ','ộ','ờ','ở','ị','Ự','Ứ','Ừ','Ử','ơ','ớ','Ư',
        'À','Á','Â','Ã','Ả','Ă','ẳ','ẵ','È','É','Ê','Ẻ','Ì','Í','Ĩ','ỳ',
        'Đ','ứ','Ò','Ó','Ô','ạ','ỷ','ừ','ử','Ù','Ú','ỹ','ỵ','Ý','ỡ','ư',
        'à','á','â','ã','ả','ă','ữ','ẫ','è','é','ê','ẻ','ì','í','ĩ','ỉ',
        'đ','ự','ò','ó','ô','õ','ỏ','ọ','ụ','ù','ú','ũ','ủ','ý','ợ','Ữ',
        ));

        $this->enChars = array_merge(array_map(function($c){return '';},$diacritics), array(
        'A','A','A','A','A','A','A','A','E','E','E','E','E','E','E','O',
        'O','O','O','O','O','O','O','O','I','O','O','I','U','U','U','Y',
        'O','a','a','a','a','a','a','a','e','e','e','e','e','e','e','o',
        'o','o','o','O','O','o','o','o','i','U','U','U','U','o','o','U',
        'A','A','A','A','A','A','a','a','E','E','E','E','I','I','I','y',
        'D','u','O','O','O','a','y','u','u','U','U','y','y','Y','o','u',
        'a','a','a','a','a','a','u','a','e','e','e','e','i','i','i','i',
        'd','u','o','o','o','o','o','o','u','u','u','u','u','y','o','U',
        ));

        $_a = '([aắằặấầẩậẳẵạàáâãảăẫ]|&[Aa](acute|grave|circ);)';
        $_e = '([eẽẹếềểễệèéêẻ]|&[Ee](acute|grave|circ);)';
        $_o = '([oốồổỗộờởơớỡòóôõỏọợ]|&[Oo](acute|grave|circ);)';
        $_i = '([iịìíĩỉ]|&[Ii](acute|grave|circ);)';
        $_u = '([uứừửưữựụùúũủ]|&[Uu](acute|grave|circ);)';
        $_y = '([yỳỷỹỵý])';
        $_d = '([dđ])';

        $this->latin1Seeker = array(
          '/'.$_a.'/iu',
          '/'.$_e.'/iu',
          '/'.$_o.'/iu',
          '/'.$_i.'/iu',
          '/'.$_u.'/iu',
          '/'.$_y.'/iu',
          '/'.$_d.'/iu',
        );

        $this->latin1Matcher = array(
          $_a,
          $_e,
          $_o,
          $_i,
          $_u,
          $_y,
          $_d,
        );

        $this->normFrom = array(
          '’',
        );

        $this->normTo = array(
          "'",
        );

        $this->htmlCharRefs = array(
          '&nbsp;','&amp;',
          '&Aacute;', '&aacute;',
          '&Eacute;', '&eacute;',
          '&Oacute;', '&oacute;',
          '&Iacute;', '&iacute;',
          '&Uacute;', '&uacute;',
          '&Yacute;', '&yacute;',
          '&Agrave;', '&agrave;',
          '&Egrave;', '&egrave;',
          '&Ograve;', '&ograve;',
          '&Igrave;', '&igrave;',
          '&Ugrave;', '&ugrave;',
          '&Acirc;', '&acirc;',
          '&Ecirc;', '&ecirc;',
          '&Ocirc;', '&ocirc;',
          '&Icirc;', '&icirc;',
          '&Ucirc;', '&ucirc;',
        );

        $this->htmlChars = array(
          ' ', '&',
          'Á', 'á',
          'É', 'é',
          'Ó', 'ó',
          'Í', 'í',
          'Ú', 'ú',
          'Ý', 'ý',
          'À', 'à',
          'È', 'è',
          'Ò', 'ò',
          'Ì', 'ì',
          'Ù', 'ù',
          'Â', 'â',
          'Ê', 'ê',
          'Ô', 'ô',
          'Î', 'î',
          'Û', 'û',
        );
    }

    public function normalize($str) {
        return str_replace($this->normFrom, $this->normTo, $str);
    }

    public function htmlRefToChar($str) {
        return str_replace($this->htmlCharRefs, $this->htmlChars, $str);
    }

    public function toPlainLatin($str) {
        return str_replace($this->viChars, $this->enChars, $str);
    }

    public function toLatin1Matcher($str) {
        $str = preg_quote($str, '/');
        return preg_replace($this->latin1Seeker, $this->latin1Matcher, $str);
    }

    public function isAllAscii($str) {
        return preg_match('/[^\x00-\x7e]/', $str) == 0 ? true : false;
    }

    private function utf8($num) {
        if($num<=0x7F)       return chr($num);
        if($num<=0x7FF)      return chr(($num>>6)+192).chr(($num&63)+128);
        if($num<=0xFFFF)     return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
        if($num<=0x1FFFFF)   return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128).chr(($num&63)+128);
        return '';
    }
}
