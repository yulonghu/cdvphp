<?php
/**
 * 根据权重比例算出接近概率的类库
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\RandLottery
 */
class RandLottery
{
    /**
     * 启动随机概率算法, 保证传入的数组结构体权重相加等于100
     *
     * <pre>
     * # $data = array(key => 权重值)
     *
     * $data = array('a' => 10, 'b' => 20, 'c' => 30, 'd' => 40);
     * $handle = new RandLottery();
     * print_r($handle->start($data));
     *
     * #输出结果
     * Array
     * (
     *     [d] => 40
     * )
     * </pre>
     *
     * @param array $data 数据源
     * @return array
     */
    public function start($data)
    {/*{{{*/
        $sum_rand = 0;
        $result = array();

        if(!is_array($data) || count($data) < 1)
        {
            throw new RuntimeException('param data wrong');
        }
        
        asort($data);
        $random = rand(1, 100);

        foreach($data as $key => $val)
        {
            $sum_rand += $val;
            if($random <= $sum_rand)
            {
                $result = array($key => $val);
                break;
            }
        }

        if(empty($result))
        {
            $result = array_pop(array_keys($data));
            $result = array($result => $data[$result]);
        }

        return $result;
    }/*}}}*/
}

