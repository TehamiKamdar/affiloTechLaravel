<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{config("app.name")}} API Documentation</title>
<link rel="shortcut icon" href="https://app.theaffilo.com/assets/media/logos/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
                    body .content .php-example code { display: none; }
                    body .content .python-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://test.local";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-4.22.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-4.22.0.js") }}"></script>
<style>
    .tocify-wrapper {
    transition: left .3s ease-in-out;
    overflow-y: auto;
    overflow-x: hidden;
    position: fixed;
    z-index: 30;
    top: 0;
    left: 0;
    bottom: 0;
    width: 230px;
    background-color: #00a9da !important;
    font-size: 13px;
    font-weight: 700;
}
</style>
</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;,&quot;php&quot;,&quot;python&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    <a>
            <img src="{{ asset('publisherAssets/assets/affiloTechLogo.png') }}" alt="logo" class="logo" style="text-align:center;width:95%;margin:5px 0px"/>
</a>
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                            <button type="button" class="lang-button" data-language-name="php">php</button>
                                            <button type="button" class="lang-button" data-language-name="python">python</button>
                    </div>

    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating Requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-websites" class="tocify-header">
                <li class="tocify-item level-1" data-unique="websites">
                    <a href="#websites">Websites</a>
                </li>
                                    <ul id="tocify-subheader-websites" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="websites-GETapi-v1-websites">
                                <a href="#websites-GETapi-v1-websites">Get All Websites</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="websites-GETapi-v1-websites--id-">
                                <a href="#websites-GETapi-v1-websites--id-">Get Website By ID</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-advertisers" class="tocify-header">
                <li class="tocify-item level-1" data-unique="advertisers">
                    <a href="#advertisers">Advertisers</a>
                </li>
                                    <ul id="tocify-subheader-advertisers" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="advertisers-GETapi-v1-advertisers">
                                <a href="#advertisers-GETapi-v1-advertisers">Get All Advertisers</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="advertisers-GETapi-v1-advertisers--id-">
                                <a href="#advertisers-GETapi-v1-advertisers--id-">Get Advertiser By ID</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-offers" class="tocify-header">
                <li class="tocify-item level-1" data-unique="offers">
                    <a href="#offers">Offers</a>
                </li>
                                    <ul id="tocify-subheader-offers" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="offers-GETapi-v1-offers">
                                <a href="#offers-GETapi-v1-offers">Get All Offers</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="offers-GETapi-v1-offer--id-">
                                <a href="#offers-GETapi-v1-offer--id-">Get Offer By ID</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-generate-deep-tracking-links" class="tocify-header">
                <li class="tocify-item level-1" data-unique="generate-deep-tracking-links">
                    <a href="#generate-deep-tracking-links">Generate Deep / Tracking Links</a>
                </li>
                                    <ul id="tocify-subheader-generate-deep-tracking-links" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="generate-deep-tracking-links-POSTapi-v1-generate-link--id-">
                                <a href="#generate-deep-tracking-links-POSTapi-v1-generate-link--id-">Generate Tracking Links</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="generate-deep-tracking-links-POSTapi-v1-generate-deep-link--id-">
                                <a href="#generate-deep-tracking-links-POSTapi-v1-generate-deep-link--id-">Generate Deep Links</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-transactions" class="tocify-header">
                <li class="tocify-item level-1" data-unique="transactions">
                    <a href="#transactions">Transactions</a>
                </li>
                                    <ul id="tocify-subheader-transactions" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="transactions-GETapi-v1-transactions">
                                <a href="#transactions-GETapi-v1-transactions">Get All Transactions</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>














</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>https://app.{{config("app.name")}}.com/</code>
</aside>
<p>This documentation is intended to offer all the information necessary for you to effectively utilize our API.</p>
<aside>While scrolling, you'll encounter code examples for interacting with the API in various programming languages within the dark section on the right side (or integrated within the content on mobile). You can change the programming language displayed by using the tabs located at the top right (or through the navigation menu at the top left on mobile).</aside>

        <h1 id="authenticating-requests">Authenticating Requests</h1>
<p>To authenticate requests, include a <strong><code>token</code></strong> header with the value <strong><code>"your-token"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>You can retrieve your token by visiting our <b>{{config("app.name")}} Publisher dashboard</b>, accessing the settings, clicking on the <b>API Info section</b>, and then copying the token for use here. <br />  <br /> You can utilize the following APIs to make <b>10 requests</b> within a <b>one-minute time</b> frame.</p>

        <h1 id="websites">Websites</h1>

    <p>APIs for managing Websites</p>

                                <h2 id="websites-GETapi-v1-websites">Get All Websites</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>This endpoint is used to fetch all available websites from the database through authentication.</p>

<span id="example-requests-GETapi-v1-websites">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://test.local/api/v1/websites?page=1&amp;limit=20" \
    --header "token: 5VP6Z86fDkcab31Edhv4ega" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/websites"
);

const params = {
    "page": "1",
    "limit": "20",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "token": "5VP6Z86fDkcab31Edhv4ega",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://test.local/api/v1/websites',
    [
        'headers' =&gt; [
            'token' =&gt; '5VP6Z86fDkcab31Edhv4ega',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'page' =&gt; '1',
            'limit' =&gt; '20',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/websites'
params = {
  'page': '1',
  'limit': '20',
}
headers = {
  'token': '5VP6Z86fDkcab31Edhv4ega',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-websites">
            <blockquote>
            <p>Example Response (200, Get All Websites):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 90644632,
            &quot;name&quot;: &quot;****************&quot;,
            &quot;url&quot;: &quot;https://www.*********.au/&quot;,
            &quot;partner_types&quot;: [
                &quot;Coupons/Deals&quot;,
                &quot;Content/Blog&quot;
            ],
            &quot;categories&quot;: [
                &quot;News &amp; Blogging&quot;,
                &quot;Business&quot;,
                &quot;Shopping&quot;,
                &quot;Lifestyle&quot;
            ],
            &quot;status&quot;: &quot;Active&quot;,
            &quot;monthly_traffic&quot;: &quot;25000&quot;,
            &quot;monthly_page_views&quot;: &quot;55000&quot;,
            &quot;last_updated&quot;: &quot;08/14/2023&quot;
        }
    ],
    &quot;pagination&quot;: {
        &quot;total&quot;: 1,
        &quot;count&quot;: 1,
        &quot;per_page&quot;: 20,
        &quot;current_page&quot;: 1,
        &quot;total_pages&quot;: 1
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-websites" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-websites"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-websites"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-websites" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-websites">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-websites" data-method="GET"
      data-path="api/v1/websites"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-websites', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-websites"
                    onclick="tryItOut('GETapi-v1-websites');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-websites"
                    onclick="cancelTryOut('GETapi-v1-websites');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-websites"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/websites</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="GETapi-v1-websites"
                                  placeholder="5VP6Z86fDkcab31Edhv4ega"
                              data-component="header">
    <br>
<p>Example: <code>5VP6Z86fDkcab31Edhv4ega</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-websites"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-websites"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-websites"
                                  value="1"
                              data-component="query">
    <br>
<p>The page is optional, with a default value of 1 and a minimum requirement of 1. Must not be one of <code>0</code> Must be at least 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="limit"                data-endpoint="GETapi-v1-websites"
                                  value="20"
                              data-component="query">
    <br>
<p>The limit is optional, with a default value of 20 and a minimum requirement of 20 and maximum requirement of 500. Must be between 20 and 500. Example: <code>20</code></p>
            </div>
                </form>

                    <h2 id="websites-GETapi-v1-websites--id-">Get Website By ID</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-websites--id-">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://test.local/api/v1/websites/90644632" \
    --header "token: k4gv53fdZ8abDhVec6EPa16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/websites/90644632"
);

const headers = {
    "token": "k4gv53fdZ8abDhVec6EPa16",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://test.local/api/v1/websites/90644632',
    [
        'headers' =&gt; [
            'token' =&gt; 'k4gv53fdZ8abDhVec6EPa16',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/websites/90644632'
headers = {
  'token': 'k4gv53fdZ8abDhVec6EPa16',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-websites--id-">
            <blockquote>
            <p>Example Response (200, Get Website By ID):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 90644632,
            &quot;name&quot;: &quot;****************&quot;,
            &quot;url&quot;: &quot;https://www.*********.au/&quot;,
            &quot;partner_types&quot;: [
                &quot;Coupons/Deals&quot;,
                &quot;Content/Blog&quot;
            ],
            &quot;categories&quot;: [
                &quot;News &amp; Blogging&quot;,
                &quot;Business&quot;,
                &quot;Shopping&quot;,
                &quot;Lifestyle&quot;
            ],
            &quot;status&quot;: &quot;Active&quot;,
            &quot;monthly_traffic&quot;: &quot;25000&quot;,
            &quot;monthly_page_views&quot;: &quot;55000&quot;,
            &quot;last_updated&quot;: &quot;08/14/2023&quot;
        }
    ],
    &quot;pagination&quot;: {
        &quot;total&quot;: 1,
        &quot;count&quot;: 1,
        &quot;per_page&quot;: 20,
        &quot;current_page&quot;: 1,
        &quot;total_pages&quot;: 1
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-websites--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-websites--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-websites--id-"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-websites--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-websites--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-websites--id-" data-method="GET"
      data-path="api/v1/websites/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-websites--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-websites--id-"
                    onclick="tryItOut('GETapi-v1-websites--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-websites--id-"
                    onclick="cancelTryOut('GETapi-v1-websites--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-websites--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/websites/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="GETapi-v1-websites--id-"
                                  placeholder="k4gv53fdZ8abDhVec6EPa16"
                              data-component="header">
    <br>
<p>Example: <code>k4gv53fdZ8abDhVec6EPa16</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-websites--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-websites--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-websites--id-"
                                  placeholder="90644632"
                              data-component="url">
    <br>
<p>The ID of the website. Example: <code>90644632</code></p>
            </div>
                    </form>

                <h1 id="advertisers">Advertisers</h1>

    <p>APIs for managing Advertisers</p>

                                <h2 id="advertisers-GETapi-v1-advertisers">Get All Advertisers</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>This endpoint is used to fetch all available advertisers from the database through authentication.</p>

<span id="example-requests-GETapi-v1-advertisers">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://test.local/api/v1/advertisers?wid=90644632&amp;page=1&amp;limit=20" \
    --header "token: 6DPV3dbZvgkfac4e61h5E8a" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/advertisers"
);

const params = {
    "wid": "90644632",
    "page": "1",
    "limit": "20",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "token": "6DPV3dbZvgkfac4e61h5E8a",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://test.local/api/v1/advertisers',
    [
        'headers' =&gt; [
            'token' =&gt; '6DPV3dbZvgkfac4e61h5E8a',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'wid' =&gt; '90644632',
            'page' =&gt; '1',
            'limit' =&gt; '20',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/advertisers'
params = {
  'wid': '90644632',
  'page': '1',
  'limit': '20',
}
headers = {
  'token': '6DPV3dbZvgkfac4e61h5E8a',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-advertisers">
            <blockquote>
            <p>Example Response (200, Get All Advertisers):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 57601894,
            &quot;name&quot;: &quot;4 Corners Cannabis&quot;,
            &quot;url&quot;: &quot;http://4cornerscannabis.com/&quot;,
            &quot;logo&quot;: &quot;https://app.{{config("app.name")}}.com/storage/4cornerscannabis.gif&quot;,
            &quot;primary_regions&quot;: [
                &quot;US&quot;
            ],
            &quot;supported_regions&quot;: [
                &quot;CA&quot;
            ],
            &quot;currency_code&quot;: null,
            &quot;average_payment_time&quot;: null,
            &quot;epc&quot;: null,
            &quot;click_through_url&quot;: &quot;https://go.{{config("app.name")}}.com/track/{id1}/{id2}&quot;,
            &quot;click_through_short_url&quot;: &quot;https://go.{{config("app.name")}}.com/short/*******&quot;,
            &quot;categories&quot;: [
                &quot;Entertainment&quot;,
                &quot;Lifestyle&quot;
            ],
            &quot;validation_days&quot;: &quot;30&quot;,
            &quot;deeplink_enabled&quot;: 1,
            &quot;program_restrictions&quot;: [
                &quot;PPC Site&quot;,
                &quot;TM+ Bidding&quot;
            ],
            &quot;promotional_methods&quot;: [
                &quot;Social Media&quot;,
                &quot;Email Marketing&quot;,
                &quot;Blog Site&quot;,
                &quot;Content Site&quot;,
                &quot;Coupon Site&quot;
            ],
            &quot;description&quot;: &quot;&lt;div data-v-5a495e4e=\&quot;\&quot; id=\&quot;about-description\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 30px; border-bottom: 1px solid rgb(234, 235, 237); color: rgb(45, 62, 80); font-family: Muli, sans-serif;\&quot;&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; class=\&quot;about-description-section\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px;\&quot;&gt;&lt;h2 data-v-5a495e4e=\&quot;\&quot; class=\&quot;sub-title\&quot; style=\&quot;margin-right: 0px; margin-bottom: 10px; margin-left: 0px; padding: 0px; font-size: 16px; line-height: 24px;\&quot;&gt;Description&lt;/h2&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; class=\&quot;content\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; line-height: 18px;\&quot;&gt;4 Corners Cannabis was one of the nation&rsquo;s first CBD companies. We control our entire production process, from &ldquo;soil to oil,&rdquo; so we know exactly what goes into our products &mdash; and what doesn&rsquo;t. All of our CBD products are organic, food grade, non-GM&lt;/div&gt;&lt;/div&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; class=\&quot;about-description-section\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px;\&quot;&gt;&lt;h2 data-v-5a495e4e=\&quot;\&quot; class=\&quot;sub-title\&quot; style=\&quot;margin-right: 0px; margin-bottom: 10px; margin-left: 0px; padding: 0px; font-size: 16px; line-height: 24px;\&quot;&gt;Preferred business models&lt;/h2&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; class=\&quot;promo-methods-container\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;\&quot;&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px;\&quot;&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; class=\&quot;promo-method\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 3px 10px; background-color: rgb(242, 243, 244); font-size: 12px; line-height: 24px; border-radius: 5px;\&quot;&gt;Loyalty/Rewards&lt;/div&gt;&lt;/div&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px;\&quot;&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; class=\&quot;promo-method\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 3px 10px; background-color: rgb(242, 243, 244); font-size: 12px; line-height: 24px; border-radius: 5px;\&quot;&gt;Deal/Coupons&lt;/div&gt;&lt;/div&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px;\&quot;&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; class=\&quot;promo-method\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 3px 10px; background-color: rgb(242, 243, 244); font-size: 12px; line-height: 24px; border-radius: 5px;\&quot;&gt;Social Influencer&lt;/div&gt;&lt;/div&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px;\&quot;&gt;&lt;div data-v-5a495e4e=\&quot;\&quot; class=\&quot;promo-method\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 3px 10px; background-color: rgb(242, 243, 244); font-size: 12px; line-height: 24px; border-radius: 5px;\&quot;&gt;Content/Reviews&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; id=\&quot;brand-stats-outer-container\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 30px; display: flex; align-items: center; justify-content: center; color: rgb(45, 62, 80); font-family: Muli, sans-serif;\&quot;&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px 0px 0px auto; padding: 0px 20px 0px 0px; display: flex;\&quot;&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-content\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: flex; flex-direction: column; justify-content: space-between; min-height: 60px;\&quot;&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-label\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; color: rgb(129, 129, 129); font-size: 12px; line-height: 15px;\&quot;&gt;30 Day EPC&lt;/div&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-val\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-weight: 600; line-height: 24px;\&quot;&gt;NEW&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px 0px 0px auto; padding: 0px 20px 0px 0px; display: flex;\&quot;&gt;&lt;span data-v-e3ef0116=\&quot;\&quot; class=\&quot;separating-bar\&quot; style=\&quot;margin: 0px 20px 0px 0px; padding: 0px; height: 60px; border-left: 1px solid rgb(234, 235, 237);\&quot;&gt;&lt;/span&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-content\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: flex; flex-direction: column; justify-content: space-between; min-height: 60px;\&quot;&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-label\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; color: rgb(129, 129, 129); font-size: 12px; line-height: 15px;\&quot;&gt;Response&lt;/div&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-val\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-weight: 600; line-height: 24px;\&quot;&gt;19%&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px 0px 0px auto; padding: 0px 20px 0px 0px; display: flex;\&quot;&gt;&lt;span data-v-e3ef0116=\&quot;\&quot; class=\&quot;separating-bar\&quot; style=\&quot;margin: 0px 20px 0px 0px; padding: 0px; height: 60px; border-left: 1px solid rgb(234, 235, 237);\&quot;&gt;&lt;/span&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-content\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: flex; flex-direction: column; justify-content: space-between; min-height: 60px;\&quot;&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-label\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; color: rgb(129, 129, 129); font-size: 12px; line-height: 15px;\&quot;&gt;Acceptance&lt;/div&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-val\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-weight: 600; line-height: 24px;\&quot;&gt;100%&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px 0px 0px auto; padding: 0px 20px 0px 0px; display: flex;\&quot;&gt;&lt;span data-v-e3ef0116=\&quot;\&quot; class=\&quot;separating-bar\&quot; style=\&quot;margin: 0px 20px 0px 0px; padding: 0px; height: 60px; border-left: 1px solid rgb(234, 235, 237);\&quot;&gt;&lt;/span&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-content\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: flex; flex-direction: column; justify-content: space-between; min-height: 60px;\&quot;&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-label\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; color: rgb(129, 129, 129); font-size: 12px; line-height: 15px;\&quot;&gt;Funding Status&lt;/div&gt;&lt;div data-v-e3ef0116=\&quot;\&quot; class=\&quot;stat-val\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-weight: 600; line-height: 24px;\&quot;&gt;83%&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&quot;,
            &quot;short_description&quot;: &quot;4 Corners Cannabis was one of the nation&rsquo;s first CBD companies. We control our entire production process, from &ldquo;soil to oil,&rdquo; so we know exactly what goes into our products &mdash; and what doesn&rsquo;t. All of our CBD products are organic, food grade, non-GM&quot;,
            &quot;program_policies&quot;: &quot;&lt;h3 class=\&quot;ioHeader\&quot; style=\&quot;margin-top: 30px; margin-right: 0px; margin-left: 0px; padding: 0px; font-size: 32px; font-weight: 500; color: rgb(45, 62, 80); font-family: Roboto, sans-serif;\&quot;&gt;Mar 28, 2023 10:23 PDT - Onwards&lt;/h3&gt;&lt;div class=\&quot;tracker\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 40px; padding: 0px; border: 1px solid rgb(229, 231, 234); border-radius: 8px; color: rgb(45, 62, 80); font-family: Roboto, sans-serif; font-size: 12px;\&quot;&gt;&lt;div class=\&quot;trackerHeading\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 15px 25px; font-size: 20px; width: 1121px; display: table; background-color: rgb(144, 152, 161); color: rgb(255, 255, 255); border-top-left-radius: 8px; border-top-right-radius: 8px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-cell;\&quot;&gt;&lt;span class=\&quot;name\&quot; style=\&quot;margin: 0px; padding: 0px;\&quot;&gt;Online Sale:&lt;/span&gt;&amp;nbsp;&lt;span class=\&quot;payout\&quot; style=\&quot;margin: 0px; padding: 0px;\&quot;&gt;20%&lt;/span&gt;&amp;nbsp;&lt;span class=\&quot;currency\&quot; style=\&quot;margin: 0px; padding: 0px; text-transform: uppercase; color: rgb(199, 203, 208);\&quot;&gt;USD&lt;/span&gt;&lt;/div&gt;&lt;div class=\&quot;trackerMinimize\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-cell; text-align: right; font-size: 22px;\&quot;&gt;&lt;span class=\&quot;fa fa-minus\&quot; style=\&quot;margin: 0px; padding: 0px; font-weight: normal; font-stretch: normal; font-family: FontAwesome; font-size: inherit; transform: translate(0px, 0px); cursor: pointer; user-select: none;\&quot;&gt;&lt;/span&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;trackerContent\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 30px 25px;\&quot;&gt;&lt;div class=\&quot;heading1\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 20px;\&quot;&gt;Payout Details&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Default Payout&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;20% of order sale amount&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;heading1 additionalTopMargin\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 40px 0px 0px; padding: 0px; font-size: 20px;\&quot;&gt;Schedule&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Action Locking&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;Actions are locked 1 month(s) after end of the month they are tracked&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Invoicing&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;Actions are invoiced on the 3 of the month after they lock&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Payout Scheduling&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;Approved transactions are paid 15 day(s) after the end of the day they are invoiced&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;heading1 additionalTopMargin\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 40px 0px 0px; padding: 0px; font-size: 20px;\&quot;&gt;Qualified Referrals&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Credit Policy&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;Last Click&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Referral Window&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;Allow referrals from clicks within 30 day(s)&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;tracker\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 40px; padding: 0px; border: 1px solid rgb(229, 231, 234); border-radius: 8px; color: rgb(45, 62, 80); font-family: Roboto, sans-serif; font-size: 12px;\&quot;&gt;&lt;div class=\&quot;trackerHeading\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 15px 25px; font-size: 20px; width: 1121px; display: table; background-color: rgb(144, 152, 161); color: rgb(255, 255, 255); border-top-left-radius: 8px; border-top-right-radius: 8px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;generalTermsHeading\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 22px; display: table-cell;\&quot;&gt;General Terms&lt;/div&gt;&lt;div class=\&quot;trackerMinimize\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-cell; text-align: right; font-size: 22px;\&quot;&gt;&lt;span class=\&quot;fa fa-minus\&quot; style=\&quot;margin: 0px; padding: 0px; font-weight: normal; font-stretch: normal; font-family: FontAwesome; font-size: inherit; transform: translate(0px, 0px); cursor: pointer; user-select: none;\&quot;&gt;&lt;/span&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;trackerContent\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 30px 25px;\&quot;&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Currency&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;Financial transactions covered by this Contract will be processed in the USD currency. Currency exchanges will occur when you or your partner(s) have set a different default currency in account settings.&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Change Notification Period&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;The Contract can be changed or cancelled with 1 day(s) notification to the media partner.&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Reversal Policy&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;Reversal of performance advertising actions are decided by the Advertiser governed by a max reversal percentage of 100%&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;div class=\&quot;labelLine\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\&quot;&gt;&lt;div bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; display: table-row;\&quot;&gt;&lt;div class=\&quot;labelCell heading2text\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\&quot;&gt;Media Partner Tracking Pixel&lt;/div&gt;&lt;div class=\&quot;labelValue io_diff_line\&quot; bis_skin_checked=\&quot;1\&quot; style=\&quot;margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\&quot;&gt;Advertiser does NOT allow media partner to fire their tracking pixel when the consumer action is completed.&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&quot;,
            &quot;type&quot;: &quot;api&quot;,
            &quot;status&quot;: &quot;joined&quot;,
            &quot;commission&quot;: &quot;20%&quot;,
            &quot;goto_cookie_lifetime&quot;: null,
            &quot;exclusive&quot;: 0
        }
    ],
    &quot;pagination&quot;: {
        &quot;total&quot;: 7024,
        &quot;count&quot;: 20,
        &quot;per_page&quot;: 20,
        &quot;current_page&quot;: 1,
        &quot;total_pages&quot;: 352
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-advertisers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-advertisers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-advertisers"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-advertisers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-advertisers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-advertisers" data-method="GET"
      data-path="api/v1/advertisers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-advertisers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-advertisers"
                    onclick="tryItOut('GETapi-v1-advertisers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-advertisers"
                    onclick="cancelTryOut('GETapi-v1-advertisers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-advertisers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/advertisers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="GETapi-v1-advertisers"
                                  placeholder="6DPV3dbZvgkfac4e61h5E8a"
                              data-component="header">
    <br>
<p>Example: <code>6DPV3dbZvgkfac4e61h5E8a</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-advertisers"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-advertisers"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>wid</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="wid"                data-endpoint="GETapi-v1-advertisers"
                                  placeholder="90644632"
                              data-component="query">
    <br>
<p>The website ID is required to filter the data. Example: <code>90644632</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-advertisers"
                                  value="1"
                              data-component="query">
    <br>
<p>The page is optional, with a default value of 1 and a minimum requirement of 1. Must not be one of <code>0</code> Must be at least 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="limit"                data-endpoint="GETapi-v1-advertisers"
                                  value="20"
                              data-component="query">
    <br>
<p>The limit is optional, with a default value of 20 and a minimum requirement of 20 and maximum requirement of 500. Must be between 20 and 500. Example: <code>20</code></p>
            </div>
                </form>

                    <h2 id="advertisers-GETapi-v1-advertisers--id-">Get Advertiser By ID</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>This endpoint is used to fetch get advertiser by id available in the database.</p>

<span id="example-requests-GETapi-v1-advertisers--id-">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://test.local/api/v1/advertisers/86049368?wid=90644632" \
    --header "token: ZP5EgevkVbDc8hf1346ada6" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/advertisers/86049368"
);

const params = {
    "wid": "90644632",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "token": "ZP5EgevkVbDc8hf1346ada6",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://test.local/api/v1/advertisers/86049368',
    [
        'headers' =&gt; [
            'token' =&gt; 'ZP5EgevkVbDc8hf1346ada6',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'wid' =&gt; '90644632',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/advertisers/86049368'
params = {
  'wid': '90644632',
}
headers = {
  'token': 'ZP5EgevkVbDc8hf1346ada6',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-advertisers--id-">
            <blockquote>
            <p>Example Response (200, Get Advertiser By ID):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
&quot;data&quot;: {
&quot;id&quot;: 86049368,
&quot;name&quot;: &quot; ESM-Computer&quot;,
&quot;url&quot;: &quot;https://www.esm-computer.de/&quot;,
&quot;logo&quot;: &quot;https://app.{{config("app.name")}}.com/&quot;,
&quot;primary_regions&quot;: [
&quot;DE&quot;
],
&quot;supported_regions&quot;: [],
&quot;currency_code&quot;: &quot;EUR&quot;,
&quot;average_payment_time&quot;: &quot;14&quot;,
&quot;epc&quot;: &quot;0&quot;,
&quot;click_through_url&quot;: null,
&quot;click_through_short_url&quot;: null,
&quot;validation_days&quot;: null,
&quot;status&quot;: &quot;Not Joined&quot;,
&quot;commission&quot;: &quot;7%&quot;,
&quot;goto_cookie_lifetime&quot;: null,
&quot;exclusive&quot;: 0,
&quot;deeplink_enabled&quot;: 1,
&quot;categories&quot;: null,
&quot;program_restrictions&quot;: [
&quot;PPC Site&quot;,
&quot;TM+ Bidding&quot;
],
&quot;promotional_methods&quot;: [
&quot;Social Media&quot;,
&quot;Email Marketing&quot;,
&quot;Blog Site&quot;,
&quot;Content Site&quot;,
&quot;Coupon Site&quot;
],
&quot;description&quot;: null,
&quot;short_description&quot;: null,
&quot;program_policies&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-advertisers--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-advertisers--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-advertisers--id-"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-advertisers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-advertisers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-advertisers--id-" data-method="GET"
      data-path="api/v1/advertisers/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-advertisers--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-advertisers--id-"
                    onclick="tryItOut('GETapi-v1-advertisers--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-advertisers--id-"
                    onclick="cancelTryOut('GETapi-v1-advertisers--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-advertisers--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/advertisers/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="GETapi-v1-advertisers--id-"
                                  placeholder="ZP5EgevkVbDc8hf1346ada6"
                              data-component="header">
    <br>
<p>Example: <code>ZP5EgevkVbDc8hf1346ada6</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-advertisers--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-advertisers--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-advertisers--id-"
                                  placeholder="86049368"
                              data-component="url">
    <br>
<p>The ID of the advertiser. Example: <code>86049368</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>wid</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="wid"                data-endpoint="GETapi-v1-advertisers--id-"
                                  placeholder="90644632"
                              data-component="query">
    <br>
<p>The website ID is required to filter the data based on websites. Example: <code>90644632</code></p>
            </div>
                </form>

                <h1 id="offers">Offers</h1>

    <p>APIs for managing Advertisers</p>

                                <h2 id="offers-GETapi-v1-offers">Get All Offers</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>This endpoint is used to fetch all available offers from the database through authentication.</p>

<span id="example-requests-GETapi-v1-offers">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://test.local/api/v1/offers?wid=90644632&amp;page=1&amp;limit=20" \
    --header "token: dh18ZvkaaDEf664ce3gPb5V" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/offers"
);

const params = {
    "wid": "90644632",
    "page": "1",
    "limit": "20",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "token": "dh18ZvkaaDEf664ce3gPb5V",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://test.local/api/v1/offers',
    [
        'headers' =&gt; [
            'token' =&gt; 'dh18ZvkaaDEf664ce3gPb5V',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'wid' =&gt; '90644632',
            'page' =&gt; '1',
            'limit' =&gt; '20',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/offers'
params = {
  'wid': '90644632',
  'page': '1',
  'limit': '20',
}
headers = {
  'token': 'dh18ZvkaaDEf664ce3gPb5V',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-offers">
            <blockquote>
            <p>Example Response (200, Get All Offers):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
 {
&quot;data&quot;: [
{
&quot;id&quot;: &quot;cf8bee9a-14ee-4eca-99bb-1979893f5d81&quot;,
&quot;name&quot;: &quot;Co-Found Private Link&quot;,
&quot;advertiser_id&quot;: 18393049,
&quot;advertiser_name&quot;: &quot;99designs by Vista&quot;,
&quot;advertiser_url&quot;: &quot;https://99designs.com&quot;,
&quot;url_tracking&quot;: &quot;http://track.test.local/track/d5592ba4-3247-48fd-bc13-9b124acb889f/d5b45a0d-5604-4872-b3d1-8dbf315b586c/cf8bee9a-14ee-4eca-99bb-1979893f5d81&quot;,
&quot;advertiser_status&quot;: &quot;active&quot;,
&quot;type&quot;: &quot;promotion&quot;,
&quot;description&quot;: &quot;&quot;,
&quot;terms&quot;: null,
&quot;start_date&quot;: &quot;11/08/2023&quot;,
&quot;end_date&quot;: &quot;11/08/2023&quot;,
&quot;code&quot;: &quot;No code required&quot;,
&quot;exclusive&quot;: 0,
&quot;regions&quot;: null,
&quot;categories&quot;: null
},
{............},
],
&quot;pagination&quot;: {
&quot;total&quot;: 832,
&quot;count&quot;: 50,
&quot;per_page&quot;: 50,
&quot;current_page&quot;: 1,
&quot;total_pages&quot;: 17
}
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-offers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-offers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-offers"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-offers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-offers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-offers" data-method="GET"
      data-path="api/v1/offers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-offers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-offers"
                    onclick="tryItOut('GETapi-v1-offers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-offers"
                    onclick="cancelTryOut('GETapi-v1-offers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-offers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/offers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="GETapi-v1-offers"
                                  placeholder="dh18ZvkaaDEf664ce3gPb5V"
                              data-component="header">
    <br>
<p>Example: <code>dh18ZvkaaDEf664ce3gPb5V</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-offers"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-offers"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>wid</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="wid"                data-endpoint="GETapi-v1-offers"
                                  placeholder="90644632"
                              data-component="query">
    <br>
<p>The website ID is required to filter the data. Example: <code>90644632</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-offers"
                                  value="1"
                              data-component="query">
    <br>
<p>The page is optional, with a default value of 1 and a minimum requirement of 1. Must not be one of <code>0</code> Must be at least 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="limit"                data-endpoint="GETapi-v1-offers"
                                  value="20"
                              data-component="query">
    <br>
<p>The limit is optional, with a default value of 20 and a minimum requirement of 20 and maximum requirement of 500. Must be between 20 and 500. Example: <code>20</code></p>
            </div>
                </form>

                    <h2 id="offers-GETapi-v1-offer--id-">Get Offer By ID</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>This endpoint is used to fetch get offer by id available in the database.</p>

<span id="example-requests-GETapi-v1-offer--id-">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://test.local/api/v1/offer/0004f9f0-4224-4228-8e85-5a58683c0862?wid=90644632" \
    --header "token: Z5Dd3f4bVavcak6h681ePgE" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/offer/0004f9f0-4224-4228-8e85-5a58683c0862"
);

const params = {
    "wid": "90644632",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "token": "Z5Dd3f4bVavcak6h681ePgE",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://test.local/api/v1/offer/0004f9f0-4224-4228-8e85-5a58683c0862',
    [
        'headers' =&gt; [
            'token' =&gt; 'Z5Dd3f4bVavcak6h681ePgE',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'wid' =&gt; '90644632',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/offer/0004f9f0-4224-4228-8e85-5a58683c0862'
params = {
  'wid': '90644632',
}
headers = {
  'token': 'Z5Dd3f4bVavcak6h681ePgE',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-offer--id-">
            <blockquote>
            <p>Example Response (200, Get Advertiser By ID):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;0004f9f0-4224-4228-8e85-5a58683c0862&quot;,
        &quot;name&quot;: &quot;The Kobo Elipsa Pack has all you need to mark up your eBooks and documents, and create your own notebooks. Includes a Kobo Stylus and SleepCover!&quot;,
        &quot;advertiser_id&quot;: 62947499,
        &quot;advertiser_name&quot;: &quot;Rakuten Kobo Australia&quot;,
        &quot;advertiser_url&quot;: &quot;https://www.kobo.com/au/en&quot;,
        &quot;url_tracking&quot;: &quot;http://track.test.local/track/f5ca51a4-e843-4005-a96f-db88ddfc9916/d5b45a0d-5604-4872-b3d1-8dbf315b586c/0004f9f0-4224-4228-8e85-5a58683c0862&quot;,
        &quot;advertiser_status&quot;: &quot;active&quot;,
        &quot;type&quot;: &quot;promotion&quot;,
        &quot;description&quot;: null,
        &quot;terms&quot;: null,
        &quot;start_date&quot;: &quot;14/04/2022&quot;,
        &quot;end_date&quot;: &quot;14/04/2028&quot;,
        &quot;code&quot;: &quot;No code required&quot;,
        &quot;exclusive&quot;: 0,
        &quot;regions&quot;: null,
        &quot;categories&quot;: null
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-offer--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-offer--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-offer--id-"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-offer--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-offer--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-offer--id-" data-method="GET"
      data-path="api/v1/offer/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-offer--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-offer--id-"
                    onclick="tryItOut('GETapi-v1-offer--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-offer--id-"
                    onclick="cancelTryOut('GETapi-v1-offer--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-offer--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/offer/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="GETapi-v1-offer--id-"
                                  placeholder="Z5Dd3f4bVavcak6h681ePgE"
                              data-component="header">
    <br>
<p>Example: <code>Z5Dd3f4bVavcak6h681ePgE</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-offer--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-offer--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-offer--id-"
                                  placeholder="0004f9f0-4224-4228-8e85-5a58683c0862"
                              data-component="url">
    <br>
<p>The ID of the offer. Example: <code>0004f9f0-4224-4228-8e85-5a58683c0862</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>wid</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="wid"                data-endpoint="GETapi-v1-offer--id-"
                                  placeholder="90644632"
                              data-component="query">
    <br>
<p>The website ID is required to filter the data based on websites. Example: <code>90644632</code></p>
            </div>
                </form>

                <h1 id="generate-deep-tracking-links">Generate Deep / Tracking Links</h1>

    <p>APIs for managing Generate Deep / Tracking Links</p>

                                <h2 id="generate-deep-tracking-links-POSTapi-v1-generate-link--id-">Generate Tracking Links</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>This endpoint is used to generate tracking links by id available in the database.</p>

<span id="example-requests-POSTapi-v1-generate-link--id-">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://test.local/api/v1/generate-link/86049368" \
    --header "token: baa1k4P8dvhEZg6Dfc36e5V" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"wid\": 45174194,
    \"sub_id\": \"980b6fb4-5bd8-11ee-8c99-0242ac120002\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/generate-link/86049368"
);

const headers = {
    "token": "baa1k4P8dvhEZg6Dfc36e5V",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "wid": 45174194,
    "sub_id": "980b6fb4-5bd8-11ee-8c99-0242ac120002"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;post(
    'http://test.local/api/v1/generate-link/86049368',
    [
        'headers' =&gt; [
            'token' =&gt; 'baa1k4P8dvhEZg6Dfc36e5V',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'wid' =&gt; 45174194.0,
            'sub_id' =&gt; '980b6fb4-5bd8-11ee-8c99-0242ac120002',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/generate-link/86049368'
payload = {
    "wid": 45174194,
    "sub_id": "980b6fb4-5bd8-11ee-8c99-0242ac120002"
}
headers = {
  'token': 'baa1k4P8dvhEZg6Dfc36e5V',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-generate-link--id-">
            <blockquote>
            <p>Example Response (200, Generate Tracking Links):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
&quot;success&quot;: true,
&quot;name&quot;: &quot;99designs by Vista&quot;,
&quot;tracking_url&quot;: &quot;https://go.{{config("app.name")}}.com/short/NaxDGFkv&quot;,
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-generate-link--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-generate-link--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-generate-link--id-"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-generate-link--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-generate-link--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-generate-link--id-" data-method="POST"
      data-path="api/v1/generate-link/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-generate-link--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-generate-link--id-"
                    onclick="tryItOut('POSTapi-v1-generate-link--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-generate-link--id-"
                    onclick="cancelTryOut('POSTapi-v1-generate-link--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-generate-link--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/generate-link/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="POSTapi-v1-generate-link--id-"
                                  placeholder="baa1k4P8dvhEZg6Dfc36e5V"
                              data-component="header">
    <br>
<p>Example: <code>baa1k4P8dvhEZg6Dfc36e5V</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-generate-link--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-generate-link--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-v1-generate-link--id-"
                                  placeholder="86049368"
                              data-component="url">
    <br>
<p>The ID of the advertiser. Example: <code>86049368</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>wid</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="wid"                data-endpoint="POSTapi-v1-generate-link--id-"
                                  placeholder="45174194"
                              data-component="body">
    <br>
<p>The website ID is required to filter the data based on websites. Example: <code>45174194</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sub_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sub_id"                data-endpoint="POSTapi-v1-generate-link--id-"
                                  placeholder="980b6fb4-5bd8-11ee-8c99-0242ac120002"
                              data-component="body">
    <br>
<p>The Sub ID is required to filter the data based on Sub ID. Must not be greater than 40 characters. Example: <code>980b6fb4-5bd8-11ee-8c99-0242ac120002</code></p>
        </div>
        </form>

                    <h2 id="generate-deep-tracking-links-POSTapi-v1-generate-deep-link--id-">Generate Deep Links</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>This endpoint is used to generate deep links by id available in the database.</p>

<span id="example-requests-POSTapi-v1-generate-deep-link--id-">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://test.local/api/v1/generate-deep-link/86049368" \
    --header "token: vc13EagdVhb5fDa466keZP8" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"wid\": 45174194,
    \"sub_id\": \"980b6fb4-5bd8-11ee-8c99-0242ac120002\",
    \"landing_url\": \"https:\\/\\/www.esm-computer.de\\/\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/generate-deep-link/86049368"
);

const headers = {
    "token": "vc13EagdVhb5fDa466keZP8",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "wid": 45174194,
    "sub_id": "980b6fb4-5bd8-11ee-8c99-0242ac120002",
    "landing_url": "https:\/\/www.esm-computer.de\/"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;post(
    'http://test.local/api/v1/generate-deep-link/86049368',
    [
        'headers' =&gt; [
            'token' =&gt; 'vc13EagdVhb5fDa466keZP8',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'wid' =&gt; 45174194.0,
            'sub_id' =&gt; '980b6fb4-5bd8-11ee-8c99-0242ac120002',
            'landing_url' =&gt; 'https://www.esm-computer.de/',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/generate-deep-link/86049368'
payload = {
    "wid": 45174194,
    "sub_id": "980b6fb4-5bd8-11ee-8c99-0242ac120002",
    "landing_url": "https:\/\/www.esm-computer.de\/"
}
headers = {
  'token': 'vc13EagdVhb5fDa466keZP8',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-generate-deep-link--id-">
            <blockquote>
            <p>Example Response (200, Generate Deep Links):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;name&quot;: &quot;99designs by Vista&quot;,
    &quot;deeplink_enabled&quot;: true,
    &quot;deeplink_link_url&quot;: &quot;https://go.{{config("app.name")}}.com/deeplink/77Gdj4gT&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-generate-deep-link--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-generate-deep-link--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-generate-deep-link--id-"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-generate-deep-link--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-generate-deep-link--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-generate-deep-link--id-" data-method="POST"
      data-path="api/v1/generate-deep-link/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-generate-deep-link--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-generate-deep-link--id-"
                    onclick="tryItOut('POSTapi-v1-generate-deep-link--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-generate-deep-link--id-"
                    onclick="cancelTryOut('POSTapi-v1-generate-deep-link--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-generate-deep-link--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/generate-deep-link/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="POSTapi-v1-generate-deep-link--id-"
                                  placeholder="vc13EagdVhb5fDa466keZP8"
                              data-component="header">
    <br>
<p>Example: <code>vc13EagdVhb5fDa466keZP8</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-generate-deep-link--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-generate-deep-link--id-"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-v1-generate-deep-link--id-"
                                  placeholder="86049368"
                              data-component="url">
    <br>
<p>The ID of the advertiser. Example: <code>86049368</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>wid</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="wid"                data-endpoint="POSTapi-v1-generate-deep-link--id-"
                                  placeholder="45174194"
                              data-component="body">
    <br>
<p>The website ID is required to filter the data based on websites. Example: <code>45174194</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sub_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sub_id"                data-endpoint="POSTapi-v1-generate-deep-link--id-"
                                  placeholder="980b6fb4-5bd8-11ee-8c99-0242ac120002"
                              data-component="body">
    <br>
<p>The Sub ID is required to filter the data based on Sub ID. Must not be greater than 40 characters. Example: <code>980b6fb4-5bd8-11ee-8c99-0242ac120002</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>landing_url</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="landing_url"                data-endpoint="POSTapi-v1-generate-deep-link--id-"
                                  placeholder="https://www.esm-computer.de/"
                              data-component="body">
    <br>
<p>It must be a valid URL and should not exceed 191 characters in length. Must be a valid URL. Must not be greater than 500 characters. Example: <code>https://www.esm-computer.de/</code></p>
        </div>
        </form>

                <h1 id="transactions">Transactions</h1>

    <p>APIs for managing Transactions</p>

                                <h2 id="transactions-GETapi-v1-transactions">Get All Transactions</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>This endpoint is used to fetch all available transactions from the database through authentication.</p>

<span id="example-requests-GETapi-v1-transactions">
<blockquote>Example Requests:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://test.local/api/v1/transactions?wid=90644632&amp;page=1&amp;limit=20" \
    --header "token: Dgev4a35fc6dP6k81abEVZh" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://test.local/api/v1/transactions"
);

const params = {
    "wid": "90644632",
    "page": "1",
    "limit": "20",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "token": "Dgev4a35fc6dP6k81abEVZh",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'http://test.local/api/v1/transactions',
    [
        'headers' =&gt; [
            'token' =&gt; 'Dgev4a35fc6dP6k81abEVZh',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'wid' =&gt; '90644632',
            'page' =&gt; '1',
            'limit' =&gt; '20',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://test.local/api/v1/transactions'
params = {
  'wid': '90644632',
  'page': '1',
  'limit': '20',
}
headers = {
  'token': 'Dgev4a35fc6dP6k81abEVZh',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-transactions">
            <blockquote>
            <p>Example Response (200, Get All Transactions):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
&quot;data&quot;: [
{
&quot;transaction_id&quot;: &quot;10789.5686.1207059&quot;,
&quot;advertiser_id&quot;: 94731544,
&quot;advertiser_name&quot;: &quot;KitchenAid Australia&quot;,
&quot;publisher_id&quot;: 14447196,
&quot;publisher_name&quot;: &quot;Zain ul Abedin&quot;,
&quot;website_id&quot;: 90644632,
&quot;website_name&quot;: &quot;*******.com.au/&quot;,
&quot;payment_id&quot;: null,
&quot;commission_status&quot;: &quot;Pending&quot;,
&quot;commission_amount&quot;: &quot;5.03&quot;,
&quot;commission_amount_currency&quot;: &quot;USD&quot;,
&quot;sale_amount&quot;: &quot;167.77&quot;,
&quot;sale_amount_currency&quot;: &quot;USD&quot;,
&quot;transaction_date&quot;: &quot;2023-07-27 23:41:30&quot;,
&quot;commission_type&quot;: &quot;CLICK_COOKIE&quot;,
&quot;url&quot;: null,
&quot;sub_id&quot;: &quot;aaf265c2-7385-4a3a-9d75-991f3e284064&quot;,
},
{...............},
]
 &quot;pagination&quot;: {
 &quot;total&quot;: 832,
 &quot;count&quot;: 50,
 &quot;per_page&quot;: 50,
 &quot;current_page&quot;: 1,
 &quot;total_pages&quot;: 17
 }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-transactions" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-transactions"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-transactions"
      data-empty-response-text="<Empty Response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-transactions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-transactions">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-transactions" data-method="GET"
      data-path="api/v1/transactions"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-transactions', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-transactions"
                    onclick="tryItOut('GETapi-v1-transactions');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-transactions"
                    onclick="cancelTryOut('GETapi-v1-transactions');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-transactions"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/transactions</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token" class="auth-value"               data-endpoint="GETapi-v1-transactions"
                                  placeholder="Dgev4a35fc6dP6k81abEVZh"
                              data-component="header">
    <br>
<p>Example: <code>Dgev4a35fc6dP6k81abEVZh</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-transactions"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-transactions"
                                  value="application/json"
                              data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>wid</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="wid"                data-endpoint="GETapi-v1-transactions"
                                  placeholder="90644632"
                              data-component="query">
    <br>
<p>The website ID is required to filter the data. Example: <code>90644632</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-transactions"
                                  value="1"
                              data-component="query">
    <br>
<p>The page is optional, with a default value of 1 and a minimum requirement of 1. Must not be one of <code>0</code> Must be at least 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="limit"                data-endpoint="GETapi-v1-transactions"
                                  value="20"
                              data-component="query">
    <br>
<p>The limit is optional, with a default value of 20 and a minimum requirement of 20 and maximum requirement of 500. Must be between 20 and 500. Example: <code>20</code></p>
            </div>
                </form>




    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                                        <button type="button" class="lang-button" data-language-name="php">php</button>
                                                        <button type="button" class="lang-button" data-language-name="python">python</button>
                            </div>
            </div>
</div>
</body>
</html>
