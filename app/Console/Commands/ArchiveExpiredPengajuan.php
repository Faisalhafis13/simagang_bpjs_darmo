<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ArchiveExpiredPengajuan extends Command
{
    protected $signature = 'magang:archive-expired';

    protected $description =
        'Command arsip otomatis sudah tidak digunakan karena pengarsipan dilakukan secara manual';

    public function handle(): int
    {
        $this->info(
            'Arsip otomatis dinonaktifkan.'
        );

        $this->info(
            'Pengajuan sekarang hanya dapat diarsipkan melalui tombol Arsipkan pada halaman Pengajuan.'
        );

        return self::SUCCESS;
    }
}