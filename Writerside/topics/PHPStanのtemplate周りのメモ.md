# PHPStanのtemplate周りのメモ

## 調べる動機
* 雰囲気で使っていたけど、「あれ〜上手く動かないぞ〜」ってことがあった
* 調べていたら `@template-covariant` `@template-contravariant` の存在を知った
  * 正確には、「存在は知っていたが、ちゃんと使ったことがないし説明も読んでスルーしていた」という感じ
* 仲良くなろう！

## 参考リンクたち
### 公式
* [What's Up With @template\-covariant? \| PHPStan](https://phpstan.org/blog/whats-up-with-template-covariant)
* [A guide to call\-site generic variance \| PHPStan](https://phpstan.org/blog/guide-to-call-site-generic-variance)
### ninja-san
* [PHPDocを使ったPHPのジェネリクス \- 超PHPerになろう](https://www.phper.ninja/entry/2020/03/01/054833)
* [PHPStanクイックガイド2023](https://zenn.dev/pixiv/articles/7467448592862e)
### community
* [The case for contravariant template types – Blog – Jiří Pudil](https://jiripudil.cz/blog/contravariant-template-types)

## 関連する用語とか概念の整理など
語の定義とかではないです。雑な例え話。  
怒られるかも知れないので真に受けないでください

* ジェネリクス、型パラメータ
  * 「何かしらの型を受け取るけど、返す値はそれと連動する(同じものになる)よ！」とかって時など
  * 「Xを受け取ったら返すのもX、Yを受け取ったら返すのもY(それはXでもZでもない！)」的な事をしたい時に便利
  * 例: `List<T>` とすれば、`new List(Curry)` したら `CurryのList` が来るし、`new List(Bacon)` したら `BaconのList` が来る
* 共変反変非変
  * 継承とか実装とかの場面でどうする・・・？という話が、今回のトピック
    * なので、ここら辺が関わる
  * 「受け取れるものは広くする」、「返すものは狭くする」が許されているよ〜という話
  * 「美味しいもの」→「カレーライス」は、概念が狭くなっているので、「前の制約を満たしたまま(共通)」ってことで共変
    * 「美味しいものなら何でもOK」という時に、勝手にカレーライスに置き換えても怒られない
    * ※ 自分勝手に、語呂合わせとして「共通の共」って覚えているけど、正しい由来ではない
  * その逆、「カレーライスって行ったのにステーキを出すんじゃありません！」という怒られが反変
  * 非変は「変わらないまま」になる
* co-variant: 共変 / contra-variant: 反変 / in-variant: 非変
  * 「コントラスト」とかと同じみたい。コントラスト→対比→「比較の対象」→「反対」みたいな感じっぽい

## PHPStanの場合
* `@template`
* `@template-covariant`
* `@template-contravariant`

### テンプレート？
see: [テンプレート \(C\+\+\) \| Microsoft Learn](https://learn.microsoft.com/ja-jp/cpp/cpp/templates-cpp?view=msvc-170)

### templateの例
`@template パラメータの名前` という記法。  
`of ...` とかしておくと、「何でも良い(mixed)訳ではなくて、使える型は制限されているよ！」を示せる。
メソッドにも使えるし、クラスレベルでも使える。

下の例は、「DateTime型のオブジェクトを受け付けるよ〜」というもの。
もし、`Chronos` とかを渡しているのに 「返り値は素朴な`DateTimeInterface` です！それ以上の機能は保証しません！！」だと、とても困る。  
なので、「吐き出されるオブジェクトの型は、メソッドの引数が何かによって変えたい」「このコードを書いた時点では保証できなくて、どう使われるかによる」を実現すべく、@templateを使っている。

<code-block lang="php" src="stan-templates/src/DateTimeUtil.php"/>

実際に、templateを上手く使った例と使っていない例を対比したサンプル。 

<code-block lang="php" src="stan-templates/snippets/template-example.php"/>
```
 ------ ----------------------------------------------------------- 
Line   template-example.php
 ------ ----------------------------------------------------------- 
11     Call to an undefined method DateTimeInterface::addDays().  
🪪  method.notFound
 ------ ----------------------------------------------------------- 
```

クラスレベルで使ったり、色々な「便利そう！」を匂わせるコード例がこんな感じ。  
メソッドをまたいで、同じ「T」に言及できる。

<code-block lang="php" src="stan-templates/src/UmaiMeshi.php"/>

### template-covariantの例
**WIP**

`@template` が非変(invariant)を表すのに対して、 `@template-covariant` は共変を示す。  
もし、「templateを持つクラスやインターフェイスを継承した、サブクラスで、親クラスのTのバリエーションを扱う」という場合にはそんなに意識しなくて良さそう。  

<code-block lang="php" src="stan-templates/src/UmaiNoodle.php"/>

```
 ------ ------------------------------------------------ 
  Line   UmaiNoodle.php                                  
 ------ ------------------------------------------------ 
  17     Dumped type: class-string<App\NoodleInterface>  
 ------ ------------------------------------------------ 
```
### "call-site" template-covariantの例
TBD

### template-contravariantの例
TBD
