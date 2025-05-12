<?php
function generatePostShareLinks($title, $description, $imageURL, $pageURL, $faviconURL)
{
    $encodedTitle = urlencode($title);
    $encodedDescription = urlencode($description);
    $encodedImageURL = urlencode($imageURL);
    $encodedPageURL = urlencode($pageURL);
    $encodedFaviconURL = urlencode($faviconURL);

    // Facebook
    $facebookLink = "https://www.facebook.com/sharer/sharer.php?u={$encodedPageURL}";

    // Instagram (Note: Instagram does not provide a direct share link like other platforms)
    $instagramLink = "https://www.instagram.com/";

    // WhatsApp
    $whatsappLink = "https://wa.me/?text={$encodedTitle}%0A{$encodedPageURL}";

    // LinkedIn
    $linkedinLink = "https://www.linkedin.com/shareArticle?url={$encodedPageURL}&title={$encodedTitle}&summary={$encodedDescription}&source={$encodedFaviconURL}";

    // Telegram
    $telegramLink = "https://t.me/share/url?url={$encodedPageURL}&text={$encodedTitle}";

    // Twitter
    $twitterLink = "https://twitter.com/intent/tweet?url={$encodedPageURL}&text={$encodedTitle}";

    $social_share = ["facebookUrl" => $facebookLink, "instagramUrl" => $instagramLink, "whatsappUrl" => $whatsappLink, "linkedinUrl" => $linkedinLink, "telegramUrl" => $telegramLink, "twitterUrl" => $twitterLink];

    return $social_share;
}

function cleanUrl($title)
{
    $txt = preg_replace('/[^\p{L}\p{N}\s]/u', '', $title);
    $url = str_replace(' ', '-', strtolower($txt));
    return $url;
}

function uncleanUrl($title)
{
    $url = str_replace('-', ' ', strtolower($title));
    return $url;
}
