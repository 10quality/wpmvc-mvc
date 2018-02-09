<?php

namespace WPMVC\MVC\Models\Common;

use Exception;
use WPMVC\Cache;
use WPMVC\Log;
use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\PostModel as Model;

/**
 * Attachment model.
 * Common Wordpress post type model.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 2.0.4
 */
class Attachment extends Model
{
    use FindTrait;
    /**
     * Property type.
     * Ayuco: addition 2017-06-01 10:03 pm
     * @since 2.0.4
     *
     * @var string
     */
    protected $type = 'attachment';
    /**
     * Post status.
     * @since 2.0.4
     * @var string
     */
    protected $status = 'inherit';
    /**
     * Aliases.
     * @since 2.0.4
     * @var array
     */
    protected $aliases = [
        'title'         => 'post_title',
        'date'          => 'post_date',
        'mime'          => 'post_mime_type',
        'url'           => 'guid',
        'caption'       => 'func_get_caption',
        'alt'           => 'meta__wp_attachment_image_alt',
        'path'          => 'func_get_path',
        'thumb_url'     => 'func_get_thumb_url',
        'large_url'     => 'func_get_large_url',
        'medium_url'    => 'func_get_medium_url',
    ];
    /**
     * Hidden attributes in Array and JSON casting.
     * @since 2.0.4
     * @var array
     */
    protected $hidden = [
        'post_author',
        'post_title',
        'post_name',
        'post_content',
        'post_date',
        'post_date_gmt',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count',
    ];
    /**
     * Returns processed caption.
     * @since 2.0.4
     *
     * @return string
     */
    public function get_caption()
    {
        if ($this->post_excerpt)
            return htmlentities($this->post_excerpt);
        return;
    }
    /**
     * Returns image path.
     * @since 2.0.4
     *
     * @return string
     */
    public function get_path()
    {
        $metadata = get_post_meta( $this->ID, '_wp_attachment_metadata', true );
        $upload_dir = wp_upload_dir();
        if ( $metadata
            && isset( $metadata['file'] )
            && isset( $upload_dir['basedir'] )
        ) {
            return $upload_dir['basedir'] . '/' . $metadata['file'];
        }
        return;
    }
    /**
     * Returns image with thumb resolution.
     * @since 2.0.4
     *
     * @return string
     */
    public function get_thumb_url()
    {
        $path = $this->path;
        return Cache::remember(
            'attachment_' . $this->ID . '_thumb',
            43200,
            function() use( &$path ) {
                try {
                    return resize_image(
                        $path,
                        get_option( 'thumbnail_size_w', 150),
                        get_option( 'thumbnail_size_h', 150),
                        true,
                        $this->ID
                    );
                } catch ( Exception $e ) {
                    Log::error( $e );
                }
                return;
            }
        );
    }
    /**
     * Returns image with large resolution.
     * @since 2.0.4
     *
     * @return string
     */
    public function get_large_url()
    {
        $path = $this->path;
        return Cache::remember(
            'attachment_' . $this->ID . '_large',
            43200,
            function() use( &$path ) {
                try {
                    return resize_image(
                        $path,
                        get_option( 'large_size_w', 1024),
                        get_option( 'large_size_h', 1024),
                        false,
                        $this->ID
                    );
                } catch ( Exception $e ) {
                    Log::error( $e );
                }
                return;
            }
        );
    }
    /**
     * Returns image with medium resolution.
     * @since 2.0.4
     *
     * @return string
     */
    public function get_medium_url()
    {
        $path = $this->path;
        return Cache::remember(
            'attachment_' . $this->ID . '_medium',
            43200,
            function() use( &$path ) {
                try {
                    return resize_image(
                        $path,
                        get_option( 'medium_size_w', 300),
                        get_option( 'medium_size_h', 300),
                        false,
                        $this->ID
                    );
                } catch ( Exception $e ) {
                    Log::error( $e );
                }
                return;
            }
        );
    }
}