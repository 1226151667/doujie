<?php
namespace app\index\model;
use app\index\model\Start;
class Attachment extends Start{
	protected $updateTime = false;
	protected $insert = ['use_num' => 1];  
}