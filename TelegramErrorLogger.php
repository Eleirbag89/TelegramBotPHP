<?php

class TelegramErrorLogger
{

    static public function log($result)
    {
        if ($result['ok'] === false) {
            $self = new self;

            $e = new \Exception();
            $error = PHP_EOL;
            foreach ($result as $key => $value) {
                if  ($value == false)   $error .= $key . ":\t\t\tFalse\n";
                else                    $error .= $key . ":\t\t" . $value ."\n";
            }
            $backtrace = 'Trace: ' . PHP_EOL . $e->getTraceAsString();
            $self->_log_to_file($error . $backtrace);
        }
    }

    /**
     * @param $fileName
     * @param $array
     * @param string $path
     */
    private function _log_to_file($array)
    {
        try {
            $file_name = __CLASS__ . '.txt';
            $myFile = fopen($file_name, "a+");
            if (is_array($array))
                $array = var_export($array, true);
            $date = '[ ' . date('Y-m-d H:i:s  e') . ' ] ';
            fwrite($myFile, "\nDate: \t\t\t" . $date .$array . "\n");
            fclose($myFile);
        } catch (\Exception $e) {
        }
    }

}
