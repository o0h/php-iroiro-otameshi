# PIEでローカルにXdebugを入れてみる

## これはなに
作業記録

## やりたいこと
macのローカルに入っているPHPに、[pie](https://github.com/php/pie)を利用してXdebugを入れたい


## 使っている環境
* homebrewで入れたPHPが入っている
  * `/usr/local/bin/php` 
  * 実態は `/usr/local/Cellar/php/8.3.13/bin/php`
* 普段使いたいPHPは、[PHPComplete](https://qiita.com/koriym/items/78d51c2cd34e6a4c6e15)で入れている
  * `/usr/local/opt/php@8.3/bin/php`

## 作業録
### PIEを入れる
https://github.com/php/pie/blob/main/docs/usage.md#one-liner の方法を採用した。  

```sh
sudo curl -L --output /usr/local/bin/pie https://github.com/php/pie/releases/latest/download/pie.phar && sudo chmod +x /usr/local/bin/pie
```

### install

```
$ php83
$ which php
/usr/local/opt/php@8.3/bin/php
$ pie install xdebug/xdebu
g
This command may need elevated privileges, and may prompt you for your password.
You are running PHP 8.3.13
Target PHP installation: 8.3.13 nts, on Linux/OSX/etc x86_64 (from /usr/local/Cellar/php/8.3.13/bin/php)
Found package: xdebug/xdebug:3.4.0 which provides ext-xdebug
Extracted xdebug/xdebug:3.4.0 source to: /Users/o0h/.pie/php8.3_4c13bbc6964e2f56c7c5e6f0c45e3bc1/vendor/xdebug/xdebug
phpize complete.
Configure complete.
Build complete: /Users/o0h/.pie/php8.3_4c13bbc6964e2f56c7c5e6f0c45e3bc1/vendor/xdebug/xdebug/modules/xdebug.so

In PhpBinaryPath.php line 109:

  Could not determine extension path for /usr/local/Cellar/php/8.3.13/bin/php


install [-j|--make-parallel-jobs MAKE-PARALLEL-JOBS] [--with-phpize-path WITH-PHPIZE-PATH] [--with-php-config WITH-PHP-CONFIG] [--with-php-path WITH-PHP-PATH] [--] <requested-package-and-version>

$
```
`php` と打つとPHPCompleteの8.3を使うようにしてみても、グローバルに入れたpieからはシステムデフォルトのPHPを見に行く。  
そりゃそうかって感じがしつつ、これだと都合が悪いので、別のPHPを使わせたい。
`Target PHP installation: XXXX` をどうやって判定しているんだ？と気になったので、コードを見てみたら https://github.com/php/pie/blob/89379c047566313ac9d600f2f07506341f460995/src/Command/CommandHelper.php#L99 に行き着く。そこから更に追っていって、最終的には `Symfony\Component\Process\PhpExecutableFinder` を使っているんだなーとわかった。  

```php
if ($php = getenv('PHP_BINARY')) {
    if (!is_executable($php) && !$php = $this->executableFinder->find($php)) {
        return false;
    }

    if (@is_dir($php)) {
        return false;
    }

    return $php;
}
```
ということなので、環境変数を与えてみよう

### install w/PHP_BINARY
```sh
$ export PHP_BINARY=/usr/local/opt/php@8.3/bin/php
$ pie install xdebug/xdebug
This command may need elevated privileges, and may prompt you for your password.
You are running PHP 8.3.13
Target PHP installation: 8.3.13 nts, on Linux/OSX/etc x86_64 (from /usr/local/opt/php@8.3/bin/php)
Found package: xdebug/xdebug:3.4.0 which provides ext-xdebug
Extracted xdebug/xdebug:3.4.0 source to: /Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug
phpize complete.
Configure complete.
Build complete: /Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug/modules/xdebug.so

In PhpBinaryPath.php line 109:

  Could not determine extension path for /usr/local/opt/php@8.3/bin/php


install [-j|--make-parallel-jobs MAKE-PARALLEL-JOBS] [--with-phpize-path WITH-PHPIZE-PATH] [--with-php-config WITH-PHP-CONFIG] [--with-php-path WITH-PHP-PATH] [--] <requested-package-and-version>
```
Targetが`from /usr/local/opt/php@8.3/bin/php` になったので、これで目的のPHPを見てくれるように。  

ただ、依然としてインストールには成功しない。


https://github.com/search?q=repo%3Aphp%2Fpie%20%2FCould%20not%20determine%20extension%20path%2F&type=code

なるほど、`PHPBinaryPath::extensionPath` でうまく行っていないんだな。

### extension_dirの解決
```sh
$ which php
/usr/local/opt/php@8.3/bin/php
$ php --info |grep extension
extension_dir => /usr/local/lib/php/pecl/20230831 => /usr/local/lib/php/pecl/20230831
mbstring extension makes use of "streamable kanji code filter and converter", which is distributed under the GNU Lesser General Public License version 2.1.
sqlite3.extension_dir => no value => no value
```

ふむ。設定が無いわけではない。そりゃそーだよね〜〜って感じ。

```sh
$ ls /usr/local/lib/php/pecl
ls: /usr/local/lib/php/pecl: No such file or directory
```
怪しそうだね〜って思ったけど、案の定そんなディレクトリが無い。 

あれ、他のextensionどこにいるんだ・・？
```sh
$ which pecl
/usr/local/opt/php@8.3/bin/pecl
$ pecl config-get ext_dir
/usr/local/lib/php/pecl/20230831
$
```

peclでも同じ場所を見ているから、設定値は一貫しているっぽい。

```sh
$ php --ini
Configuration File (php.ini) Path: /usr/local/etc/php/8.3
Loaded Configuration File:         /usr/local/etc/php/8.3/php.ini
Scan for additional .ini files in: /usr/local/etc/php/8.3/conf.d
Additional .ini files parsed:      /usr/local/etc/php/8.3/conf.d/ext-opcache.ini
$ cat /usr/local/etc/php/8.3/conf.d/ext-opcache.ini
[opcache]
zend_extension="/usr/local/opt/php/lib/php/20230831/opcache.so"
```
なるほど。

```diff
-- /usr/local/lib/php/pecl/20230831
++ /usr/local/opt/php/lib/php/20230831/opcache.so
```

php.iniを書き換えればいいかなぁ。

```sh
$ php --info |grep extension
extension_dir => /usr/local/opt/php/lib/php/20230831 => /usr/local/opt/php/lib/php/20230831
mbstring extension makes use of "streamable kanji code filter and converter", which is distributed under the GNU Lesser General Public License version 2.1.
sqlite3.extension_dir => no value => no value
```

あとpeclの設定も
```sh
$ pecl config-get ext_dir
/usr/local/lib/php/pecl/20220829
$ pecl config-set ext_dir /usr/local/opt/php/lib/php/20230831
config-set succeeded
$ pecl config-get ext_dir
/usr/local/opt/php/lib/php/20230831
```

これでどうかしら。

```sh
$ echo $PHP_BINARY
/usr/local/opt/php@8.3/bin/php
o0h@Mac-mini ~ $ pie install xdebug/xdebug
This command may need elevated privileges, and may prompt you for your password.
You are running PHP 8.3.13
Target PHP installation: 8.3.13 nts, on Linux/OSX/etc x86_64 (from /usr/local/opt/php@8.3/bin/php)
Found package: xdebug/xdebug:3.4.0 which provides ext-xdebug
Extracted xdebug/xdebug:3.4.0 source to: /Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug
phpize complete.
Configure complete.
Build complete: /Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug/modules/xdebug.so

In Process.php line 270:

  The command "'make' 'install'" failed.

  Exit Code: 2(Misuse of shell builtins)

  Working directory: /Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug

  Output:
  ================


  Error Output:
  ================
  Makefile:245: warning: overriding commands for target `test'
  Makefile:136: warning: ignoring old commands for target `test'
  mkdir: /usr/local/Cellar/php/8.3.13/pecl: File exists
  mkdir: /usr/local/Cellar/php/8.3.13/pecl: No such file or directory
  make: *** [install-modules] Error 1


install [-j|--make-parallel-jobs MAKE-PARALLEL-JOBS] [--with-phpize-path WITH-PHPIZE-PATH] [--with-php-config WITH-PHP-CONFIG] [--with-php-path WITH-PHP-PATH] [--] <requested-package-and-version>
```

状況は変わった、けどエラーは出る。

### peclが変？(とは？)
peclの場所は誰がどうやって解決してるんだ・・？とりあえずpieのコード見てみる。

https://github.com/search?q=repo%3Aphp%2Fpie%20pecl&type=code

こっちじゃ無さそう  
`/Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug` になにかヒントが有るってことかな。

config.logを見てみると、このあたりが怪しいなー
```
configure:4385: result: x86_64-apple-darwin22.6.0
configure:4490: checking for PHP prefix
configure:4492: result: /usr/local/Cellar/php/8.3.13
configure:4494: checking for PHP includes
configure:4496: result: -I/usr/local/Cellar/php/8.3.13/include/php -I/usr/local/Cellar/php/8.3.13/include/php/main -I/usr/local/Cellar>
configure:4498: checking for PHP extension directory
configure:4500: result: /usr/local/Cellar/php/8.3.13/pecl/20230831
configure:4502: checking for PHP installed headers prefix
configure:4504: result: /usr/local/Cellar/php/8.3.13/include/php
```

configure(lineno付きにしてみた)がこんな感じ
```
4477 if test -z "$prefix"; then
4478   as_fn_error $? "Cannot find php-config. Please use --with-php-config=PATH" "$LINENO" 5
4479 fi
4480 
4481 php_shtool=$srcdir/build/shtool
4482 
4483 test -d include || $php_shtool mkdir include
4484 > Makefile.objects
4485 > Makefile.fragments
4486 pattern=define
4487 $EGREP $pattern'.*include/php' $srcdir/configure|$SED 's/.*>//'|xargs touch 2>/dev/null
4488 
4489 
4490 { printf "%s\n" "$as_me:${as_lineno-$LINENO}: checking for PHP prefix" >&5
4491 printf %s "checking for PHP prefix... " >&6; }
4492 { printf "%s\n" "$as_me:${as_lineno-$LINENO}: result: $prefix" >&5
4493 printf "%s\n" "$prefix" >&6; }
4494 { printf "%s\n" "$as_me:${as_lineno-$LINENO}: checking for PHP includes" >&5
4495 printf %s "checking for PHP includes... " >&6; }
4496 { printf "%s\n" "$as_me:${as_lineno-$LINENO}: result: $INCLUDES" >&5
4497 printf "%s\n" "$INCLUDES" >&6; }
4498 { printf "%s\n" "$as_me:${as_lineno-$LINENO}: checking for PHP extension directory" >&5
4499 printf %s "checking for PHP extension directory... " >&6; }
4500 { printf "%s\n" "$as_me:${as_lineno-$LINENO}: result: $EXTENSION_DIR" >&5
4501 printf "%s\n" "$EXTENSION_DIR" >&6; }
4502 { printf "%s\n" "$as_me:${as_lineno-$LINENO}: checking for PHP installed headers prefix" >&5
4503 printf %s "checking for PHP installed headers prefix... " >&6; }
4504 { printf "%s\n" "$as_me:${as_lineno-$LINENO}: result: $phpincludedir" >&5
4505 printf "%s\n" "$phpincludedir" >&6; }
```

試しにphp-configを明示してみるか・・？

```
$ pie install xdebug/xdebug --with-php-config=/usr/local/opt/php@8.3/bin/php-config
This command may need elevated privileges, and may prompt you for your password.
You are running PHP 8.3.13
Target PHP installation: 8.3.13 nts, on Linux/OSX/etc x86_64 (from /usr/local/Cellar/php/8.3.13/bin/php)
Found package: xdebug/xdebug:3.4.0 which provides ext-xdebug
Extracted xdebug/xdebug:3.4.0 source to: /Users/o0h/.pie/php8.3_4c13bbc6964e2f56c7c5e6f0c45e3bc1/vendor/xdebug/xdebug
phpize complete.
Configure complete with options: --with-php-config=/usr/local/opt/php@8.3/bin/php-config
Build complete: /Users/o0h/.pie/php8.3_4c13bbc6964e2f56c7c5e6f0c45e3bc1/vendor/xdebug/xdebug/modules/xdebug.so

In Process.php line 270:

  The command "'make' 'install'" failed.

  Exit Code: 2(Misuse of shell builtins)

  Working directory: /Users/o0h/.pie/php8.3_4c13bbc6964e2f56c7c5e6f0c45e3bc1/vendor/xdebug/xdebug

  Output:
  ================


  Error Output:
  ================
  Makefile:245: warning: overriding commands for target `test'
  Makefile:136: warning: ignoring old commands for target `test'
  mkdir: /usr/local/Cellar/php/8.3.13/pecl: File exists
  mkdir: /usr/local/Cellar/php/8.3.13/pecl: No such file or directory
  make: *** [install-modules] Error 1


install [-j|--make-parallel-jobs MAKE-PARALLEL-JOBS] [--with-phpize-path WITH-PHPIZE-PATH] [--with-php-config WITH-PHP-CONFIG] [--with-php-path WITH-PHP-PATH] [--] <requested-package-and-version>
```
まぁ変わらんか・・・

大人しく探そうとしている？ファイルがどうなっているのか見てみる

```sh
$ ls -ld /usr/local/Cellar/php/8.3.13/pecl
lrwxr-xr-x  1 o0h  admin  23 Nov 11 22:56 /usr/local/Cellar/php/8.3.13/pecl -> /usr/local/lib/php/pecl
$ ls -ld /usr/local/lib/php/pecl          
ls: /usr/local/lib/php/pecl: No such file or directory
```

しかし、正しいpeclはそこじゃない気がするんだよな
```sh
$ ls -ld $(which pecl)
-r-xr-xr-x  1 o0h  admin  862 Nov 11 22:56 /usr/local/opt/php@8.3/bin/pecl
```

こいつが良く分かってなくて、怪しい気もする
```sh
$ php-config --prefix
/usr/local/Cellar/php/8.3.13
```
それに、 `/usr/local/Cellar/php/8.3.13/pecl` のシンボリックリンク先は存在しないんだけど、 `/usr/local/Cellar/php/8.3.13/bin/pecl` はあるんだよな。実行ファイルじゃないんか・・？

うーむ、この環境固有の問題(=自分が悪い)のか、他の人も同じように踏んでいる地雷・・？っていうのを切り分けるヒントが欲しくて、"/usr/local/lib/php/pecl: No such file or directory" でググる

https://akamist.com/blog/archives/5495


なるほど、削除してみたら変わるのか。やってみよう

```sh
$ cd /usr/local/Cellar/php/8.3.13
$ mv pecl __pecl
```

リベンジ


```sh
 $ pie install xdebug/xdebug
This command may need elevated privileges, and may prompt you for your password.
You are running PHP 8.3.13
Target PHP installation: 8.3.13 nts, on Linux/OSX/etc x86_64 (from /usr/local/opt/php@8.3/bin/php)
Found package: xdebug/xdebug:3.4.0 which provides ext-xdebug
Extracted xdebug/xdebug:3.4.0 source to: /Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug
phpize complete.
Configure complete.
Build complete: /Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug/modules/xdebug.so

In UnixInstall.php line 61:
                                                                                    
  Install failed, /usr/local/opt/php/lib/php/20230831/xdebug.so was not installed.  
                                                                                    

install [-j|--make-parallel-jobs MAKE-PARALLEL-JOBS] [--with-phpize-path WITH-PHPIZE-PATH] [--with-php-config WITH-PHP-CONFIG] [--with-php-path WITH-PHP-PATH] [--] <requested-package-and-version>
```
お、変わったぞ。でも何もわからんな・・・w

### xdebug.so was not installed. 
`Build complete: /Users/o0h/.pie/php8.3_0218e6c79018dcc344d1eca72917225f/vendor/xdebug/xdebug/modules/xdebug.so` つってんのに、`/usr/local/opt/php/lib/php/20230831/xdebug.so` の話をされるのはなぁ。。


```php
$targetExtensionPath = $targetPlatform->phpBinaryPath->extensionPath();

$sharedObjectName             = $downloadedPackage->package->extensionName->name() . '.so';
$expectedSharedObjectLocation = sprintf(
    '%s/%s',
    $targetExtensionPath,
    $sharedObjectName,
);
```
https://github.com/php/pie/blob/89379c047566313ac9d600f2f07506341f460995/src/Installing/UnixInstall.php#L27C1-L34C11

```php
if (! file_exists($expectedSharedObjectLocation)) {
    throw new RuntimeException('Install failed, ' . $expectedSharedObjectLocation . ' was not installed.');
}
```
https://github.com/php/pie/blob/89379c047566313ac9d600f2f07506341f460995/src/Installing/UnixInstall.php#L60-L62

まぁ〜〜〜そりゃそうでしょうね！！って気がする。  

### おしまい(未完)
しっかり直して気持ちよく「動きました！！」って言いたいのだけど、今日は一旦ココまでにする。。。。

ビルドは完了しているので、出来上がったものを手動で使える場所に移しちゃう
```sh
$ cp  /Users/o0h/.pie/php8.3_4c13bbc6964e2f56c7c5e6f0c45e3bc1/vendor/xdebug/xdebug/modules/xdebug.so /usr/local/opt/php/lib/php/20230831/
```

.iniファイルいじってxdebug.soを読み込ませるのも忘れず。
これで動くよな多分。

```sh
$ php -m |grep Xdebug
Xdebug
$ php -v             
PHP 8.3.13 (cli) (built: Oct 22 2024 18:39:14) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.3.13, Copyright (c) Zend Technologies
    with Zend OPcache v8.3.13, Copyright (c), by Zend Technologies
    with Xdebug v3.4.0, Copyright (c) 2002-2024, by Derick Rethans
```
