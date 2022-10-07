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
                    <x-auth-validation-errors :errors="$errors" class="mb-6"/>
                    <x-auth-session-status :status="session('success')" class="mb-6"/>
                    {{-- @dump(session()->all()) --}}
                    <form id="video-form" action="{{ route('videos.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label for="large-input"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Material
                                Name</label>
                            <input type="text" id="large-input" name="title" value="{{ old('title') }}"
                                class="block p-4 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div class="mb-6">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Description</label>
                            <textarea id="description" rows="10" name="description" value="{{ old('description') }}"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Your description..."></textarea>

                        </div>
                        <div class="mb-6">
                            <label class="block">
                                <span class="sr-only">Upload file</span>
                                <input type="file" accept="video/mp4,video/x-matroska,video/webm,video/quicktime" name="video"
                                    class="block w-full text-sm text-gray-900 file:mr-4 file:py-4 file:px-4 file:border-0 rounded-lg focus:ring-blue-500 focus:border-blue-500 border border-gray-300 cursor-pointer file:text-sm file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-gray-500 bg-gray-50" />
                            </label>
                        </div>
                        <div class="mb-6">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500 hover:bg-indigo-400 transition ease-in-out duration-150" id="submit">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="loader"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="submit-text">Submit</span>
                            </button>
                        </div>
                        <div class="mb-6">
                            <div class="success-text"></div>
                            <div class="error-text"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            $(function () {
                $('#submit').on('click', function (e) {
                    e.preventDefault();
                    $(this).attr('disabled', true);
                    $('#loader').removeClass('hidden');
                    $(this).find('.submit-text').text('Processing...');
                    // $(this).closest('form').submit();
                    // console.log($(this).closest('form').serialize());
                    let formData = new FormData($('#video-form')[0]);
                    $.ajax({
                        url: "{{ route('videos.store') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();

                            xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                console.log(percentComplete);
                                $('.submit-text').text('Uploading ' + percentComplete + '%');

                                if (percentComplete === 100) {

                                }

                            }
                            }, false);

                            return xhr;
                        },
                        uploadProgress: function(event, position, total, percentComplete) {
                            var percentVal = percentComplete + '%';
                            // bar.width(percentVal);
                            // percent.html(percentVal);
                            $('.submit-text').text('Uploading ' + percentVal);
                        },
                        success: function (data) {
                            if(data?.percentage > 0) {
                                $('.success-text').text('Uploading ' + data.percentage + '%');
                            } else{
                                $('#loader').addClass('hidden');
                                $('#submit').find('.submit-text').text('Submit');
                                $('#submit').attr('disabled', false);
                                $('.success-text').text(data.success).addClass('text-green-500');
                            }
                            console.log(data);
                        },
                        error: function (error) {
                            $('#loader').addClass('hidden');
                            $('#submit').find('.submit-text').text('Submit');
                            $('#submit').attr('disabled', false);
                            $('.error-text').text(error.responseJSON.message);
                            console.log(error);
                        }
                    });
                });
            });
        </script>
    @endsection
</x-app-layout>

