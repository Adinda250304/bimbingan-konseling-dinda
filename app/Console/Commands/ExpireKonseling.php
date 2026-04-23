<?php

namespace App\Console\Commands;

use App\Models\Konseling;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireKonseling extends Command
{
    protected $signature   = 'konseling:expire';
    protected $description = 'Hanguskan sesi konseling yang sudah melewati 2 jam dari waktu jadwal.';

    public function handle(): void
    {
        $batas = Carbon::now()->subHours(2);

        // Gabungkan tanggal + jam menjadi datetime, lalu bandingkan
        // Sesi yang sudah lewat > 2 jam dari jadwal → tandai tidak hadir
        $expired = Konseling::where('status', 'disetujui')
            ->whereNotNull('tanggal_konseling')
            ->whereNotNull('jam_konseling')
            ->get()
            ->filter(function ($k) use ($batas) {
                $jadwal = Carbon::parse(
                    $k->tanggal_konseling->format('Y-m-d') . ' ' . $k->jam_konseling
                );
                return $jadwal->lt($batas);
            });

        $count = $expired->count();

        if ($count > 0) {
            Konseling::whereIn('id', $expired->pluck('id'))
                ->update(['status' => 'tidak_hadir']);

            $this->info("{$count} sesi ditandai tidak hadir (lewat 2 jam).");
        } else {
            $this->info('Tidak ada sesi yang perlu diupdate.');
        }
    }
}
