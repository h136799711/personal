<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-22
 * Time: 13:42
 */

namespace app\src\favorites\action;


use app\src\favorites\logic\FavoritesLogic;
use app\src\base\action\BaseAction;

/**
 * Class FavoritesQueryAction
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\service\Favorites
 */
class FavoritesQueryAction extends BaseAction
{

    /**
     * 查询
     * @author hebidu <email:346551990@qq.com>
     * @param $map
     * @return array
     */
    public function query($map){


        $logic   = new FavoritesLogic();

        $result  = $logic->search($map);

        return $this->result($result);

    }

}