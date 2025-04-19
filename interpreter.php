<?php
require __DIR__ . '/LLM.php';

function includes($from) {
    $lines = file($from, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $working = getcwd();
    chdir(pathinfo($from, PATHINFO_DIRNAME));

    foreach ($lines as $line) {
        if (str_starts_with(ltrim($line), 'include')) {
            compile(include_path($line), true);
        }
    }

    chdir($working);
}

function includes_rename($script) : string {
    $processed = '';

    foreach (explode("\n", $script) as $line) {
        if (str_starts_with(ltrim($line), 'include')) {
            $inc = include_path($line);
            $dir =  pathinfo($inc, PATHINFO_DIRNAME);
            $base = pathinfo($inc, PATHINFO_FILENAME);
            $processed .= "require '$dir/.$base.php';\n";
        } else {
            $processed .= "$line\n";
        }
    }

    return $processed;
}

function include_path($path) : string {
    $path = ltrim($path);
    $path = substr($path, 7);
    $path = str_replace(["\t", ';', "'", '"', '(', ')'], ' ', $path);
    $path = ltrim($path);
    return explode(' ', $path)[0];
}

function check_hash($php_script, $vibe_script) {
    $handle = @fopen($php_script, 'r');
    if ($handle === false || !file_exists($vibe_script)) return false;

    fgets($handle); //skip first line
    $line = trim(fgets($handle));
    if (!str_starts_with($line, '//HASH=')) return false;
    $hash = explode('=',$line)[1];
    fclose($handle);

    return hash_file('sha256', $vibe_script) === $hash;
}

function compile($file, $included = false) : string {
    $original_dir  = pathinfo($file, PATHINFO_DIRNAME);
    $base_filename = pathinfo($file, PATHINFO_FILENAME);
    $php_file  = "$original_dir/.$base_filename.php";
    $vibe_file = "$base_filename.vibe";

    if (!check_hash($php_file, $file)) {
        echo "compiling '$vibe_file'...\n";
        $php_ver = 'PHP' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
        $prompt = "Take the pseudo-code and convert it to $php_ver. RAW OUTPUT ONLY and don't enclose in ``` or output '<?php', don't get creative fixing or adding code that isn't requested. default to snake_case. file will be included so no need to add anything to call functions.";
        $processed = includes_rename(file_get_contents($file));
        $processed = prompt($processed, $prompt);
        $script  = "<?php\n";
        $script .= "//HASH=".hash_file('sha256', $file)."\n";
        $script .= "ob_implicit_flush(true);";
        if (!$included) $script .= "require '" . __DIR__ . "/LLM.php'; // provides prompt() and categorise()\n";
        $script .= "$processed\n";
        file_put_contents($php_file, $script);
    }

    includes($file);
    return $php_file;
}

function run($filename) {
    $php_script = compile($filename);
    $script_dir = pathinfo($filename, PATHINFO_DIRNAME);
    chdir($script_dir);
    run_script($php_script);
}

function run_script($filename) {
    $errors = '';
    $descriptors = [ ["pipe", "r"], ["pipe", "w"], ["pipe", "w"] ];
    $process = proc_open("php $filename", $descriptors, $pipes);

    if (is_resource($process)) {
        stream_set_blocking($pipes[0], false);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $closed = false;

        while(!$closed) {
            $read   = [$pipes[2], $pipes[1], STDIN];
            $write  = [];
            $except = [];

            $n = stream_select($read, $write, $except, null);
            if ($n === false) break;
            if ($n === 0) continue;

            foreach ($read as $stream) {
                if ($stream === $pipes[1]) {
                    $data = fread($pipes[1], 1024);
                    if ($data === false || $data === '') $closed = true;
                    else echo $data;
                }

                if ($stream === $pipes[2]) {
                    $err = fread($pipes[2], 1024);
                    if ($err !== false || $err !== '') {
                        error_msg($err);
                        $errors .= $err;
                    }
                }

                if ($stream === STDIN) {
                    $line = fgets(STDIN);
                    if ($line !== false) fwrite($pipes[0], $line);
                }
            }
        }

        fclose($pipes[0]); fclose($pipes[1]); fclose($pipes[2]);
        //proc_close($process);
    }

    if ($errors != '') {
        $dir = pathinfo($filename, PATHINFO_DIRNAME);
        file_put_contents("$dir/error.log", $errors);
        //TODO on error, add comment to vibe script
        //$filelist = prompt($stderr, "OUTPUT ONLY. return comma seperated list of the filenames (including directory) of the php scripts that had errors. no whitespace.");
        //foreach (explode(',', $filelist) as $filename) @unlink($filename);
        //echo "error occured. failed parts cleared. try running again.";
    }
}

function error_msg(string $err) {
    echo "\033[0;31m$err\033[0m";
}

if (isset($argv[1])) {
    if (!file_exists($argv[1])) { error_msg("error: no script found.\n"); exit; }
    run($argv[1]);
}
?>
