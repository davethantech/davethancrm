<?php
use Illuminate\Support\Facades\Artisan;
Artisan::command('about-core', function () { $this->info('Davethan CRM first-party core'); })->purpose('Show core identity');