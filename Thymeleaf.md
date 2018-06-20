javaからbootstrapのページを読み込む手順
====
Thymeleafを使用  

## 必要なファイルの配置
* /src/main/resources/publicに解凍したbootstrapフォルダを格納  
* /src/main/resources/public/assets/jsに必要なjsファイルを格納(index.html が読み込んでいるファイル)  
* /src/main/resources/templatesにindex.htmlを格納  
* /src/main/resources/static/CSSにstyle.cssを格納  

## HTMLでCSSの読み込みの設定  
th:href にて、「@{/css/style.css}」を設定する。  

## コントローラーの設定 
mav.setViewName(" ")の部分を変える→今回だと("index")  
(使用するビューを設定)

## アクセス
http://localhost:8080/boot
