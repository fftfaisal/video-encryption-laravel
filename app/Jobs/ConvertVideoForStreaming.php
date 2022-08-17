<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
        // Log::channel('faisal')->info("starting job for video ID: {$video->id} at ". now() );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::channel('faisal')->info( "processing job for video ID: {$this->video->id} at " . now() );

        $this->video->update([
            'convert_start_for_streaming_at' => now(),
        ]);
        // create some video formats...
        // $lowBitrateFormat  = (new X264)->setKiloBitrate(500);
        // $midBitrateFormat  = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(4000);
        $encryptionKey = HLSExporter::generateEncryptionKey();
        \Storage::put( 'encrypted/'.$this->video->id.'/' .$this->video->uuid.'.key', $encryptionKey );
        // open the uploaded video from the right disk...
        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->path)
            // ->export()
            // ->inFormat($highBitrateFormat)
            // ->onProgress(function ($percentage) {
            //     echo("{$percentage}% done <br>");
            // })
            // ->toDisk($this->video->disk)
            // ->save($this->video->id .'/' . $this->video->uuid .'.webm');
            // ->open($this->video->id .'/' . $this->video->uuid .'.webm');
        // call the 'exportForHLS' method and specify the disk to which we want to export...
            ->exportForHLS()
            ->withEncryptionKey($encryptionKey,$this->video->uuid.'.key')
            ->toDisk('encrypted')

        // we'll add different formats so the stream will play smoothly
        // with all kinds of internet connections...
            // ->addFormat($lowBitrateFormat)
            // ->addFormat($midBitrateFormat)
            // ->setSegmentLength(10)
            // ->setKeyFrameInterval(100)
            ->addFormat($highBitrateFormat)
            ->onProgress(function ($percentage) {
                Log::channel('faisal')->info( 'progress: ' . $percentage . '%' );
            })
        // call the 'save' method with a filename...
            ->save($this->video->id .'/' . $this->video->uuid .'.m3u8');

        // update the database so we know the convertion is done!
        $this->video->update([
            'converted_for_streaming_at' => now(),
        ]);
        Log::channel('faisal')->info( "ended job for video ID: {$this->video->id} at ". now()  );
    }

    public function failed(\Throwable $exception)
    {
        Log::channel('faisal')->error( $exception );
    }
}
