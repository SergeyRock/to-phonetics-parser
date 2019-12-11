# ToPhonetics parser
Parser for getting transcription from https://tophonetics.com site.

## Instalation
Just put sources to your webserver.

## Examples
```php
<php
include 'toPhoneticsParser.php';
$parser = new C_Ol_ToPhoneticsParser();
echo $parser->execute('Hello world');
?>
```

It got next string:
[həˈloʊ] [wɜrld]

## Using with Google Spreadsheets
Formula:
```
=IMPORTDATA("http://www.yoursite.com/to_phonetics/?word="&ENCODEURL("Hello world"))
```

![](https://www.uchitel-izd.ru/upload/files/clip2net/ol/2019/12.11-11572.png)
