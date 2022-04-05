<?php

namespace Source\Support;

/**
 *
 */
class SeoBuilder extends Seo
{
    /**
     * Make the seo
     * @param string $description
     * @param $url
     * @param $follow
     * @return string
     */
    public function make(string $description, $url, $follow = false): string
    {
        return $this->render(
            "{$description}" . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $url,
            theme("/assets/images/share.jpg"),
            $follow
        );
    }

}