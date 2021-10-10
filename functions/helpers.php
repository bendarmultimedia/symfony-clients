<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

if (!function_exists('getStringBetween')) {
    function getStringBetween($mainString, $startStringToFind, $endStringToFind): string
    {
        $string = ' ' . $mainString;
        $ini = strpos($string, $startStringToFind);

        if ($ini == 0) {
            return '';
        }

        $ini += strlen($startStringToFind);
        $len = strpos($string, $endStringToFind, $ini) - $ini;

        return substr($string, $ini, $len);
    }
}

if (!function_exists('getAllStringsBetween')) {
    function getAllStringsBetween($str, $startDelimiter, $endDelimiter)
    {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $contents;
    }
}

if (!function_exists('inverseHex')) {
    function inverseHex($color)
    {
        $color = TRIM($color);
        $prependHash = false;

        if (STRPOS($color, '#') !== false) {
            $prependHash = true;
            $color = STR_REPLACE('#', '', $color);
        }

        switch ($len = STRLEN($color)) {
            case 3:
                $color = PREG_REPLACE("/(.)(.)(.)/", "\\1\\1\\2\\2\\3\\3", $color);
                break;
            case 6:
                break;
            default:
                TRIGGER_ERROR("Invalid hex length ($len). Must be (3) or (6)", E_USER_ERROR);
        }

        if (!PREG_MATCH('/[a-f0-9]{6}/i', $color)) {
            $color = HTMLENTITIES($color);
            TRIGGER_ERROR("Invalid hex string #$color", E_USER_ERROR);
        }

        $r = DECHEX(255 - HEXDEC(SUBSTR($color, 0, 2)));
        $r = (STRLEN($r) > 1) ? $r : '0' . $r;
        $g = DECHEX(255 - HEXDEC(SUBSTR($color, 2, 2)));
        $g = (STRLEN($g) > 1) ? $g : '0' . $g;
        $b = DECHEX(255 - HEXDEC(SUBSTR($color, 4, 2)));
        $b = (STRLEN($b) > 1) ? $b : '0' . $b;

        return ($prependHash ? '#' : null) . $r . $g . $b;
    }
}

if (!function_exists('blackOrWhiteHex')) {
    function blackOrWhiteHex($color)
    {
        $color = TRIM($color);
        if (STRPOS($color, '#') !== false) {
            $color = STR_REPLACE('#', '', $color);
        }

        switch ($len = STRLEN($color)) {
            case 3:
                $color = PREG_REPLACE("/(.)(.)(.)/", "\\1\\1\\2\\2\\3\\3", $color);
                break;
            case 6:
                break;
            default:
                TRIGGER_ERROR("Invalid hex length ($len). Must be (3) or (6)", E_USER_ERROR);
        }

        if (!PREG_MATCH('/[a-f0-9]{6}/i', $color)) {
            $color = HTMLENTITIES($color);
            TRIGGER_ERROR("Invalid hex string #$color", E_USER_ERROR);
        }

        $r = HEXDEC(SUBSTR($color, 0, 2));
        $g = HEXDEC(SUBSTR($color, 2, 2));
        $b = HEXDEC(SUBSTR($color, 4, 2));
        // if (
        //     (($r <= 127 && $g <= 127)
        //         || ($g <= 127 && $b <= 127)
        //         || ($r <= 127 && $b <= 127))
        //     // || $r + $g + $b <= 370
        // ) {
        if (
            (($r + $g <= 320)
                    || ($g + $b <= 320)
                    || ($r + $b <= 320))
            &&
            (($r <= 129 && $g <= 129)
                || ($g <= 129 && $b <= 129)
                || ($r <= 127 && $b <= 127))
            // || $r + $g + $b <= 370
        ) {
            return '#fff';
        } else {
            return '#000';
        }
    }
}

if (!function_exists('changeArrayByArray')) {
    function changeArrayByArray(array $mainArray, array $newValuesArray)
    {
        foreach ($newValuesArray as $key => $value) {
            if (array_key_exists($key, $mainArray)) {
                $mainArray[$key] = $value;
            }
        }
        return $mainArray;
    }
}

if (!function_exists('removeArrayElementsByKeys')) {
    function removeArrayElementsByKeys(array &$mainArray, array $keysArray): array
    {
        foreach ($keysArray as $key) {
            if (isset($mainArray[$key])) {
                unset($mainArray[$key]);
            }
        }
        return $mainArray;
    }
}

if (!function_exists('polishNumeralInflectionMasculine')) {
    function polishNumeralInflectionMasculine(int $elements)
    {
        $lastChar =  substr($elements, -1);
        if ($elements == 1) {
            return "";
        } elseif (
            $elements == 0
            || ($elements > 1 && $lastChar == "0")
            || ($elements > 1 && (int) $lastChar >= 5 && (int) $lastChar <= 9)
        ) {
            return "ów";
        } elseif ($elements > 1 && (int) $lastChar > 1 && (int) $lastChar <= 4) {
            return "y";
        } else {
            return "";
        }
    }
}

if (!function_exists('checkEmailIsValid')) {
    function checkEmailIsValid(?string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $email == null) {
            return false;
        } else {
            return true;
        }
    }
}
if (!function_exists('checkStringHasString')) {
    function checkStringHasString($string, $subString)
    {
        if (preg_match("/{$subString}/i", $string)) {
            return true;
        } else {
            false;
        }
    }
}
if (!function_exists('addZeroToHour')) {
    function addZeroToHour($value)
    {
        return ($value < 10) ? '0' . $value : $value;
    }
}

if (!function_exists('getCurrentURL')) {
    function getCurrentURL($stringToRemove = '')
    {
        $actualLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $actualLink = str_replace($stringToRemove, '', $actualLink);
        return $actualLink;
    }
}

if (!function_exists('getHostURL')) {
    function getHostURL()
    {
        $actualLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            . "://$_SERVER[HTTP_HOST]";
        return $actualLink;
    }
}


if (!function_exists('throwableMessage')) {
    function throwableMessage(Throwable $exception, $showExceptionInfo = true)
    {
        $message = "";
        // $message = "<div class='alert alert-danger'>";

        if ($showExceptionInfo) {
            $message .= 'Plik: <b>' . $exception->getFile() . '</b> '
                . 'linia: <b> ' . $exception->getLine() . '</b>: <br>'
                . $exception->getMessage();
        } else {
            $message .= $exception->getMessage();
        }
        // $message .= '</div>';
        return $message;
    }
}

if (!function_exists('datetimeToTime')) {
    function datetimeToTime($dateTime, $format = 'd-m-Y')
    {
        return strtotime($dateTime->format($format));
    }
}

if (!function_exists('execPrint')) {
    function execPrint($command, $withLog = false)
    {
        $result = array();
        $file = __DIR__ . '/../panel/var/log/command.log';
        $resultString = "\n==== " . date('d.m.Y H:i:s') . ": ==== \n";
        exec($command, $result);
        if (is_array($result)) {
            print("<pre>");
            foreach ($result as $line) {
                $resultString .= $line . "\n";
                print($line . "\n");
            }
            print("</pre>");
        }

        if ($withLog) {
            $fp = fopen($file, 'a+');
            fwrite($fp, $resultString);
            fclose($fp);
        }
        return $result;
    }
}

// Symfony Functions
/*
if (!function_exists('addFormFlashes')) {
    function addFormFlashes($form, $controller)
    {
        $errors = array();
        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors() as $key => $error) {
                if ($form->isRoot()) {
                    $errors['#'][] = $error->getMessage();
                } else {
                    $errors[] = $error->getMessage();
                }
            }
            foreach ($form->all() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }

            foreach ($errors as $error) {
                $controller->addFlash('error', $error);
            }
        }
    }
}

if (!function_exists('implementSymfony')) {
    function implementSymfony($envFilePath)
    {
        (new Dotenv())->bootEnv($envFilePath);

        if ($_SERVER['APP_DEBUG']) {
            umask(0000);
            Debug::enable();
        }
        return  new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
    }
}
*/
