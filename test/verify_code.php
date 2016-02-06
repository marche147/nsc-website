<?php
/*
 * Image Related functions :
 * resource imagecreatetruecolor ( int $width , int $height );	// imagecreatetruecolor() returns an image identifier representing a black image of the specified size.
 * int imagecolorallocate ( resource $image , int $red , int $green , int $blue ); //Returns a color identifier representing the color composed of the given RGB components.
 *
 */
class VerifyCode {
	private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ3456789';	// char set
	private $codeLen;	// length of the code
	private $code;	// code
	private $codeWidth;
	private $codeHeight;
	private $img;
	public function __construct($width=80, $height=20, $len=4)
	{
		$this->codeWidth = $width;
		$this->codeHeight = $height;
		$this->codeLen = $len;
		$this->makeCode();
		return;
	}
	private function makeCode() {	// make random verify code
		$len = strlen($this->charset)-1;
		for($i=0;$i<$this->codeLen;$i++) {
			$this->code .= $this->charset[mt_rand(0,$len)];
		}
		return;
	}
	public function getCode()
	{
		return $this->code;
	}
	private function drawBG()	// make background
	{
		$this->img = imagecreate($this->codeWidth,$this->codeHeight);
		$bgcolor = imagecolorallocate($this->img,mt_rand(225,255),mt_rand(225,255),mt_rand(225,255));
		imagefill($this->img,0,0,$bgcolor);
		return;
	}
	private function drawDummy()	// make dummy lines and points
	{
		for($i=0;$i<100;$i++)
		{
			$pcolor = imagecolorallocate($this->img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($this->img,rand(1,$this->codeWidth-3),rand(1,$this->codeHeight-3),$pcolor);
		}
		for($i=0;$i<10;$i++)
		{
			$lcolor = imagecolorallocate($this->img,mt_rand(0,255),mt_rand(0,128),mt_rand(0,255));
			imagearc($this->img,mt_rand(-10,$this->codeWidth+10),mt_rand(-10,$this->codeHeight+10),mt_rand(30, 300),mt_rand(30, 300),55,44,$lcolor);
		}
		return;
	}
	private function drawCode()
	{
		for($i=0;$i<$this->codeLen;$i++)
		{
			$color = imagecolorallocate($this->img,mt_rand(0,128),mt_rand(0,128),mt_rand(0,128));
			$fontsize = mt_rand(5,8);
			$x = 3+($this->codeWidth/$this->codeLen)*$i;
			$y = rand(2, imagefontheight($fontsize)-10);
			imagechar($this->img,$fontsize,$x,$y,$this->code[$i],$color);
		}
		return;
	}
	private function doImg()
	{
		$this->drawBG();
		$this->drawDummy();
		$this->drawCode();
		return;
	}
	public function output()
	{
		$this->doImg();
		header('Content-type:image/png');
		imagepng($this->img);
		return;
	}
	public function __destruct()
	{
		imagedestroy($this->img);
		return;
	}
}
?>
