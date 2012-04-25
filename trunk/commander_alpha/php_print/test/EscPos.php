<?php
/**
 * Classe per creazione di stringhe formattate
 * per essere stamapate su pos.
 *
 * @version 1.0.0
 * @author Alessandro Sarzina - Francesco Falanga
 *
 *
 * USAGE EXAMPLE:
 *  	$esc=new EscPos("it",858,"àèìòù","\x7B\x7D\x7E\x7C\x60\xD5");	// initialize and select country, codepage and extra char trasformer string
 * 	$esc->align("c");			// central align
 * 	$esc->font(false,true,false,true,true);	// select bold, tall and large font
 * 	$esc->text("TITLE TEST");
 * 	$esc->font();
 * 	$esc->align();				// left align
 * 	$esc->text("Text test");
 * 	$esc->cut(30);				// 30 spaces and paper cut
 * 	$to_printer=$esc->out();		// get the generated string
 *
 */
class EscPos {

    private $out;
    private $font_small=false;
    private $font_large=false;
    private $translate_from=false;
    private $translate_to=false;

    /**
     * Constructor for escpos object
     *
     * @param $charcode		string(2char) for local charset selection [us, fr, de, uk, dk, sw, it, es, nw]
     * @param $codepage		int dos codepage selection [437, 850, 863, 865, 858]
     * @param $translate_from   [optional] string translate chars (strtr php function first argument)
     * @param $translate_to	[optional] string translate chars (strtr php function second argument)
     *
     */
    function __construct($charcode="it",$codepage=858,$translate_from=false,$translate_to=false){
//            $this->out=chr(27)."M".chr(48);
            $this->out="";
            if($translate_from && $translate_to && strlen($translate_from)*strlen($translate_to)>0){
                    $this->translate_from=$translate_from;
                    $this->translate_to=$translate_to;
            }
            $val=0;
            switch($charcode){
                    case "fr": $val=1; break;
                    case "de": $val=2; break;
                    case "uk": $val=3; break;
                    case "dk": $val=4; break;
                    case "sw": $val=5; break;
                    case "it": $val=6; break;
                    case "es": $val=7; break;
                    case "nw": $val=9; break;
                    case "us":
                    default: $val=0;
            }
            $val2=0;
            switch($codepage){
                    case 850: $val2=2; break;
                    case 860: $val2=3; break;
                    case 863: $val2=4; break;
                    case 865: $val2=5; break;
                    case 858: $val2=19; break;
                    case 437:
                    default: $val=0;
            }
            $this->out.="\x1B\x40\x1B\x52".chr($val)."\x1B\x74".chr($val2);
    }


    /**
     * Return the generated string
     *
     * @return	ASCII string to send to the printer.
     */
    function out(){
        return $this->out;
    }


    /**
     * Font selection
     *
     * @param $small		[optional] boolean for smaller font
     * @param $bold		[optional] boolean for bold
     * @param $underline        [optional] boolean for underline
     * @param $tall		[optional] boolean for double height
     * @param $large		[optional] boolean for double width
     */
    public function font($small=false,$bold=false,$underline=false,$tall=false,$large=false){
            $val=0;
            $this->font_small=$small;
            $this->font_large=$large;
            if($small)$val+=1;
            if($bold) $val+=8;
            if($underline) $val+=128;
            if($tall) $val+=16;
            if($large) $val+=38;
            $this->out.="\x1B\x21".chr($val);
    }

    /**
     * Align selection
     *
     * @param $align		string for align [l,c,r]
     */
    public function align($align="l"){
            $val=0;
            $align=strtolower(substr($align,0,1));
            if($align=="c") $val=1;
            elseif($align=="r") $val=2;
            $this->out.="\x1B\x61".chr($val);
    }

    /**
     * Cut the paper
     *
     * @param $space [optional] space before cut
     */
    public function cutCom(){
        $this->out.="\n\n\n\n".chr(27)."i";
    }

    public function cut($space=0){
            $this->out.="\x1D\x56";
            if($space>0) $this->out.="\x41".chr($space);
//            if($space>0) $this->out.="\x41".$space;
            else $this->out.="\x0";
    }

    /**
     * Print a raw data string without any filter
     *
     * @param $str	string raw string to append
     */
    public function raw($str){
            $this->out.=$str;
    }

    /**
     * Print a text (removes invalid chars)
     *
     * @param $text		string text to print
     * @param $newline	[optional] boolean append a new line char (default true)
     */
    public function text($text,$newline=true){
            $text=$this->_text($text);
            if($newline && !preg_match("/\n$/",$text)) $text.="\n";
            $this->out.=$text;
    }

    /**
     * Print a single line
     *
     * @param $text		string text to print on single line
     * @param $newline	[optional] boolean append a new line char (default true)
     * @return			the excees string or empty string if not exceed
     */
    public function line($text,$newline=true){
            $rest="";
            $maxlen=$this->font_small?56:42;
            $maxlen=$this->font_large?$maxlen/2:$maxlen;
            $text=$this->_text($text);
            if(strlen($text)>$maxlen){
                    $text=substr($text,0,$maxlen);
                    $rest=substr($text,$maxlen);
            }
            if($newline && !preg_match("/\n$/",$text)) $text.="\n";
            $this->out.=$text;
            return $rest;
    }

    /**
     * Print two string in a single line, the first with left align and second with right align
     *
     * @param $leftText			string text to print to the left
     * @param $rightText		string text to print to the right
     * @param $rightPriority	[optional] boolean if true give prority to the right string in case of string exceed (false default)
     * @param $newline			[optional] boolean append a new line char (default true)
     * @param $pad				[optional] string pad between string
     * @return					the excees string or empty string if not exceed
     */
    public function dualLine($leftText,$rightText,$rightPriority=false,$newline=true,$pad=" "){
            $rest="";
            $text="";
            $maxlen=$this->font_small?56:42;
            $maxlen=$this->font_large?$maxlen/2:$maxlen;
            $leftText=$this->_text($leftText);
            $rightText=$this->_text($rightText);
            if(strlen($leftText)+strlen($rightText)+1>$maxlen){
                    if($rightPriority){
                            $exc=$maxlen-strlen($rightText)-1;
                            $leftText=substr($leftText,0,$exc);
                            $rest=substr($text,$exc);
                            if(strlen($rightText)>$maxlen) return $rest.substr($pad,0,1).$this->line($text,$newline);
                    }else{
                            $exc=$maxlen-strlen($leftText)-1;
                            $rightText=substr($rightText,0,$exc);
                            $rest=substr($text,$exc);
                            if(strlen($leftText)>$maxlen) return $this->line($text,$newline).substr($pad,0,1).$rest;
                    }
            }
            if($rightPriority){
                    $text=str_pad($leftText,$maxlen-strlen($rightText),$pad);
                    $text.=$rightText;
            }else{
                    $text=$leftText;
                    $text.=str_pad($rightText,$maxlen-strlen($leftText),$pad,STR_PAD_LEFT);
            }
            if($newline && !preg_match("/\n$/",$text)) $text.="\n";
            $this->out.=$text;
            return $rest;
    }

    /**
     * Print a image from a file
     *
     * @param $filename	string to the filename
     * @return			false if an error occured
     */
    public function imageFromFile($filename){
            $img=false;
            $info = @getimagesize($filename);
            if(!$info) return false;
            switch($info[2]){
                    case IMAGETYPE_GIF : $img=imagecreatefromgif($filename); break;
                    case IMAGETYPE_JPEG : $img=imagecreatefromjpeg($filename); break;
                    case IMAGETYPE_PNG : $img=imagecreatefrompng($filename); break;
                    case IMAGETYPE_WBMP : $img=imagecreatefromwbmp($filename); break;
                    case IMAGETYPE_XBM : $img=imagecreatefromwxbm($filename); break;
            }
            return image($img);
    }

    /**
     * Print a image stored in NV memory
     *
     * @param $id		int	image address in NV
     */
    public function imageNV($id){
            if(!is_int($id) || $id<1 || $id>255) return false;
            $this->out.="\x1C\x70".chr($id)."\x0";
    }

    /**
     * Print a image from a resource
     *
     * @param $img		resource of an image (GD)
     * @return			false if an error occured
     */
    public function image($img){
            if($img===false || !is_resource($img)) return false;
            $xH=floor(ceil(imagesx($img)/8)/256);
            $xL=floor(ceil(imagesx($img)/8)-$xH*256);
            $yH=floor(imagesy($img)/256);
            $yL=floor(imagesy($img)-$yH*256);
            $img_string="";
            $count=0;
            for($y=0;$y<imagesy($img);$y++){
                    for($x=0;$x<8*ceil(imagesx($img)/8);$x++){
                            if($x%8==0) $str="";
                            if($x>=$img_width){
                                    $color=0;
                            }else{
                                    $rgb = imagecolorat($image, $x, $y);
                                    $r = ($rgb >> 16) & 0xFF;
                                    $g = ($rgb >> 8) & 0xFF;
                                    $b = $rgb & 0xFF;
                                    $gs = (($r*0.299)+($g*0.587)+($b*0.114));
                                    if($gs>150) $color=0;
                                    else $color=1;
                            }
                            $str=$str.$color;
                            if($x%8==7) $img_string.=chr((int)bindec($str));
                            $count++;
                    }
            }
            $this->out.="\x1D\x76\x30\x0".chr($xL).chr($xH).chr($yL).chr($yH);
            $this->out.=$img_string;
    }

    /**
     * Print a barcode
     *
     * @param $value	string(or int) value of code
     * @param $type	string barcode type [ean13,ean8,upca,upce,code39,itf,codabar,code93,code128]
     */
    public function barcode($value,$type='ean13'){
            $value=(string)$value;
            $typeval=67;
            $maxlen=255;
            $minlen=1;
            $strpad=" ";
            $preg='\x0-\x7f';
            switch(strtolower($type)){
                    case "upc": case "upca": case "upc-a":
                            $typeval=65; $maxlen=12; $minlen=11; $strpad="0"; $preg='\x30-\x39'; break;
                    case "upce": case "upc-e":
                            $typeval=66; $maxlen=12; $minlen=11; $strpad="0"; $preg='\x30-\x39'; break;
                    case "jan8": case "ean8":
                            $typeval=68; $maxlen=8; $minlen=7; $strpad="0"; $preg='\x30-\x39'; break;
                    case "code39": case "code":
                            $typeval=69; $preg='\x30-\x39\x41-\x5a\x20\x24\x25\x2b\x2d\x2e\x2f'; break;
                    case "itf":
                            $typeval=70; $maxlen=254; $preg='/[\x30-\x39]*/'; break;
                    case "codabar":
                            $typeval=71; $preg='\x30-\x39\x41-\x44\x24\x2b\x2d\x2e\x2f\x3a'; break;
                    case "code93":
                            $typeval=72; break;
                    case "code128":
                            $typeval=73; break;
                    case "jan13": case "ean13":	default:
                            $typeval=67; $maxlen=13; $minlen=12; $strpad="0"; $preg='\x30-\x39'; break;
            }
            $value = preg_replace('/[^'.$preg.']*/','', $value);
            $length=strlen($value);
            if($typeval==70 && $length%2==1) $value=str_pad($value,$length+1);
            if($length>$maxlen){
                    $value=substr($value,0,$maxlen);
            }elseif($minlen<12){
                    $value=str_pad($value,$minlen, $strpad, is_numeric($strpad)?STR_PAD_LEFT:STR_PAD_RIGHT);
            }
            $length=strlen($value);
            $this->out.="\x1D\x6B".chr($typeval).chr($length).$value;
    }



    private function _text($text){
            if($this->translate_from && $this->translate_to){
                    $text=strtr($text,$this->translate_from, $this->translate_to);
            }
            $text = preg_replace('/[\x0-\x9\xb-\x1f]*/','', $text);
            return $text;
    }


}

?>