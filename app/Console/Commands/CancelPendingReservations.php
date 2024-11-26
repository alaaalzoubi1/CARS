<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class CancelPendingReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:cancel-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel reservations that have been pending for more than 24 hours';

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
     * @return int
     */
    public function handle()
    {
        $cutoff = Carbon::now()->subHours(24);

        Reservation::where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->update(['status' => 'canceled']);

        $this->info('Canceled pending reservations older than 24 hours.');

        return 0;
    }
//php artisan reservations:cancel-pending

}
