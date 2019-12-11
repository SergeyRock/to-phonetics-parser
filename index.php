<?php

include 'toPhoneticsParser.php';

$parser = new C_Ol_ToPhoneticsParser();
echo $parser->execute('Hello world');