<?php

namespace App\Console\Commands;

use App\Services\KafkaService;
use Illuminate\Console\Command;

class KafkaConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts consuming kafka events.';

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(KafkaService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->service->consume();
    }
}
