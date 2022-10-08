<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForDownloading;
use App\Jobs\ConvertVideoForStreaming;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class VideoController extends Controller {
    public function index()
    {
        $videos = Video::all();
        // $video = Video::find(1);
        // $this->dispatch( new ConvertVideoForDownloading( $video ) );

        return view('video-index', compact('videos'));
    }
    function store( Request $request ) {
        // $request->dd();
        $request->validate( [
            'title'       => 'required',
            'description' => 'required',
            'video'       => ['required',File::types( ['mp4', 'webm','mov','mkv'] )->max( 5120 * 1024 )],
        ] );
        $videoFile           = $request->file( 'video' );
        $name            = time() . '.' . $videoFile->getClientOriginalExtension();
        $destinationPath = 'videos';
        $path = $videoFile->storeAs( $destinationPath, $name );
        $video = Video::create([
            'uuid'          => \Str::uuid(),
            'disk'          => 'local',
            'original_name' => $request->video->getClientOriginalName(),
            'path'          => $path,
            'title'         => $request->title,
            'description'   => $request->description,
        ]);
        // $this->dispatch( new ConvertVideoForStreaming( $video ) );
        $this->dispatch( new ConvertVideoForDownloading( $video ) );

        return response()->json( ['success' => 'Video Uploaded successfully', 'video_id' => $video->id] );
    }

    public function view(Video $video) {
        if (request()->ajax()) {
            return response()->json(['video' => $video]);
        }
        return view('video-player', compact('video'));
    }

    public function getFile($file) {
        // dd($file->converted_for_streaming_at);
        abort_if( ! auth()->check(), 404 );
        // $fileId = explode( '_', $file );
        if(\Str::isUuid($file)) {
            $videoFile = Video::where('uuid',$file)->firstOrFail();
        } elseif(\Str::endsWith($file,['.m3u8','.ts','.key'])) {
            $fileId = explode( '.', $file )[0];
            if (!\Str::isUuid($fileId)) {
                $fileId = explode( '_', $file )[0];
            }
            $videoFile = Video::where('uuid',$fileId)->firstOrFail();
            $filePath = $videoFile->id .'/'.$file;
        } else {
            $videoFile = Video::findOrFail($file);
            $filePath = $videoFile->id .'/'.$videoFile->uuid.'.m3u8';
        }
        // dd($filePath);
        // return $fileId;
        // $videoFile = Video::where('uuid',$fileId[0] )->orWhere('id',$file)->firstOrFail();
        // $filePath = isset($fileId[1]) ? $file : $videoFile->uuid.'.m3u8';
        return response()->file( storage_path('app/encrypted/'.$filePath) );
    }
    public function processVideo(Video $video)
    {
        return view('video-process', compact('video'));
    }

    public function getPlaylist( $video, $playlist = null ) {
        $video = Video::find($video);
        if (empty($playlist)) {
            $playlist = "{$video->id}/{$video->uuid}.m3u8";
        } else {
            $playlist = "{$video->id}/$playlist";
        }
        // dd($playlist);
        return \FFMpeg::dynamicHLSPlaylist()
        ->fromDisk( 'encrypted' )
        ->open( $playlist )
        ->setKeyUrlResolver( function ( $key ) use($video)  {
            return route( 'video.key', ['key' => urlencode($video->id. '-'.$key)] );
        } )
        ->setMediaUrlResolver( function ( $mediaFilename ) use($video)  {
            // return \Storage::disk( 'encrypted' )->url( $video->id. '/'.$mediaFilename );
            return route('getFile',$mediaFilename);
        } )
        ->setPlaylistUrlResolver( function ( $playlistFilename ) use($video) {
            return route( 'video.playlist', ['playlist' => $playlistFilename, 'video' => $video] );
        } );
    }
    public function getPlaylistKey( $key ) {
        $path = str_replace('-','/',$key);
        return \Storage::disk( 'secrets' )->download( $path );
    }
}
