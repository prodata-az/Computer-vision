<?php

namespace App\Http\Controllers\Inference;

use App\Http\Controllers\Controller;
use App\Models\Video;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $modelId = $request->input('model_id');
        $video = $request->file('video');

        $videoName = time().'.mp4';
        $video->storeAs('/', $videoName, 'videos');
        $start = 0;
        $segment = 17;

        $ffprobe = FFProbe::create();
        $duration = $ffprobe->format($video->getRealPath())->get('duration');
        $duration = floor($duration);


        $fullPath = Storage::disk('videos')->path($videoName);
        $path = str_replace(public_path() . '/', '', $fullPath);

        $process = new Process(['/usr/bin/python3', 'splitter.py', '--file', $path]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $videoName = str_replace('.mp4', '', $videoName);
        $video_files = glob(public_path('videos/' . $videoName . '_*.mp4'));

        usort($video_files, function ($a, $b) {
            $num_a = intval(preg_replace('/\D/', '', $a));
            $num_b = intval(preg_replace('/\D/', '', $b));

            return $num_a - $num_b;
        });

        foreach ($video_files as $index => $video_file) {
            $video_path = str_replace(public_path() . '/', '', $video_file);

            if ($index !== 0) $start = $start + $segment;

            Video::create([
                'model_id' => $modelId,
                'video_path' => $video_path
            ]);

            $video = Video::where('video_path', $video_path)->first();

            $videosPaths[$start] = ['id' => $video->id, 'path' => $video->video_path];
        }

        return response()->json(['duration' => $duration, 'video_paths' => $videosPaths]);
    }
}
