<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::create([
        	'page_title'   => 'Terms And Conditions',
            'page_slug'    => 'terms-and-conditions',
            'page_details' => 'Terms And Conditions',
        ]);

        Page::create([
        	'page_title'   => 'Privacy Policy',
            'page_slug'    => 'privacy-policy',
            'page_details' => 'Privacy Policy',
        ]);

        // Page::create([
        // 	'page_title'   => 'Service Agreement',
        //     'page_slug'    => 'service-agreement',
        //     'page_details' => 'The Mukta Strict cancellation policy allows guests to receive a full refund if they cancel within 48 hours of booking and at least 14 days before a listing"s check-in time.
        //     In the event of a cancellation within 48 hours, the guest is only entitled to 50% refund, regardless of how far out the check-in date is.',
        // ]);

    }
}
