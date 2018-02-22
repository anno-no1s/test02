## yumでMeCabをインストールするためのリポジトリ追加
`sudo rpm -ivh http://packages.groonga.org/centos/groonga-release-1.1.0-1.noarch.rpm`

## パッケージ情報の更新
`sudo yum makecache`

## 形態素解析のライブラリのインストール
`sudo yum install mecab mecab-ipadic mecab-devel`

## 外部ライブラリのインストール
`composer install`

## 実行コマンド
`php get.php`
