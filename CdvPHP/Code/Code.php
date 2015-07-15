<?php
/**
 * 简易高效生成图片验证码函数
 *
 * 宽、高, 画布大小、间距等全部都可以自己随意设置。
 *
 * 注意：大字容易让人识别,不容易让人分辨字母取消掉。
 *
 * 为了人性化, 验证码一般不需要区分大小写。
 *
 * @author cdvphp.com fanjiapeng@126.com
 * @package CdvPHP\Code
 */

class Code
{
    /**
     * 生成图形验证码
     *
     * 读取验证码 Loader::getInstance('Session')->get('code')
     *
     * @param int $width  自定义宽
     * @param int $height 自定义高
     *
     * @return string  
     */
    public function getEasyCode($width = 66, $height = 28)
    {/*{{{*/
        //图像宽、高配置
        $im = @imagecreate($width, $height) or die('Cannot Initialize new GD image stream');
        $bgColor = ImageColorAllocate($im, 251, 252, 253);

        //图像背景
        imagefill($im, 0, 0, $bgColor);
        //PHP5可以去掉 srand((double)microtime() * 1000000);
        $watch = '123456789abcdefghjkmnpqrstuvwxyz';
        $srand = rand();
        $vcodes = '';

        //生成图像文字
        for($i = 0; $i < 4; $i++)
        {
            $vcodes .= $authnum = $watch{rand(0, 31)};
            imagestring($im, 5, (12 + ($i * 10)), 3, $authnum, ImageColorAllocate($im, rand(100, 255), rand(0, 100), rand(100, 255)));
        }

        Loader::getInstance('Session')->set('code', $vcodes);

        //加入点干扰象素
        for($i = 0; $i < 50; $i++)
        {
            imagesetpixel($im, rand() % 70, rand() % 30, ImageColorallocate($im, $srand, $srand, $srand));
        }

        //加入斜杠干扰素
        /*
        for($i = 0; $i < 2; $i++)
        {
            $line_color = imagecolorallocate($im, 0xAB, 0xAB, 0xAB);
            imageline($im, rand(0, $width), rand(0, $imgHeight), rand(0, $height), rand(0, $height), $line_color);
        }
         */
        ImagePNG($im);
        ImageDestroy($im);

        return $vcodes;
    }/*}}}*/
}
