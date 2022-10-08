<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <video id="my-video" class="video-js vjs-theme-{{ request()->get('theme','fantasy') }} vjs-big-play-centered vjs-16-9" width="600" height="240" controls>
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            var player = videojs('my-video',{});
            player.src({
                src: "{{ route('video.playlist',$video) }}",
                type: 'application/x-mpegURL'
            });
        </script>
    @endsection
</x-app-layout>

