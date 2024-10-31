<?php

namespace QuizAd\Controller\Rest;

use QuizAd\Controller\AbstractRestController;
use WP_Post;
use WP_Query;

class SearchRestController extends AbstractRestController
{

    /**
     * @param $request
     *
     * @return array
     */
    protected function handle($request)
    {
        $fetch = new WP_Query(array(
                                  'posts_per_page' => 5,
                                  's'              => sanitize_text_field($request['keyword']),
                                  'post_type'      => array('post', 'page')
                              ));

        $restResponse = '<p>Wybierz: </p>';
        if ($fetch->have_posts())
        {
            /** @var WP_Post $post */
            foreach ($fetch->get_posts() as $post)
            {
                if ($post->post_type === 'post')
                {
                    $restResponse .= '<div class="search_item" id="post-' . $post->ID . '">' . $post->post_title . '</div>';
                }
                if ($post->post_type === 'page')
                {
                    $restResponse .= '<div class="search_item" id="page-' . $post->ID . '">' . $post->post_title . '</div>';
                }
            }
        }
        else
        {
            $restResponse = '<p>No Results Found</p>';
        }
        return [
            'data'    => $restResponse,
            'status'  => 200,
            'success' => true
        ];
    }
}