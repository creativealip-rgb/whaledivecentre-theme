<?php
// Clear any output buffers
while (ob_get_level()) ob_end_flush();
// Try opcache
if (function_exists('opcache_reset')) { opcache_reset(); echo "opcache_reset done\n"; }
if (function_exists('apc_clear_cache')) { apc_clear_cache(); echo "apc_clear done\n"; }
// Clear WP cache if available
if (defined('WP_CACHE') && WP_CACHE) {
    global $wp_object_cache;
    if (method_exists($wp_object_cache, 'flush')) $wp_object_cache->flush();
}
echo "FLUSH_DONE";
?>
