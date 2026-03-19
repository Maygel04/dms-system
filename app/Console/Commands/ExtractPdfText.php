<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\DB;

class ExtractPdfText extends Command
{
    protected $signature = 'pdf:extract';
    protected $description = 'Extract text from existing PDF files into search_text column';

    public function handle()
    {
        $parser = new Parser();

        $docs = DB::table('documents')
            ->whereNotNull('file_name')
            ->get();

        foreach ($docs as $doc) {

            $path = storage_path('app/public/uploads/'.$doc->department.'/'.$doc->file_name);

            if (!file_exists($path)) {
                $this->warn("Missing file: ".$doc->file_name);
                continue;
            }

            try {
                $pdf = $parser->parseFile($path);
                $text = strtolower($pdf->getText());

                DB::table('documents')
                    ->where('id',$doc->id)
                    ->update(['search_text'=>$text]);

                $this->info("Indexed: ".$doc->file_name);

            } catch (\Exception $e) {
                $this->error("Failed: ".$doc->file_name);
            }
        }

        $this->info("DONE indexing PDFs.");
    }
}