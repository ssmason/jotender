=== Go Fetch Jobs (for WP Job Manager) ===
Author: SebeT
Contributors: SebeT, freemius
Tags: import, rss, feed, jobs, WP Job Manager, automated imports, scheduled imports, Jobify, Babysitter, Jobseek, WorkScout, Prime Jobs, JobHaus, JobFinder
Requires at least: 3.5
Tested up to: 4.4.2
Stable tag: 1.1.2

Instantly populate your WP Job Manager database using RSS job feeds from the most popular job sites.

== Description ==

[DEMO site](http://bruno-carreco.com/wpuno/demo/wp/go-fetch-jobs-wp-job-manager/?demo=1)

Instantly populate your [WP Job Manager](https://wpjobmanager.com/) site with jobs from the most popular job sites and/or job aggregators. This handy plugin fetches jobs from RSS feeds and imports them to your jobs database. Pick your favorite job site, look for the jobs RSS feed, paste it directly to *Go Fetch Jobs* and instantly get fresh jobs in your database!

To help you quickly getting fresh jobs from external sites, *Go Fetch Jobs* comes bundled with ready to use RSS feeds and detailed instructions on how to setup RSS feeds for job sites that provide them, including [jobs.theguardian.com](jobs.theguardian.com).

Easily categorize bulk job imports with job categories, job types, default custom fields values and expiry dates.

Besides the usual *Title* + *Description* + *Date* usually provided by RSS feeds, *Go Fetch Jobs* can also (optionally) try to extract and auto fill job companies logos if that information is provided by the RSS feed.

It also comes with the ability to save import rules as templates so you can later recycle/re-use them for new imports.

> #### Additional features, exclusive to premium plans include:
> * Ready to use RSS feeds from popular job sites including: [indeed.com](indeed.com), [careerjet.com](careerjet.com) and [craigslist](cragislist.org)
> * Custom RSS builder for select providers that allows creating custom RSS feeds with specific keywords/location, without leaving your site
> * Extract and auto-fill job company names and locations on select providers
> * Automated scheduled imports

Keep reading for additional details...

**Importing**

The import process will import the following information from each RSS feed:

Base Fields:

* Job Title
* Job Description
* Job Published Date
* Link (external link)

Other Fields (not always available): (***)

* Job Company
* Job Location
* Job Company Logo

Additionally you’ll be able to optionally add more details to each bulk of jobs being imported: (***)

* Set taxonomies (job type and category)
* Set as featured
* Set default values for custom fields
* Set expiration date
* Set jobs author
* Specify a max limit of jobs to import
* Specify a date interval

**Templates**

There’s plenty of options to control and customize your job imports and you can even save your import settings as templates. These templates, beside simplifying future imports, can be assigned to automatic imports, available through schedules. You can create unlimited schedules to automatically and regularly import jobs. Just specify an existing template, the schedule recurrence and watch your site being refreshed with new jobs on a regular basis.

**Seamless Integration**

Each imported job seamlessly integrates with *WP Job Manager* native jobs with custom fields like *Job Company*, *Job Location* and *Job Logo* being automatically filled if recognized and available in the feed.

**Crediting Providers**

Since some RSS providers usually require crediting their jobs when used on external sources, *Go Fetch Jobs* will automatically extract and fill that information for your. It will be displayed on jobs individual pages, below the job description.

These are the fields you can use to identify the jobs providers/sources:

**Monetize on External Links** (***)

If you have an Affiliate Id, publisher ID, etc, from any of your job providers, *Go Fetch Jobs* also provides a *Parameters* field where you can specify a list of 'key/value’ parameters (e.g; pid=123, publisher=123) to add to the external URL (e.g: www.external-site.com/rss/jobs/?q=wordpress&pid=123). These arguments will be automatically applied to each job external links allowing you to monetize on clicks, if applicable.

**Filter Jobs**

For further control over the jobs being imported you can filter jobs by a date interval (***) and limit the number of jobs to import.

**Schedules** (***)

Schedules are one of the most powerful features in **Go Fetch Jobs**. They allow you to automatically import jobs on a regular basis (daily, weekly or monthly) using an existing import template. On each schedule you can further limit the jobs to import and assign them to a particular job lister.

Schedules are a great way to keep your job site fresh with new jobs!


**(***)** *Features not available on the Free version.*


> Here's a detailed list of the features per plan:
> 
> ####Free
> * Import Jobs from any Valid RSS Feed
> * Seamless Integration with WPJM Jobs
> * Assign Job Types
> * Assign Job Categories
> * Assign Values to WPJM Custom Fields
> * Assign Job Expiry Date
> * Save Import Rules as Templates
> * Tips on Finding Job Sites with RSS feeds
> * Auto Assign Company Logos on Select Providers
> * 6 Ready to Use RSS Feeds, including [jobs.theguardian.com](jobs.theguardian.com), with Detailed Setup Instructions
>
> *Limited Support*

> ####Pro
> * All Free Features +
>   * 15+ Ready to Use RSS Feeds from Popular Job Sites, including [careerbuilder.com](http://www.careerbuilder.com/)
>   * Integrated Custom RSS Feed Builder for Select Providers
>   * Location and Company Meta Fields Auto Fill For Select Providers
>   * Feature Imported Jobs
>   * Optional URL Parameters For External Links
>
> *Priority Support*

> #### Pro+
> * All Pro Features +
>   * Schedule Imports
>   * 25+ Ready to Use RSS Feeds from Popular Job Sites, including [indeed.com](http://www.indeed.com/)
>   * Limit Job Imports by Date
>   * Feature Job Imports
>
> *Priority Support*

**You can upgrade to any plan right directly from the plugin.**

== Installation ==

1. Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from the Plugins page.
2. A new Menu named 'Go Fetch Jobs' will be available on the left sidebar.

== Screenshots ==

1. Existing RSS Providers List
2. Load Saved Import Templates
3. RSS Feed Setup Detailed Instructions
4. Custom RSS Feed Builder
5. Fetch Job Companies Logos
6. Set Job Providers Information / Optional URL Parameters
7. Set Job Details for Imported Jobs
8. Filters / Save Templates
9. Job Listings for Imported Jobs (Frontend)
10. Single Job Page for Imported Jobs (Frontend)

== Changelog ==

= 1.1.2 =
* Fixed: Invalid 'Create Template' link under schedule pages
* Fixed: Use 'the_content' filter for feeds description (fixes HTML entities not properly converted on some RSS feeds)
* Fixed: Strip tags in titles (fixes HTML tags showing in post titles on some RSS feeds)
* Changes: Added Option to force load a feed (for feeds that although valid may fail to load)

= 1.1.1 =
* Fixed: feeds not loading in Firefox

= 1.1 =
* Fixed: feeds from known providers not returning any data


== Upgrade Notice ==
This is the first stable version.
