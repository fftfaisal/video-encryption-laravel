<?php

namespace App\Jobs;

use App\Models\Video;
use App\Models\VideoSecret;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter;
use FFMpeg\Format\ProgressListener\AbstractProgressListener;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\ProgressListenerDecorator;

class ConvertVideoForDownloading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $video;

    public $timeout = 3600;

    public $tries = 3;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
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
        $lowBitrate   = ( new X264 )->setKiloBitrate( 250 );
        $midBitrate   = ( new X264 )->setKiloBitrate( 500 );
        $highBitrate  = ( new X264 )->setKiloBitrate( 1000 );
        $superBitrate = ( new X264 )->setKiloBitrate( 1500 );

        \FFMpeg::fromDisk($this->video->disk)
        ->open($this->video->path)
        ->exportForHLS()
        ->withRotatingEncryptionKey( function ( $filename, $contents ) {

            // use this callback to store the encryption keys

            \Storage::disk( 'secrets' )->put( $this->video->id . '/' . $filename, $contents );

            // or...
            VideoSecret::create( [
                'video_id' => $this->video->id,
                'filename' => $filename,
                'contents' => $contents,
            ] );
        } )
        ->addFormat( $lowBitrate )
        ->addFormat( $midBitrate )
        ->addFormat( $highBitrate )
        ->addFormat( $superBitrate )
        ->onProgress(function($percentage) {
            Log::channel('faisal')->info( 'progressed: ' . $percentage . '%' );
            $this->video->update( [
                'processing_percentage' => $percentage,
            ] );
        })
        ->toDisk('encrypted')
        ->save($this->video->id .'/' . $this->video->uuid .'.m3u8');
        // ->save( 'adaptive_steve.m3u8' );

        // update the database so we know the convertion is done!
        $this->video->update([
            'converted_for_streaming_at' => now(),
        ]);
        Log::channel('faisal')->info( "ended job for video ID: {$this->video->id} at ". now()  );
    }
}
