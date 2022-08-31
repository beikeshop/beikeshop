#!/bin/sh

version="1.0.0"

pwd

echo '1. 删除已有打包zip文件.'
if [[ -a ./$version.zip ]];then
    echo "存在文件 $version.zip";
    rm -rf ./$version.zip
fi;
printf "\n"


echo '2. 删除已有源码文件.'
if [[ -a ./$version/ ]];then
    echo "存在文件夹 $version";
    rm -rf $version/
fi;
printf "\n"


echo '3. 复制源码并从git更新代码.'
cp -r beikeshop/ $version/ && cd $version && pwd
git pull && git checkout master
printf "\n"


echo '4. composer install.'
composer install
printf "\n"


echo '5. 删除node相关文件.'
if [[ -a ./package-lock.json ]];then
    echo "存在文件 package-lock.json";
    rm ./package-lock.json
fi;
if [[ -a ./node_modules ]];then
    echo "存在文件夹 node_modules";
    rm -rf ./node_modules
fi;
printf "\n"


echo '6. 安装npm包并编译前端相关文件.'
npm install && npm run production
printf "\n"


echo '7. 清理其他文件.'
if [[ -a ./database/product_beike.sqlite ]];then
  echo "存在文件 product_beike.sqlite";
  rm -rf ./database/product_beike.sqlite
fi;
if [[ -a ./storage/installed ]];then
  echo "存在文件 installed";
  rm -rf ./storage/installed
fi;
rm -rf ./storage/app/*
rm -rf ./storage/debugbar/*
rm -rf ./storage/framework/cache/*
rm -rf ./storage/framework/sessions/*
rm -rf ./storage/framework/testing/*
rm -rf ./storage/framework/views/*
rm -rf ./storage/logs/*
rm -rf ./storage/upload/*
rm -rf ./.idea
rm -rf ./.git*
rm -rf ./node_modules
rm ./package-lock.json
rm ./Envoy.blade.example.php
printf "\n"


echo '8. 压缩文件夹.'
cd ../ && zip -r $version.zip $version
printf "\n"


