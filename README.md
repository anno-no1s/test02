## portを3000に固定しているため、アクセスできるように調整する
vagrantを使用する場合はVagrantfileに以下の設定を追加
`config.vm.network "forwarded_port", guest: 3000, host: 3000`

## 外部ライブラリのインストール
`npm install`

## サーバー起動
`node app.js`

## ブラウザから下記のURLにアクセス
`http://localhost:3000/`
