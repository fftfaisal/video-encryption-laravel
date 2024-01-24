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
                    <div class="mb-1 text-lg font-medium dark:text-white">Video is currently processing</div>
                    <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                        <div id="percentage"
                            class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                            style="width: {{ $video->processing_percentage }}%"> {{ $video->processing_percentage }}%
                        </div>
                    </div>
                    <div class="text-center mt-8">
                        <a href="{{ route('videos.show', $video) }}" class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500 hover:bg-indigo-400 transition ease-in-out duration-150">
                            Play Video
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            if({{ $video->processing_percentage }} < 100){

            let interval = setInterval(() => {
                $.get('{{ route('videos.show', $video) }}')
                    .then(function(response) {
                        // console.log(response.video.processing_percentage);
                        $('#percentage').css('width', response.video.processing_percentage + '%');
                        $('#percentage').html(response.video.processing_percentage + '%');
                        if (response.video.processing_percentage == 100) {
                            clearInterval(interval);
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }, 2000);
        }
        </script>
    @endsection
</x-app-layout>
