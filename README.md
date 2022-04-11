# discord-webhook-proxy

Discord Webhook にリクエストを送るだけのアプリケーションです。

(This application just send POST request to discord webhook url.)

CORS による制限のため、ブラウザ上のアプリケーションから Discord Webhook API にリクエストを送ることはできません。

(For CORS, application on browser can't send request to discord webhook.)

この PHP アプリケーションは `/webhook/send` エンドポイントで接続を受け、ブラウザ上のアプリケーションの代わりに Discord の Webhook URL にリクエストを送ります。

(Application on browser can use discord webhook api by to send `/webhook/send` endpoint on this PHP backend
application.)

## How to run

This application create from [Slim Framework 4 Skeleton Application](https://github.com/odan/slim4-skeleton)

`.env` ファイルが必要となるため作成し、適宜パラメタを設定してください。

* DISCORD_WEBHOOK_URL : Discord の Webhook URL です。
* ALLOW_ORIGIN_URL : ALLOW_ORIGIN_URL です。開発環境で外部からの接続ができない場合は、 `*` を指定していればいいかと思います。
* PRODUCTION : 本番環境において指定します。この環境変数に何かしら設定があれば、本番環境として扱われます。

You h to create `.env` file.

```bash
cp .env.sample .env
```

To run the application in development, you can run these commands

```bash
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:

```bash
docker-compose up -d
```

After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```

That's it! Now go build something cool.

## Deploy to AppEngine

GCP App Engine にデプロイすることができます。私は普段、本番環境を App Engine にデプロイしています。

(You can deploy this application to GCP App Engine.)

プロジェクトの設定方法その他については、 [Building an app with PHP 7+  |  App Engine standard environment for PHP docs  |  Google Cloud](https://cloud.google.com/appengine/docs/standard/php-gen2/building-app)
を参照してください。

(For detail,
show [Building an app with PHP 7+  |  App Engine standard environment for PHP docs  |  Google Cloud](https://cloud.google.com/appengine/docs/standard/php-gen2/building-app))

`app.yaml` のファイルを作成し、編集する必要があります。

パラメタについては `.env` と同様となります。

```bash
cp app.sample.yaml app.yaml
```

```bash
gcloud app deploy
```
