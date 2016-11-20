<?php
/**
 * @author hebidu <email:346551990@qq.com>
 */
namespace app\src\system\logic;

use app\src\base\logic\BaseLogic;
use app\src\system\model\Log;

class LogLogic extends BaseLogic{
	
	protected function _init(){
		$this->setModel(new Log());
	}

}
