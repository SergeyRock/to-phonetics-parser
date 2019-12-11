<?php

/**
 * Class implements parsing of phonetic transcription from site https://tophonetics.com
 * @author		Sergey Oleynikov
 *
 * Example:
 * $parser = new C_Ol_ToPhoneticsParser();
 * echo $parser->execute('Hello world');
 */

class C_Ol_ToPhoneticsParser
{
    const TO_PHONETICS_URL = 'https://tophonetics.com/ru/';
    const BRACKET_PRE_FOR_CURL = '→';
    const BRACKET_POST_FOR_CURL = '←';
    const BRACKET_PRE = '[';
    const BRACKET_POST = ']';

    const PARSE_TRANSCRIPTION_REG_EX = '<span class="transcribed_word">(.*?)</span>';

    private $lastCurlInfo;

    private $dialect = 'am'; // you are able to set 'br' (British) or 'am' (American)

    public function downloadPage($postData)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, self::TO_PHONETICS_URL);

        curl_setopt($ch, CURLOPT_POST, 1); // POST-method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Add variables

        $pageData = curl_exec($ch);
        $this->lastCurlInfo = curl_getinfo($ch);

        curl_close($ch);

        return $pageData;
    }

    private function parseTranscription($pageData)
    {
        $retRes = array();
        if(preg_match_all('#' . self::PARSE_TRANSCRIPTION_REG_EX .  '#i', $pageData, $matches)) {
            foreach($matches[1] as $match) {
                $retRes[] = strip_tags($match);
            }
        }

        return $retRes;
    }

    public function execute($word)
    {
        $postData = $this->getPostDataForCurl($word);
        if ($postData === false) {
            return false;
        }

        if($pageData = $this->downloadPage($postData)) {
            if($arTranscriptions = $this->parseTranscription($pageData)) {
                return self::transcriptionsToText($arTranscriptions);
            }

            return false;
        }

        return false;
    }

    private static function transcriptionsToText($arTranscriptions)
    {
        $text = implode(self::BRACKET_POST . ' ' . self::BRACKET_PRE, $arTranscriptions);
        $text = self::BRACKET_PRE . $text . self::BRACKET_POST;
        return $text;
    }

    private function getPostDataForCurl($word)
    {
        $word = trim($word);
        if (empty($word)) {
            return false;
        }

        $postData['text_to_transcribe'] = trim($word);
        $postData['output_dialect'] = $this->dialect;
        $postData['output_style'] = 'only_tr';
        $postData['preBracket'] = self::BRACKET_PRE_FOR_CURL;
        $postData['postBracket'] = self::BRACKET_POST_FOR_CURL;
        $postData['speech_support'] = 0;
        $postData['submit'] = 'Показать транскрипцию';

        return $postData;
    }
}