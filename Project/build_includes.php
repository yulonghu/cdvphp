<?php
/**
 * build class
 *
 * @Auther fanjiapeng@360.cn
 * @Ctime 2016/01/21
 */

if($argc == 4)
{
    $obj_build = new AssemblyBuilder();
    $obj_build->run($argv[1], $argv[2], $argv[3]);
    $obj_build = null;
}
else
{
    echo "Usage: /usr/local/bin/php build_includes <root_path> <outfile> <cache_key>", PHP_EOL;
}

class AssemblyBuilder
{/*{{{*/
    private static $_paths       = array();
    private static $_skipFolders = array('web-inf', 'tmp', '.svn', 'sqls', 'logs', 'Project', '.git', 'Templates', 'TemplatesCache');
    private static $_skipFiles   = array('.swp', 'Autoloader');
    private static $_fileNameTemplate = array('php');

    private function _getCodeTpl()
    {/*{{{*/
return '<?php
function openapiautoload($classname)
{
    $classpath = getClassPath();
    if (isset($classpath[$classname]))
    {
        include($classpath[$classname]);
    }
}
function getClassPath()
{
    static $classpath=array();
    if(function_exists(\'apc_fetch\'))
    {
        $classpath = apc_fetch(\'___CACHEKEY___\');
        if ($classpath) return $classpath;

        $classpath = getClassMapDef();
        apc_store(\'___CACHEKEY___\', $classpath, 86400); 
    }
    else if(function_exists("eaccelerator_get"))
    {
        $classpath = eaccelerator_get(\'___CACHEKEY___\');
        if ($classpath) return $classpath;

        $classpath = getClassMapDef();
        eaccelerator_put(\'___CACHEKEY___\', $classpath, 86400); 
    }
    else
    {
        $classpath = getClassMapDef();
    }
    return $classpath;
}
function getClassMapDef()
{
    return array(
        ___DATA___
    );
}
spl_autoload_register(\'openapiautoload\');
?>';
    }/*}}}*/

    public function run($rootPath, $outfile, $cache_key)
    {/*{{{*/
        $classes = array();
        self::$_paths = explode(':', $rootPath);

        foreach (self::$_paths as $path)
        {
            if(empty($path))
            {
                continue;
            }

            $files = $this->_findFiles($path);
            foreach ($this->_findClasses($files) as $class => $filename)
            {
                if (empty($classes[$class]))
                {
                    $classes[$class] = $filename;
                }
                else
                {
                    echo "Repeatedly Class $class in file $filename", PHP_EOL;
                }
            }
        }

        $bool_result = $this->_generatorAssemblyFile($classes, $outfile, $this->_getCodeTpl(), $cache_key);

        if($bool_result)
        {
            echo PHP_EOL, "generator assembly file successed!", PHP_EOL, PHP_EOL;
        }
        else
        {
            echo PHP_EOL, "generator assembly file falied!", PHP_EOL, PHP_EOL;
        }
    }/*}}}*/

    private function _generatorAssemblyFile($classes, $outfile, $code, $cache_key)
    {/*{{{*/
        $arr_code = '';
        $str_mode = "\t";
        foreach ($classes as $key => $value)
        {
            $arr_code  .= "{$str_mode}\"{$key}\" => \t\t\t\"{$value}\",\n";
            $str_mode = "\t\t\t";
        }
        $cache_key = $cache_key . ":" . time();
        $code = str_replace("___DATA___", $arr_code, $code);
        $code = str_replace("___CACHEKEY___", $cache_key, $code);
        
        return file_put_contents($outfile, $code);
    }/*}}}*/

    private function _findClasses($files)
    {/*{{{*/
        $classes = array();
        foreach($files as $file)
        {
            foreach($this->_findClassFromAFile($file) as $class)
            {
                if (!isset($classes[$class]))
                {
                    $classes[$class] = $file;
                }
                else
                {
                    echo "Repeatedly Class $class in file $file", PHP_EOL;
                }
            }
        }

        return $classes;
    }/*}}}*/

    private function _findClassFromAFile($file)
    {/*{{{*/
        $classes = array();
        $lines = file($file);
        foreach($lines as $line)
        {
            if (preg_match("/^\s*class\s+(\S+)\s*/", $line, $match))
            {
                $classes[] = $match[1];
            }
            if (preg_match("/^\s*abstract\s*class\s+(\S+)\s*/", $line, $match))
            {
                $classes[] = $match[1];
            }
            if (preg_match("/^\s*interface\s+(\S+)\s*/", $line, $match))
            {
                $classes[] = $match[1];
            }
        }
        
        return $classes;
    }/*}}}*/

    private function _skipFiles($file)
    {/*{{{*/
        foreach(self::$_skipFiles as $file_rule)
        {
            if(preg_match("/$file_rule/i", $file))
            {
                return TRUE;
            }
        }
    }/*}}}*/

    private function _isSkipFolders($file)
    {/*{{{*/
        foreach (self::$_skipFolders as $skip)
        {
            $skip = quotemeta($skip);
            if (preg_match("/$skip/", $file))
            {
                return TRUE;
            }
        }
    }/*}}}*/

    private function _findFiles($dirname)
    {/*{{{*/
        $filelist = array();
        $currentfilelist = scandir($dirname);
        foreach ($currentfilelist as $file)
        {
            if ($file == '.' || $file == '..' || $this->_isSkipFolders($file))
            {
                continue;
            }

            $file = "$dirname/$file";

            if (is_dir($file))
            {
                foreach ($this->_findFiles($file) as $tmpfile)
                {
                    $filelist[] = $tmpfile;
                }
                continue;
            }

            if (false == $this->_skipFiles($file))
            {
                echo $file, PHP_EOL;
                $filelist[] = $file;
            }
        }

        return $filelist;
    }/*}}}*/
}/*}}}*/
