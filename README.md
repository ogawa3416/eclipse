### とりあえず実行
- コマンドプロンプトを起動して以下を実行。
```
cd C:\path\to\project\helloworld
gradlew bootRun
```
- ブラウザで http://localhost:8080/ を開く。

### eclipseで実行する。
- コマンドプロンプトを起動して以下を先に実行（重要）。
```
cd C:\path\to\project\helloworld
gradlew build
```
- eclipseをインストールする。
- インポートで新しいプロジェクトとして「helloworld」を追加する。
- Run -> Run as -> Spring Boot App を実行する。
- ブラウザで http://localhost:8080/ を開く。
