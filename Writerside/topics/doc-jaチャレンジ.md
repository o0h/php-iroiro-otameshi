# doc-jaチャレンジ
## なに？
PHP8.4リリースに伴って、マニュアルの翻訳が盛り上がっているので自分もやってみたい。  
普通に貢献したいし！

こういう話もある: https://mainichi.nichiyoubi.land/posts/20241121/

## 環境構築
https://github.com/jdkfx/phpdoc  is 神...


1. cloneして
2. makeして
```
 $ make setup
Cloning phd...
Cloning into 'phd'...
remote: Enumerating objects: 11884, done.
remote: Counting objects: 100% (1719/1719), done.
remote: Compressing objects: 100% (649/649), done.
remote: Total 11884 (delta 1066), reused 1444 (delta 919), pack-reused 10165 (from 1)
Receiving objects: 100% (11884/11884), 3.02 MiB | 6.52 MiB/s, done.
Resolving deltas: 100% (6315/6315), done.
Cloning doc-base...
Cloning into 'doc-base'...
remote: Enumerating objects: 19750, done.
remote: Counting objects: 100% (1597/1597), done.
remote: Compressing objects: 100% (267/267), done.
remote: Total 19750 (delta 1396), reused 1426 (delta 1330), pack-reused 18153 (from 1)
Receiving objects: 100% (19750/19750), 10.42 MiB | 11.40 MiB/s, done.
Resolving deltas: 100% (13856/13856), done.
Cloning doc-en...
Cloning into 'en'...
remote: Enumerating objects: 311435, done.
remote: Counting objects: 100% (10281/10281), done.
remote: Compressing objects: 100% (918/918), done.
remote: Total 311435 (delta 9721), reused 9644 (delta 9362), pack-reused 301154 (from 1)
Receiving objects: 100% (311435/311435), 64.88 MiB | 16.81 MiB/s, done.
Resolving deltas: 100% (262674/262674), done.
Updating files: 100% (11301/11301), done.
Cloning doc-ja...
Cloning into 'ja'...
remote: Enumerating objects: 178220, done.
remote: Counting objects: 100% (5947/5947), done.
remote: Compressing objects: 100% (1743/1743), done.
remote: Total 178220 (delta 4474), reused 5599 (delta 4196), pack-reused 172273 (from 1)
Receiving objects: 100% (178220/178220), 44.91 MiB | 20.59 MiB/s, done.
Resolving deltas: 100% (156469/156469), done.
Updating files: 100% (7686/7686), done.
```

```sh
$ ls -l
total 24
-rw-r--r--   1 o0h  staff  1069 11 24 11:13 LICENSE
-rw-r--r--   1 o0h  staff  1329 11 24 11:13 Makefile
-rw-r--r--   1 o0h  staff  1406 11 24 11:13 README.md
drwxr-xr-x   3 o0h  staff    96 11 24 11:13 bin
drwxr-xr-x  21 o0h  staff   672 11 24 11:13 doc-base
drwxr-xr-x  28 o0h  staff   896 11 24 11:14 en
drwxr-xr-x  29 o0h  staff   928 11 24 11:14 ja
drwxr-xr-x  28 o0h  staff   896 11 24 11:13 phd

$ cat .gitignore
# 翻訳用に用意する各言語のディレクトリを無視する
*

!.gitignore
!bin/
!bin/**
!LICENSE
!Makefile
!README.md
```

なるほど、関連ディレクトリもcloneしてくれる感じだ。  
自分の場合は、以前にPR送った際に別の場所にphp-docのディレクトリを持っているので、これはsymlinkで置き換えておこう

初めてのビルド！
```
 $ make build
make xhtml

php doc-base/configure.php --with-lang=ja
configure.php: $Id$
PHP version: 8.3.13

Checking for source directory... /Users/o0h/src/github.com/php/doc-base
Checking for output filename... /Users/o0h/src/github.com/php/doc-base/.manual.xml
Checking whether to include CHM... no
Checking for PHP executable... /usr/local/Cellar/php/8.3.13/bin/php
Checking for language to build... ja
Checking whether the language is supported...
error: No language directory found.
make: *** [build] Error 1
php phd/render.php --docbook doc-base/.manual.xml --package PHP --format xhtml
[02:36:25 - E_USER_ERROR          ] /Users/o0h/src/github.com/jdkfx/phpdoc/phd/phpdotnet/phd/Options/Handler.php:190
	'doc-base/.manual.xml' is not a readable docbook file
make: *** [xhtml] Error 1
```
(自ローカル環境のフルパスが出てるけどまぁ無難な名前だし、気にしなくていっか)

doc-baseが古いな多分?  
・・・と思ったけど、そういうことではなく、シンボリックリンクを使ったことで色々とパスの解決にミスってたりしたっぽい。  
のでゴニョゴニョして、再挑戦

```sh
$ make build
php doc-base/configure.php --with-lang=ja
configure.php: $Id$
PHP version: 8.3.13

Checking for source directory... /Users/o0h/src/github.com/php/doc-base
Checking for output filename... /Users/o0h/src/github.com/php/doc-base/.manual.xml
Checking whether to include CHM... no
Checking for PHP executable... /usr/local/Cellar/php/8.3.13/bin/php
Checking for language to build... ja
Checking whether the language is supported... yes
Checking for partial build... no
Checking whether to enable detailed XML error messages... no
Checking libxml version... 2.9.13
Checking whether to enable detailed error reporting (may segfault)... yes
doc-base: e6a8e42e9e48aa008128535c6a6f2513eb038343
 M configure.php
en:       fe4ff341037cab316f76ca414f9a1a8ecdc4abfd
ja:       2e698ff9cef04229d797d43cb3d0a344d6329c0e
[gone]

[ahead 172]

Generating /Users/o0h/src/github.com/php/doc-base/manual.xml... done
Generating /Users/o0h/src/github.com/php/doc-base/scripts/file-entities.php... done
Iterating over extension specific version files... OK
Saving it... OK
Iterating over files for sources info... OK
Generating sources XML... OK
Saving sources.xml file... OK
Modification history file /Users/o0h/src/github.com/php/en/fileModHistory.php not found.
Creating empty modification history file...done.
Creating file /Users/o0h/src/github.com/php/doc-base/entities/file-entities.ent... done
Checking for if we should generate a simplified file... no
Checking whether to save an invalid .manual.xml... no
Loading and parsing manual.xml... done.
Running XInclude/XPointer... done. Performed 1476 XIncludes
Validating manual.xml... done.

All good. Saving .manual.xml... done.
All you have to do now is run 'phd -d /Users/o0h/src/github.com/php/doc-base/.manual.xml'
If the script hangs here, you can abort with ^C.
         _ _..._ __
        \)`    (` /
         /      `\
        |  d  b   |
        =\  Y    =/--..-="````"-.
          '.=__.-'               `\
             o/                 /\ \
              |                 | \ \   / )
               \    .--""`\    <   \ '-' /
              //   |      ||    \   '---'
         jgs ((,,_/      ((,,___/

 (Run `nice php doc-base/configure.php` next time!)
 ```

やった〜〜スフィンクスだ！  
前にPR送ろうとした時は、ココまで行ってなかったので既に神

```sh
$ make xhtml
php phd/render.php --docbook doc-base/.manual.xml --package PHP --format xhtml
[02:47:53 - Heads up              ] Creating output directory..
[02:47:53 - Heads up              ] Output directory created
[02:47:53 - Indexing              ] Indexing...
[02:47:53 - Rendering Style       ] Running full build
[02:48:01 - Indexing              ] Indexing done
[02:48:02 - Rendering Style       ] Running full build
[02:48:02 - Rendering Format      ] Starting PHP-Chunked-XHTML rendering
[02:48:29 - E_USER_WARNING        ] /Users/o0h/src/github.com/jdkfx/phpdoc/phd/phpdotnet/phd/Format/Abstract/XHTML.php:42
	No mapper found for 'package'
[02:48:29 - Rendering Format      ] Writing search indexes..
[02:48:30 - Rendering Format      ] Index written
[02:48:30 - Rendering Format      ] Finished rendering
```
すごい！

```sh
make open
```
phpdoc-ja/my-first-build-OK.png

![php-doc-ja:my-first-build-OK.png](php-doc-ja:my-first-build-OK.png)

うお〜〜〜！すごい！

## 翻訳を試してみる
本当にちゃんと動くのか、作業手順の確認の意味を込めて改変してみる。

トップページは、今こうなっている

![php-doc-ja:bookinfo-original.png](php-doc-ja:bookinfo-original.png)

基本的にはWebサイトのURLとドキュメントのファイルパスが一致しているが、このページに対応するドキュメントはどこ・・・ってなったら雑に`git grep` して目についた文言で引っ掛けても良さそう。

で、こんなdiffを作る

```sh
$ git --no-pager diff
diff --git a/bookinfo.xml b/bookinfo.xml
index 5ffa5d88f..c0cb58679 100644
--- a/bookinfo.xml
+++ b/bookinfo.xml
@@ -9,7 +9,7 @@

   <authorgroup>
     <othercredit role="translator">
-      <orgname>PHP マニュアル翻訳プロジェクト </orgname>
+      <orgname>(๑´ڡ`๑)PHP マニュアル翻訳プロジェクト(๑´ڡ`๑) </orgname>
     </othercredit>
   </authorgroup>
```

改修したら、
```sh
make build
make xhtml
```

で、HTMLの吐き出しまでやってくれる。
(部分的にビルドする方法あるかな？)

![php-doc-ja:my-first-changes.png](php-doc-ja:my-first-changes.png)


できた(๑´ڡ`๑)
