<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram;

class UpdateTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:webhook:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновить данные webhook';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = str_replace('http://', 'https://', route('telegram.webhook'));
        $this->info('Set url: '. $url);
        $result = Telegram::setWebhook([
            'url' => $url
        ]);
        $this->info('Result : '. $result);
        if (!$result->getResult()) {
            $this->error('Error init webhook : '  . $result);
            return;
        } else {
            $this->info('Webhook init');
        }
    }
}
