# ToPhonetics parser
Parser for getting transcription from https://tophonetics.com site.

## Examples
include 'toPhoneticsParser.php';

$parser = new C_Ol_ToPhoneticsParser();

echo $parser->execute('Hello world');

It got next string:
[həˈloʊ] [wɜrld]

