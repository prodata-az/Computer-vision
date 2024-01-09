<?php

namespace App\Http\Controllers\Train;

use App\Http\Controllers\Controller;
use App\Models\Video;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFProbe;
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

    // $video_path = $video->getPathName();
    // $segment_duration = 15; 

    // for ($i = 0; $i < 60; $i += $segment_duration) {
    //   $output_path = "output{$i}.mp4";
    //   $command = "ffmpeg  -ss {$i} -i {$video_path} -t {$segment_duration} -codec copy {$output_path}";
    //   shell_exec($command);
    // }

    $videoName = time().'.mp4';
    $video->storeAs('/', $videoName, 'videos');

    $start = 0;
    $segment = 17;
    $data = [];

    $fullPath = Storage::disk('videos')->path($videoName);
    $path = str_replace(public_path() . '/', '', $fullPath);

    $process = new Process(['/usr/bin/python3', 'splitter.py', '--file' , $path]);
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


      $data[$start] = ['id' => $video->id, 'path' => $video->video_path];
    }

    return response()->json($data);
  }
}
