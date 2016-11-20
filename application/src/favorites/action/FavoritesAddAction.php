<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-22
 * Time: 10:27
 */

namespace app\src\favorites\action;


use app\src\base\helper\ValidateHelper;
use app\src\favorites\logic\FavoritesLogic;
use app\src\favorites\model\Favorites;
use app\src\goods\logic\ProductLogic;
use app\src\base\action\BaseAction;

class FavoritesAddAction extends BaseAction
{

    public function add($entity){

        $id = $entity['favorite_id'];
        $type  = $entity['type'];

        $logic = new FavoritesLogic();

        $result = $logic->getInfo($entity);
        if(ValidateHelper::legalArrayResult($result)){
            $result = $logic->delete($entity);

        }else{
            $content = $this->getFavoriteContent($id,$type);
            if(empty($content)){
                return $this->error(lang("err_not_find"));
            }
            $entity['create_time'] = time();
            $entity['favorite_content'] = json_encode($content);
            $result = $logic->add($entity);
        }

        return $this->result($result);
    }

    private function getFavoriteContent($id,$type){
        if($type == Favorites::FAV_TYPE_PRODUCT){

            $logic  = new ProductLogic();
            $result = $logic->detail($id);
            if($result['status']){
                return $result['info'];
            }else{
                return [];
            }

        }else{
            return [];
        }
    }
    
}