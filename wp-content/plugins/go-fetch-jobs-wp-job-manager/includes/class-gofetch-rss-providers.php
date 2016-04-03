<?php

/**
 * Sets up the write panels used by the schedules (custom post types).
 *
 * @package GoFetch/Admin/Providers
 */
if ( !defined( 'ABSPATH' ) ) {
    die;
}
/**
 * Schedules meta boxes base class.
 */
class GoFetch_WPJM_RSS_Providers
{
    public static function valid_item_tags()
    {
        $fields = array(
            'location',
            'geolocation',
            'latitude',
            'longitude',
            'company',
            'logo'
        );
        return apply_filters( 'goft_wpjm_providers_valid_item_tags', $fields );
    }
    
    public static function valid_regexp_tags()
    {
        $patterns = array(
            'location' => array( '/Location.*?:(.*?)<.*?>/is', '/Headquarters.*?:<.*?>(.*?)<.*?>/is', '/Location.*?<.*?>(.*?)<.*?>/is' ),
            'company'  => array(
            '/Advertiser.*?:(.*?)<.*?>/is',
            '/Company.*?<.*?>(.*?)<.*?>/is',
            '/Company.*?:(.*?)<.*?>/is',
            '/Posted by.*?<.*?>(.*?)<.*?>/is'
        ),
            'salary'   => array( '/Salary.*?:(.*?)$/is', '/Salary\\/Rate.*?:(.*?)<.*?>/is' ),
        );
        return apply_filters( 'goft_wpjm_providers_valid_regexp_tags', $patterns );
    }
    
    /**
     * Retrieves a list of providers and their details.
     */
    public static function get_providers( $provider = '' )
    {
        $providers = array(
            'freelancewritinggigs.com'    => array(
            'website'     => 'http://www.freelancewritinggigs.com/',
            'logo'        => 'https://jobmob.co.il/images/articles/freelance-marketplaces/freelancewritinggigs-freelance-marketplace-logo.png',
            'description' => 'Freelance Writing Job Board',
            'feed'        => array(
            'base_url'     => 'http://www.freelancewritinggigs.com/?feed=job_feed',
            'search_url'   => 'http://www.freelancewritinggigs.com/freelance-writing-job-ads/',
            'example'      => 'http://www.freelancewritinggigs.com/?feed=job_feed&job_categories=blogging-jobs',
            'sample'       => 'http://www.freelancewritinggigs.com/?feed=job_feed&job_types=contract%2Cfreelance&[etc...]',
            'meta'         => array( 'company', 'location' ),
            'tag_mappings' => array(
            'type' => 'job_type',
        ),
            'query_args'   => array(
            'keyword'  => array(
            'search_keywords' => '',
        ),
            'location' => array(
            'search_location' => '',
        ),
        ),
            'default'      => true,
        ),
            'category'    => __( 'Blogging', 'gofetch-wpjm' ),
            'weight'      => 7,
        ),
            'jobs.wordpress.net'          => array(
            'website'     => 'http://jobs.wordpress.net/',
            'logo'        => 'https://s.w.org/about/images/logos/wordpress-logo-notext-rgb.png',
            'description' => 'WordPress related Job Postings',
            'feed'        => array(
            'base_url'   => 'http://jobs.wordpress.net/feed/',
            'search_url' => 'http://jobs.wordpress.net/?s=',
            'example'    => 'http://jobs.wordpress.net/search/design/feed/rss2/',
            'sample'     => 'http://jobs.wordpress.net/search/design/feed/rss2/',
            'query_args' => array(
            'keyword' => array(
            's' => '',
        ),
        ),
            'examples'   => array(
            __( 'Latest Jobs', 'gofetch-wpjm' )                    => 'http://jobs.wordpress.net/feed/',
            __( 'Latest Design Jobs', 'gofetch-wpjm' )             => 'http://jobs.wordpress.net/job_category/design/feed/',
            __( 'Latest Plugin Development Jobs', 'gofetch-wpjm' ) => 'http://jobs.wordpress.net/job_category/plugin-development/feed/',
        ),
        ),
            'weight'      => 8,
            'category'    => __( 'WordPress', 'gofetch-wpjm' ),
        ),
            'jobs.theguardian.com'        => array(
            'website'     => 'https://jobs.theguardian.com/',
            'logo'        => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/The_Guardian.svg/2000px-The_Guardian.svg.png',
            'description' => 'Great jobs on the Guardian Jobs site',
            'feed'        => array(
            'base_url'        => 'https://jobs.theguardian.com/jobsrss/',
            'search_url'      => 'https://jobs.theguardian.com/jobs/',
            'example'         => 'https://jobs.theguardian.com/jobsrss/?keywords=arts&countrycode=GB',
            'sample'          => 'https://jobs.theguardian.com/jobsrss/?JobLevel=19&keywords=arts&countrycode=GB&[etc...]',
            'meta'            => array( 'logo' ),
            'regexp_mappings' => array(
            'company'  => '/(?(?!.*?:\\s.*?:\\s.*?)(.*?)-|.*?:(.*?):\\s)/is',
            'location' => '/\\.([^.]+)$/is',
        ),
            'query_args'      => array(
            'keyword'  => array(
            'keywords' => '',
        ),
            'location' => array(
            'radialtown' => '',
        ),
        ),
            'default'         => true,
        ),
            'weight'      => 9,
            'category'    => __( 'Generic', 'gofetch-wpjm' ),
        ),
            'expressoemprego.pt'          => array(
            'website'     => 'http://expressoemprego.pt/',
            'logo'        => 'https://pbs.twimg.com/profile_images/3116193991/c5507f53c7632991b9b163b39bb3d3f3_400x400.png',
            'description' => 'A sua Carreira é o nosso Trabalho',
            'feed'        => array(
            'base_url'        => 'http://expressoemprego.pt/rss',
            'meta'            => array( 'logo' ),
            'regexp_mappings' => array(
            'company'  => '/^(.*?)\\|/is',
            'location' => '/\\|(.*?)\\|/is',
        ),
            'examples'        => array(
            __( 'Latest 50 Jobs', 'gofetch-wpjm' )        => 'http://www.expressoemprego.pt/rss/ultimas-ofertas',
            __( 'Latest Internet Jobs', 'gofetch-wpjm' )  => 'http://expressoemprego.pt/rss/internet',
            __( 'Latest Jobs in Lisbon', 'gofetch-wpjm' ) => 'http://expressoemprego.pt/rss/lisboa',
        ),
        ),
            'weight'      => 6,
            'category'    => __( 'Generic', 'gofetch-wpjm' ),
        ),
            'cargadetrabalhos.net'        => array(
            'website'     => 'http://www.cargadetrabalhos.net/',
            'logo'        => 'http://www.cargadetrabalhos.net/wp-content/themes/bluekino%28en%29/images/cdtlogosombratop.jpg',
            'description' => 'Emprego na área da comunicação',
            'feed'        => array(
            'base_url' => 'http://www.cargadetrabalhos.net/feed/',
            'default'  => true,
        ),
            'weight'      => 6,
            'category'    => __( 'Marketing', 'gofetch-wpjm' ),
        ),
            'jobs.marketinghire.com'      => array(
            'website'     => 'http://jobs.marketinghire.com/',
            'logo'        => 'http://www.marketinghire.com/templates/ja_university/images/logo.png',
            'description' => 'All Marketing jobs',
            'feed'        => array(
            'base_url'        => 'http://jobs.marketinghire.com/jobs/?display=rss',
            'search_url'      => 'http://jobs.marketinghire.com/jobs',
            'example'         => 'http://jobs.marketinghire.com/jobs/?display=rss&keywords=specialist&resultsPerPage=25',
            'sample'          => 'http://jobs.marketinghire.com/jobs/?display=rss&keywords=specialist&filter=&[etc...]',
            'meta'            => array( 'logo' ),
            'regexp_mappings' => array(
            'location' => '/^(.*?),/is',
        ),
            'query_args'      => array(
            'keyword'  => array(
            'keywords' => '',
        ),
            'location' => array(
            'place' => '',
        ),
        ),
            'default'         => true,
        ),
            'weight'      => 7,
            'category'    => __( 'Marketing', 'gofetch-wpjm' ),
        ),
            __( 'Other', 'gofetch-wpjm' ) => array(
            'website'     => '#',
            'logo'        => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/46/Generic_Feed-icon.svg/500px-Generic_Feed-icon.svg.png',
            'description' => 'Use RSS feed from other provider',
            'feed'        => array(
            'base_url' => '#',
        ),
            'category'    => __( 'Other', 'gofetch-wpjm' ),
            'weight'      => 99,
        ),
        );
        $providers = apply_filters( 'goft_wpjm_providers', $providers );
        if ( $provider ) {
            
            if ( empty($providers[$provider]) ) {
                return array();
            } else {
                return $providers[$provider];
            }
        
        }
        return $providers;
    }
    
    /**
     * Retrieves the RSS feed setup instructions for a given provider.
     */
    public static function setup_instructions_for( $provider )
    {
        $setup = $header = $multi_region = $steps_li = $skip_copy_url = '';
        $steps = 0;
        $data = self::get_providers( $provider );
        if ( empty($data['feed']['search_url']) ) {
            $data['feed']['search_url'] = $data['feed']['base_url'];
        }
        // __Header.
        
        if ( !empty($data['feed']['base_url']) ) {
            $header = html( 'a', array(
                'href'   => esc_url( $data['website'] ),
                'target' => '_blank',
            ), html( 'img', array(
                'src'   => esc_url( $data['logo'] ),
                'class' => 'provider-logo-orig',
            ) ) );
            $header .= html( 'p', html( 'em', $data['description'] ) );
        }
        
        $header = html( 'div class="provider-header"', $header );
        // __Meta.
        $base_data = array( __( 'Title', 'gofetch-wpjm' ), __( 'Description', 'gofetch-wpjm' ), __( 'Date', 'gofetch-wpjm' ) );
        $meta_data = array();
        if ( !empty($data['feed']['meta']) ) {
            $meta_data = array_merge( $meta_data, $data['feed']['meta'] );
        }
        if ( !empty($data['feed']['tag_mappings']) ) {
            $meta_data = array_merge( $meta_data, array_keys( $data['feed']['tag_mappings'] ) );
        }
        if ( !empty($data['feed']['regexp_mappings']) ) {
            $meta_data = array_merge( $meta_data, array_keys( $data['feed']['regexp_mappings'] ) );
        }
        $meta_li = '';
        foreach ( $base_data as $field ) {
            $meta_li .= html( 'li', ucfirst( $field ) );
        }
        $data_info = html( 'p', sprintf( __( 'Data provided in RSS feeds: %s', 'gofetch-wpjm' ), html( 'ul', $meta_li ) ) );
        
        if ( !empty($meta_data) ) {
            $meta_data = array_intersect( self::valid_item_tags(), $meta_data );
            $meta_li = '';
            foreach ( $meta_data as $field ) {
                $meta_li .= html( 'li', ucfirst( $field ) );
            }
            if ( $meta_li ) {
                $data_info .= html( 'p', sprintf( __( 'Other data <small>(not always available)</small>: %1$s %2$s', 'gofetch-wpjm' ), GoFetch_WPJM_Admin::limited_plan_warn(), html( 'ul', $meta_li ) ) );
            }
        }
        
        $data_info = html( 'div class="provider-data secondary-container"', $data_info );
        // __Multi Region.
        $multi_region = '';
        
        if ( isset( $data['multi-region'] ) ) {
            $multi_region = '<br/>' . __( 'This is a multi region jobs site. These instructions are meant for a specific country site but they should also work with any of the other available country sites.', 'gofetch-wpjm' );
            
            if ( !empty($data['multi-region']) ) {
                $multi_region .= '   ' . html( 'span', html( 'a', array(
                    'href'         => '#',
                    'class'        => 'provider-expand-multi-region-details',
                    'data-child'   => 'multi-region-details',
                    'data-default' => __( 'Info', 'gofetch-wpjm' ),
                ), __( 'Info', 'gofetch-wpjm' ) ) );
                $multi_region .= sprintf( '<p class="multi-region-details secondary-container">%s</p>', $data['multi-region'] );
            }
        
        }
        
        switch ( $provider ) {
            case 'idealist.org':
            case 'technojobs.co.uk':
            case 'freelancewritinggigs.com':
            case 'jobs.smashingmagazine.com':
            case 'dribbble.com':
            case 'mediabistro.com':
            case 'totaljobs.com':
            case 'authenticjobs.com':
            case 'jobs.gamasutra.com':
            case 'craigslist.org':
            case 'uk.dice.com':
            case 'sap.dice.com':
            case 'indeed.com':
            case 'careerjet.com':
            case 'jobs.marketinghire.com':
            case 'reed.co.uk':
                $steps_li .= html( 'li', sprintf( __( 'Visit the provider job search page by clicking <a href="%2$s" target="_blank">here</a>.</p>', 'gofetch-wpjm' ), ++$steps, esc_url( $data['feed']['search_url'] ) ) );
                $steps_li .= html( 'li', sprintf( __( 'Setup your jobs criteria and click the search button or \'Enter\'.</p>', 'gofetch-wpjm' ), ++$steps ) );
                $steps_li .= html( 'li', sprintf( __( '<strong>OR</strong> ... click on any existing pre-set filters (if available) like <code>Jobs by Location</code>, <code>Jobs by Title</code>, etc...</p>', 'gofetch-wpjm' ), ++$steps ) );
                switch ( $provider ) {
                    case 'idealist.org':
                    case 'freelancewritinggigs.com':
                        $steps_li .= html( 'li', sprintf( __( 'After you get the results click the <code>RSS</code> link/button on top of the search results.</p>', 'gofetch-wpjm' ), ++$steps ) );
                        break;
                    case 'jobs.gamasutra.com':
                    case 'jobs.theguardian.com':
                        $steps_li .= html( 'li', sprintf( __( 'After you get the results scroll to the bottom of the page and click the <code>Subscribe/Subscribe to RSS</code> link.</p>', 'gofetch-wpjm' ), ++$steps ) );
                        break;
                    case 'technojobs.co.uk':
                        $steps_li .= html( 'li', sprintf( __( 'After you get the results click the <code>%2$s</code> link.</p>', 'gofetch-wpjm' ), ++$steps, __( 'Subscribe to an RSS Feed for this search', 'gofetch-wpjm' ) ) );
                        $steps_li .= html( 'li', sprintf( __( 'You will be taken to a new page that contains the URL of the generated RSS feed. Look for it under the <code>%2$s</code> text.</p>', 'gofetch-wpjm' ), ++$steps, __( 'The address for your RSS feed is here:', 'gofetch-wpjm' ) ) );
                        $skip_copy_url = true;
                        break;
                    case 'authenticjobs.com':
                        $steps_li .= html( 'li', sprintf( __( 'After you get the results click the <code>Email/RSS Notifications</code> button on top of the search results.</p>', 'gofetch-wpjm' ), ++$steps ) );
                        $steps_li .= html( 'li', sprintf( __( 'Click the <code>RSS</code> option and copy & paste the URL to your browser address bar.</p>', 'gofetch-wpjm' ), ++$steps ) );
                        break;
                    case 'reed.co.uk':
                        $steps_li .= html( 'li', sprintf( __( 'After you get the results replace everything in your URL before the <code>?</code> with the RSS feed URL <code>%2$s</code>.</p>', 'gofetch-wpjm' ), ++$steps, $data['feed']['base_url'] . '?' ) );
                        $steps_li .= html( 'li', sprintf( __( 'If you specified <code>location</code> as a criteria you need to manually add it to the URL parameters <code>%2$s</code>.</p>', 'gofetch-wpjm' ), ++$steps, add_query_arg( 'location', 'london', $data['feed']['base_url'] ) ) );
                        break;
                    case 'jobs.smashingmagazine.com':
                    case 'mediabistro.com':
                    case 'dribbble.com':
                    case 'totaljobs.com':
                    case 'indeed.com':
                    case 'careerjet.com':
                        $steps_li .= html( 'li', sprintf( __( 'After you get the results replace everything in your URL before the <code>?</code> with the RSS feed URL <code>%2$s</code>.</p>', 'gofetch-wpjm' ), ++$steps, $data['feed']['base_url'] . '?' ) );
                        break;
                    default:
                        $steps_li .= html( 'li', sprintf( __( 'After you get the results click the <code>RSS</code> (<span class="dashicons dashicons-rss"></span>) link/button/icon on the bottom.</p>', 'gofetch-wpjm' ), ++$steps ) );
                        break;
                }
                break;
            case 'krop.com':
            case 'coroflot.com':
            case 'jobs.problogger.net':
            case 'cargadetrabalhos.net':
                $setup .= __( '<p>Unfortunately this provider does not allow customizing the RSS feed.</p><br/>', 'gofetch-wpjm' );
                break;
            case 'jobs.wordpress.net':
                $setup .= __( '<p>Unfortunately this provider does not allow customizing the RSS feed but provides keywords and categorized feeds.</p><br/>', 'gofetch-wpjm' );
                $steps_li .= html( 'li', sprintf( __( 'Visit the provider jobs page by clicking <a href="%2$s" target="_blank">here</a>.</p>', 'gofetch-wpjm' ), ++$steps, esc_url( $data['feed']['base_url'] ) ) );
                $steps_li .= html( 'li', sprintf( __( 'Click on any of the RSS icons (<span class="dashicons dashicons-rss"></span>) over each of the job results groups.</p>', 'gofetch-wpjm' ), ++$steps ) );
                $steps_li .= html( 'li', sprintf( __( '<strong>OR</strong> ... do a search for the keywords you want to use and click on the RSS icon (<span class="dashicons dashicons-rss"></span>) on top of the search results.</p>', 'gofetch-wpjm' ), ++$steps ) );
                break;
            case 'weworkremotely.com':
                $setup .= __( '<p>Unfortunately this provider does not allow customizing the RSS feed but provides categorized feeds.</p><br/>', 'gofetch-wpjm' );
                $steps_li .= html( 'li', sprintf( __( 'Visit the provider jobs page by clicking <a href="%2$s" target="_blank">here</a>.</p>', 'gofetch-wpjm' ), ++$steps, esc_url( $data['feed']['search_url'] ) ) );
                $steps_li .= html( 'li', sprintf( __( 'Click on any of the RSS icons (<span class="dashicons dashicons-rss"></span>) over each of the job results groups.</p>', 'gofetch-wpjm' ), ++$steps ) );
                break;
            case 'expressoemprego.pt':
            case 'monster.com.hk':
            case 'dice.com':
                $setup .= __( '<p>Unfortunately this provider does not allow customizing the RSS feed but provides categorized feeds.</p><br/>', 'gofetch-wpjm' );
                $steps_li .= html( 'li', sprintf( __( 'Visit the provider RSS feeds page by clicking <a href="%2$s" target="_blank">here</a>.</p>', 'gofetch-wpjm' ), ++$steps, esc_url( $data['feed']['search_url'] ) ) );
                $steps_li .= html( 'li', sprintf( __( 'Click on any of the feeds from the RSS feed list.</p>', 'gofetch-wpjm' ), ++$steps ) );
                break;
            case 'jobs.ac.uk':
                $setup .= __( '<p>Unfortunately this provider does not allow customizing the RSS feed but provides categorized feeds.</p><br/>', 'gofetch-wpjm' );
                $steps_li .= html( 'li', sprintf( __( 'Visit the provider RSS feeds page by clicking <a href="%2$s" target="_blank">here</a>.</p>', 'gofetch-wpjm' ), ++$steps, esc_url( $data['feed']['base_url'] ) ) );
                $steps_li .= html( 'li', sprintf( __( 'Click on any of the feed categories from the list.</p>', 'gofetch-wpjm' ), ++$steps ) );
                $steps_li .= html( 'li', sprintf( __( 'You will be taken to a new page that contains all the available RSS feeds.</p>', 'gofetch-wpjm' ), ++$steps ) );
                $steps_li .= html( 'li', sprintf( __( 'Click on any of the feeds from the RSS feed list.</p>', 'gofetch-wpjm' ), ++$steps ) );
                break;
            case 'careerbuilder.com':
                $steps_li .= html( 'li', sprintf( __( 'Visit the provider RSS page by clicking <a href="%2$s" target="_blank">here</a>.</p>', 'gofetch-wpjm' ), ++$steps, esc_url( $data['feed']['search_url'] ) ) );
                $steps_li .= html( 'li', sprintf( __( 'Scroll to the bottom of the page, setup your RSS Feed using the RSS builder<strong>***</strong> and click <code>%2$s</code>.</p>', 'gofetch-wpjm' ), ++$steps, __( 'Get Jobs via RSS', 'gofetch-wpjm' ) ) );
                $steps_li .= html( 'li', sprintf( __( 'Optionally, you can just click your preferred RSS Feed from the pre-set list.</p>', 'gofetch-wpjm' ), ++$steps ) );
                $notes = sprintf( __( '<p><strong>*** Note:</strong> At the time these instructions were written, the RSS builder was generating RSS feeds with an invalid domain name. If you don\'t get a valid RSS feed URL similar to the example, please replace the invalid domain <code>%1$s</code> with this one <code>%2$s</code>.</p>', 'gofetch-wpjm' ), esc_url( 'http://www.careerbuilder.se/RTQ/rss20.aspx?' ), esc_url( 'http://www.careerbuilder.com/RTQ/rss20.aspx?geoip=false&' ) );
                break;
            case __( 'Other', 'gofetch-wpjm' ):
                $other_li = html( 'li', __( 'Visit any job site jobs search page, click \'View Source\' on your browser and search for the <code>RSS</code> word. In case you find matches look for any RSS related links near it.</p>', 'gofetch-wpjm' ) );
                $other_li .= html( 'li', __( 'Google directly for <code>my job site provider + RSS feeds</code></p>', 'gofetch-wpjm' ) );
                $other_li .= html( 'li', sprintf( __( 'Search for job sites directly from an RSS Reader like <a href="%1$s" target="_blank">Feedly</a>.</p>', 'gofetch-wpjm' ), 'https://feedly.com' ) );
                $setup .= html( 'p', sprintf( __( 'To use other job feed providers outside the providers list try the following:', 'gofetch-wpjm' ) ), html( 'ul', $other_li ) );
                $setup .= '<br/>' . html( 'p', __( 'In any case, most job sites usually offer a pre-set list of RSS feeds or a custom RSS builder based on a job search.', 'gofetch-wpjm' ) . ' ' . __( 'Just follow the instructions for similar providers and you should be ready to go.', 'gofetch-wpjm' ) );
                break;
            default:
                $steps_li .= html( 'li', sprintf( __( 'Visit the provider job search page by clicking <a href="%2$s" target="_blank">here</a>.</p>', 'gofetch-wpjm' ), ++$steps, esc_url( $data['feed']['search_url'] ) ) );
                $steps_li .= html( 'li', sprintf( __( 'Setup your jobs criteria and click the search button.</p>', 'gofetch-wpjm' ), ++$steps ) );
        }
        
        if ( $steps ) {
            if ( !$skip_copy_url ) {
                $steps_li .= html( 'li', sprintf( __( 'Copy the RSS feed URL from your browser address bar.</p>', 'gofetch-wpjm' ), ++$steps ) );
            }
            
            if ( !empty($data['feed']['sample']) ) {
                $samples = '';
                foreach ( (array) $data['feed']['sample'] as $sample ) {
                    $samples .= ( $samples ? ' ' . __( 'OR', 'gofetch-wpjm' ) . ' ' : '' );
                    $samples .= sprintf( '<a href="%1$s" target="_blank">%1$s</a>', $sample );
                }
                $steps_li .= html( 'li', sprintf( __( 'You should have an URL similar to this %2$s (depending on your criteria).</p>', 'gofetch-wpjm' ), ++$steps, $samples ) );
            }
            
            $steps_li .= html( 'li', sprintf( __( 'Paste your new RSS feed URL on the <code>URL</code> input field below.', 'gofetch-wpjm' ), ++$steps ) );
            $setup .= html( 'ol', $steps_li );
        }
        
        // Display single example.
        
        if ( !empty($data['feed']['example']) ) {
            $setup .= '<br/>';
            $setup .= html( 'p', html( 'strong', __( 'Example <small>(click to copy&paste)</small>', 'gofetch-wpjm' ) ) );
            $setup .= html( 'p class="provider-other-feeds"', sprintf( '<span class="dashicons dashicons-rss"></span> <a href="%1$s" class="provider-rss" target="_blank">%1$s</a></p>', esc_url( $data['feed']['example'] ) ) );
        }
        
        if ( !empty($data['feed']['default']) ) {
            $data['feed']['fixed'] = array(
                __( 'Latest Jobs', 'gofetch-wpjm' ) => $data['feed']['base_url'],
            );
        }
        // Display the fixed RSS feeds.
        
        if ( !empty($data['feed']['fixed']) ) {
            $setup .= '<br/>';
            
            if ( count( $data['feed']['fixed'] ) === 1 ) {
                
                if ( $steps ) {
                    $setup .= sprintf( __( '<p><strong>OR</strong> ... use the default RSS feed from the provider <small>(click to copy&paste)</small>:</p>', 'gofetch-wpjm' ), ++$steps );
                } else {
                    $setup .= sprintf( __( 'Click on your preferred RSS feed from the provider pre-set list:</p>', 'gofetch-wpjm' ), ++$steps );
                }
            
            } else {
                $setup .= sprintf( __( 'Choose your preferred RSS feed from the provider list <small>(click to copy&paste)</small>:</p>', 'gofetch-wpjm' ), ++$steps );
            }
            
            $setup .= '<br/>';
            foreach ( $data['feed']['fixed'] as $desc => $url ) {
                $setup .= html( 'p class="provider-other-feeds"', sprintf( '<span class="dashicons dashicons-rss"></span> <a href="%2$s" class="provider-rss" target="_blank">%1$s</a></p>', $desc, esc_url( $url ) ) );
            }
            $setup .= '<br/>';
        }
        
        // Display examples.
        
        if ( !empty($data['feed']['examples']) ) {
            $setup .= '<br/><br/>';
            $setup .= html( 'p', sprintf( __( 'Here are some quick ready to use examples <small>(click to copy&paste)</small>:', 'gofetch-wpjm' ), ++$steps ) );
            $setup .= '<br/>';
            foreach ( $data['feed']['examples'] as $desc => $url ) {
                $setup .= html( 'p class="provider-other-feeds"', sprintf( '<span class="dashicons dashicons-rss"></span> <a href="%2$s" class="provider-rss" target="_blank">%1$s</a></p>', $desc, esc_url( $url ) ) );
            }
            $setup .= '<br/>';
        }
        
        if ( !empty($notes) ) {
            $setup .= '<br/>' . $notes;
        }
        // Wrap the manual setup.
        $manual_setup = html( 'p', html( 'a', array(
            'href'         => '#',
            'class'        => 'provider-expand-feed-manual-setup',
            'data-child'   => 'feed-manual-setup',
            'data-default' => __( 'Manual Setup Instructions', 'gofetch-wpjm' ),
        ), __( 'Manual Setup Instructions', 'gofetch-wpjm' ) ) );
        $setup = $manual_setup . html( 'div class="feed-manual-setup"', $setup );
        $setup = $header . $multi_region . $data_info . $setup;
        // Wrap the builder, if available.
        if ( !empty($data['feed']['query_args']) ) {
            
            if ( !gfjwjm_fs()->is_not_paying() ) {
                $feed_builder = html( 'p', html( 'a', array(
                    'href'         => '#',
                    'class'        => 'provider-expand-feed-builder',
                    'data-child'   => 'feed-builder',
                    'data-default' => __( 'Use RSS Feed Builder', 'gofetch-wpjm' ),
                ), __( 'Use RSS Feed Builder', 'gofetch-wpjm' ) ) );
                $setup .= $feed_builder . html( 'div class="feed-builder"', self::output_rss_feed_builder() );
            } else {
                $setup .= html( 'p', html( 'a', array(
                    'href'  => '#',
                    'class' => '',
                ), __( 'Use RSS Feed Builder', 'gofetch-wpjm' ) ), ' ' . GoFetch_WPJM_Admin::limited_plan_warn() );
            }
        
        }
        return apply_filters( 'goft_wpjm_setup_instructions_for', $setup, $provider );
    }
    
    /**
     * Outputs a dropdown will all the available RSS providers.
     */
    public static function output_providers_dropdown( $atts = array() )
    {
        $choices = $choices_n = array();
        foreach ( self::get_providers() as $name => $data ) {
            $weight = ( !empty($data['weight']) ? $data['weight'] : 1 );
            $choices_n[$weight][] = $name;
        }
        krsort( $choices_n );
        // Retrieve choices sorted by provider weight.
        foreach ( $choices_n as $providers ) {
            foreach ( $providers as $provider ) {
                $data = self::get_providers( $provider );
                $defaults = array(
                    'description' => '',
                    'category'    => __( 'Other', 'gofetch-wpjm' ),
                );
                $data = wp_parse_args( $data, $defaults );
                $categories[$data['category']][$provider] = sprintf(
                    '%1$s %2$s %3$s',
                    $provider,
                    ( isset( $data['multi-region'] ) ? __( '<em>[Multi-Region]</em>', 'gofetch-wpjm' ) : '' ),
                    ( !empty($data['description']) ? sprintf( '<em>(%s)</em>', $data['description'] ) : '' )
                );
            }
        }
        ksort( $categories );
        $options = $optgroup = '';
        // Iterate through the categories.
        foreach ( $categories as $category => $providers ) {
            foreach ( $providers as $provider => $desc ) {
                $options .= html( 'option', array(
                    'value' => esc_attr( $provider ),
                ), $desc );
            }
            $optgroup .= html( 'optgroup', array(
                'label' => esc_attr( ucwords( $category ) ),
            ), $options );
            $options = '';
        }
        $optgroup = html( 'option', array(
            'value' => '',
        ), __( '--- Known Providers ---', 'gofetch-wpjm' ) ) . $optgroup;
        $defaults = array(
            'name' => 'goftj_rss_providers',
        );
        $atts = wp_parse_args( $atts, $defaults );
        return html( 'select', $atts, $optgroup );
    }
    
    public static function output_rss_feed_builder()
    {
        $output = '';
        return $output;
    }

}