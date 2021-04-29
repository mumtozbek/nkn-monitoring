<?php

namespace App\Console\Commands;

use App\Models\Node;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReindexNodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nodes:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex nodes';

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
        DB::table('blocks')->truncate();
        DB::table('proposals')->truncate();

        $nodes = Node::all();

        $nodes->each(function($node) {
            try {
                $nodeState = [
                    'status' => $node->status,
                    'version' => $node->version,
                    'height' => $node->height,
                    'relays' => $node->relays,
                    'uptime' => $node->uptime,
                ];

                Cache::forget('nodes.mined.' . $node->id);

                $node->update([
                    'status' => 'WAIT_FOR_SYNCING',
                    'version' => '',
                    'height' => 0,
                    'relays' => 0,
                    'uptime' => 0,
                ]);

                $node->uptimes->sortBy('created_at')->each(function($uptime) use ($node) {
                    $node->reindex($uptime->response, $uptime->created_at);
                });

                $node->update($nodeState);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
            }
        });
    }
}
