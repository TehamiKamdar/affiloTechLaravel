<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\AdvertiserApply;
use Illuminate\Console\Command;

class MakeCustomDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make-custom-domain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Custom Domain';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $advertisers = Advertiser::whereNotNull('url')
            ->where('is_active', Advertiser::AVAILABLE)
            ->whereNull('custom_domain')
            ->take(Vars::LIMIT_5000)
            ->get();

        foreach ($advertisers as $advertiser) {
            $url = $advertiser->url;

            // Handle multiple URLs separated by commas
            if (str_contains($url, ',')) {
                $urls = explode(',', $url);
                $url = $urls[0];
            }

            try {
                $url = trim($url);
                $url = filter_var($url, FILTER_SANITIZE_URL);

                // Ensure URL has a scheme
                if (!preg_match('/^http[s]?:\/\//', $url)) {
                    $url = 'http://' . $url;
                }

                // Validate the URL
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    $this->info("Invalid URL: $url");
                    // Update advertiser with the custom domain
                    $advertiser->update(['is_active' => Advertiser::NOT_AVAILABLE]);
                    continue;
                }

                // Parse the URL and get the host
                $parsedUrl = parse_url($url);
                $domain = $parsedUrl['host'] ?? null;

                if (!$domain) {
                    $this->info("Invalid host in URL: $url");
                    // Update advertiser with the custom domain
                    $advertiser->update(['is_active' => Advertiser::NOT_AVAILABLE]);
                    continue;
                }

                // Remove 'www.' prefix if present
                $domain = preg_replace('/^www\./', '', $domain);

                // Update advertiser with the custom domain
                $advertiser->update(['custom_domain' => $domain]);
            } catch (\Exception $exception) {
                $this->info("Exception: " . $exception->getMessage());
                $this->info("URL: $url");
                continue;
            }
        }
    }
}
