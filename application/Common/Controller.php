<?php
namespace Common;

class Tp
{
	function __construct(){
        $this->tpl_vars = [];
	}

	public function assign($tpl_var, $value = null){
		if($tpl_var != "") {
            $this->tpl_vars[$tpl_var] = $value;
        }
	}

	public function display($fileName)
    {
		$tplFile = str_replace("\\","/",__APP__.__MODULE__.'/view/'.$fileName.'.html');
		$cachePath = str_replace("\\","/",__CACHE__);
        $runtimePath = str_replace("\\","/",__APP__.'/Runtime');

		if(!file_exists($tplFile)){
			return false;
		} else {
            $templateName = md5(str_replace("/",'_',$tplFile));
            $comFileName = $cachePath.'/'.$templateName.'.php';

            if (!file_exists($runtimePath)) {
                mkdir($runtimePath);
            }

            if (!file_exists($runtimePath.'/Cache')) {
                mkdir($runtimePath.'/Cache');
            }

            if (!file_exists($runtimePath.'/Logs')) {
                mkdir($runtimePath.'/Logs');
            }

            if (!file_exists($cachePath)) {
                mkdir($cachePath);
            }

            if(!file_exists($comFileName) || filemtime($comFileName) < filemtime($tplFile)){
                $repContent = $this->tpl_replace(file_get_contents($tplFile));
                file_put_contents($comFileName, $repContent);
            }
        }

		include $comFileName;
	}

	private function tpl_replace($content){
	    //变量
		$pattern = ['/\{\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\}/i'];
		$replacement = ['<?php echo $this->tpl_vars["${1}"]; ?>'];
		$repContent = preg_replace($pattern, $replacement, $content);

        //数组 todo 只支持二维数组
        $pattern = ['/\{\s*\$([a-zA-Z_\x7f-\xff].*)\[(.*)\]\s*\}/i'];
        $replacement = ['<?php echo $this->tpl_vars["${1}"][${2}]; ?>'];
        $repContent = preg_replace($pattern, $replacement, $repContent);

		//__常量__ 形式的常量
        $pattern = ['/(__[a-zA-Z]*(__)?)/i'];
        $replacement = ['<?php echo ${1}; ?>'];
        $repContent = preg_replace($pattern, $replacement, $repContent);

        //函数
        $pattern = ['/\{:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(.*)\}/i'];
        $replacement = ['<?php echo ${1}${2}; ?>'];
        $repContent = preg_replace($pattern, $replacement, $repContent);

		return $repContent;
	}
}