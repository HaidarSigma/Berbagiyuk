<?php

namespace App\Console\Commands;

use App\Models\LogAktivitas;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DbBackup extends Command
{
    protected $signature = 'db:backup {--simpan=14 : Jumlah berkas cadangan yang disimpan}';
    protected $description = 'Membuat cadangan database BerbagiYuk dan menghapus cadangan lama';

    public function handle(): int
    {
        $dir = storage_path('app/backup');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $file = $dir.DIRECTORY_SEPARATOR.'berbagiyuk_'.now()->format('Ymd_His').'.sql';
        $db   = config('database.connections.mysql');
        $akun = config('database.backup');

        $proses = new Process([
            $akun['mysqldump'],
            '--host='.$db['host'],
            '--port='.$db['port'],
            '--user='.$akun['username'],
            '--single-transaction',
            '--skip-lock-tables',
            '--skip-triggers',
            '--result-file='.$file,
            $db['database'],
        ]);
        $proses->setTimeout(120);
        $proses->run(null, ['MYSQL_PWD' => (string) $akun['password']]);

        if (! $proses->isSuccessful() || ! is_file($file) || filesize($file) < 100) {
            @unlink($file);
            $this->error('Backup gagal: '.trim($proses->getErrorOutput()));
            LogAktivitas::catat('backup_gagal', mb_substr(trim($proses->getErrorOutput()), 0, 200));
            return self::FAILURE;
        }

        $kb = round(filesize($file) / 1024, 1);

        $semua = glob($dir.DIRECTORY_SEPARATOR.'berbagiyuk_*.sql');
        rsort($semua);
        foreach (array_slice($semua, (int) $this->option('simpan')) as $lama) {
            unlink($lama);
        }

        LogAktivitas::catat('backup_berhasil', basename($file).' | '.$kb.' KB');
        $this->info("Backup berhasil: {$file} ({$kb} KB)");

        return self::SUCCESS;
    }
}