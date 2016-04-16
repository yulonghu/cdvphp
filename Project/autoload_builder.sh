#!/bin/sh
#------------------------------------
# build autoload files
#
# @command: sh Project/autoload_builder.sh
# @Auther: fanjiapeng@360.cn
# @Ctime:  2016/01/21
#------------------------------------

export PATH=$PATH:/usr/local/php/bin

PHP=`which php`

# 项目名称
PROJECT_NAME='cdvphp'

# 项目路径
ROOT_PATH=`pwd`

# 生成目录
AUTOLOAD_PATH_PROJECT="$ROOT_PATH/CdvPHP:$ROOT_PATH/Application"

$PHP $ROOT_PATH/Project/build_includes.php $AUTOLOAD_PATH_PROJECT $ROOT_PATH/Public/autoload.php "${PROJECT_NAME}:$USER:autoload:server"

