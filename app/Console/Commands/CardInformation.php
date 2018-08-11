<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\WMLBrowser;
use Symfony\Component\Console\Output\ConsoleOutput;

class CardInformation extends Command
{
    /**
     * @var string
     */
    protected $signature = 'card:information';

    /**
     * @var string
     */
    protected $description = 'Get information for inputed card.';

    /**
     * Actions to be executed when run the command
     * @return mixed
     */
    public function handle()
    {
        $this->output = new ConsoleOutput;

        //Get the card information
        $cardNumber = $this->ask(trans('cardInformation.askCardInformation'));

        $track1 = WMLBrowser::getVar('track1', $cardNumber);
        $track2 = WMLBrowser::getVar('track2', $cardNumber);

        if (!$track2) {
            $this->info(trans('cardInformation.track2DontFound'));
            return false;
        }

        //Check validate date
        if (!WMLBrowser::validateDate($track2)) {
            $this->info(trans('cardInformation.dateDontValid'));
            return false;
        }

        //The Pan first 6 digits must be in between 400000 – 459999.
        if (!WMLBrowser::validatePan($track2)) {
            $this->info(trans('cardInformation.panDontValid'));
            return false;
        }

        //The third digit of Service must be odd.
        if (!WMLBrowser::validateService($track2)) {
            $this->info(trans('cardInformation.serviceDontValid'));
            return false;
        }

        //Check for the first digit of Discretionary Data is equal to 1
        //If true, ask for a PIN number;
        $pin = '';        
        if (WMLBrowser::validateDiscretionaryData($track2)) {
            $pin = $this->askPin();
        }

        if (!empty($track1)) {
            $this->info(trans('cardInformation.cardholderName', [ 'name' => WMLBrowser::getCardholder($track1) ]));
        }

        if (!empty($pin)) {
            $this->info(trans('cardInformation.pinCaptured'));
        }

        return true;
        
    }

    private function askPin()
    {
        $pin = $this->ask(trans('cardInformation.askPin'));
        while (!$this->validatePin($pin)) {
            $pin = $this->ask(trans('cardInformation.askPinAgain'));
        }

        return $pin;
    }

    private function validatePin($pin)
    {
        if (!is_numeric($pin)) {
            return false;
        }

        if (strlen($pin) != WMLBrowser::PIN_SIZE) {
            return false;
        }
        
        return true;
    }
}
