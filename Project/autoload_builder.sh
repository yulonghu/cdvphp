#!/bin/sh
#------------------------------------
# build autoload files
# Auther: fanjiapeng@360.cn
# Ctime:  2016/01/21
#------------------------------------

export PATH=$PATH:/usr/local/php/bin

PHP=`which php`
PROJECT_NAME='cdvphp'

if [ `uname` == 'Linux' ]
then
    basedir=`dirname $(readlink -f $0)`
    PROJECT_HOME=`readlink -f $basedir/../`
elif [ `uname` == 'FreeBSD' ]
then
    basedir=`dirname $(realpath $0)`
    PROJECT_HOME=`realpath $basedir/../`
fi

AUTOLOAD_PATH_PROJECT="$PROJECT_HOME/CdvPHP:$PROJECT_HOME/Application"

$PHP $PROJECT_HOME/Project/build_includes.php $AUTOLOAD_PATH_PROJECT $PROJECT_HOME/Public/autoload.php "${PROJECT_NAME}:$USER:autoload:server"

