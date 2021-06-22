<?php

add_filter('allowed_redirect_hosts', 'extend_allowed_domains_list');

function extend_allowed_domains_list($hosts)
{
    $hosts[] = 'consul.staging.oecd.xapp.ovh';
    $hosts[] = 'consultation-staging.oecd-opsi.org';
    $hosts[] = 'consul.production.oecd.xapp.ovh';
    $hosts[] = 'engagement.oecd-opsi.org';
    $hosts[] = 'www.consul.production.oecd.xapp.ovh';
    $hosts[] = 'www.engagement.oecd-opsi.org';
    $hosts[] = 'lvh.me';

    return $hosts;
}
