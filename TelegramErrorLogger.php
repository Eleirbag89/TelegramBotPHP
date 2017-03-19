<?php
/**
 * Telegram Error Logger Class.
 * @author shakibonline
 */
class TelegramErrorLogger
{
    /// Log request and response parameters from/to Telegrsm API
    /**
     * Prints the list of parameters from/to Telegram's API endpoint
     * \param $result the Telegram's response as array
     * \param $content the request parameters as array
     */
    static public function log($result,$content)
    {
        try {
            if ($result['ok'] === false) {
                $self = new self;
                $e = new \Exception();
                $error = PHP_EOL;
                $error .= "==========[Response]==========";
                $error .= "\n";
                foreach ($result as $key => $value) {
                    if  ($value == false)   $error .= $key . ":\t\t\tFalse\n";
                    else                    $error .= $key . ":\t\t" . $value ."\n";
                }
                $array = "=========[Sent Data]==========";
                $array .= "\n";
                foreach ($content as $key => $value) {
                    $array .= $key . ":\t\t" . $value ."\n";
                }
                $backtrace = "============[Trace]===========";
                $backtrace .= "\n";
                $backtrace .= $e->getTraceAsString();
                $self->_log_to_file($error . $array . $backtrace);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /// Write a string in the log file adding the current server time
    /**
     * Write a string in the log file TelegramErrorLogger.txt adding the current server time
     * \param $error_text the text to append in the log
     */
    private function _log_to_file($error_text)
    {
        try {
            $fileName = __CLASS__ . '.txt';
            $myFile = fopen($fileName, "a+");
            $date = "============[Date]============";
            $date .= "\n";
            $date .= '[ ' . date('Y-m-d H:i:s  e') . ' ] ';
            fwrite($myFile, $date .$error_text . "\n\n");
            fclose($myFile);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}