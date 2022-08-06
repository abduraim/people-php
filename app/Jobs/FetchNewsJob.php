<?php

namespace App\Jobs;

use App\Services\HtmlParser\HtmlParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param int $newsCount Кол-во новостей, которые необходимо загрузить
     *
     * @return void
     */
    public function __construct(
        private int $newsCount
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(HtmlParser $htmlParser)
    {
        $htmlParser->fetch($this->newsCount);
    }
}
