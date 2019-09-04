<div class="wrap">
    <?php if ($youtuberAlert): ?>
        <div class="mt-4 alert alert-<?php echo $youtuberAlert['type']; ?>" role="alert">
            <?php echo $youtuberAlert['text']; ?>
        </div>
    <?php endif; ?>
    <div class="col-sm-12 clearfix">
        <a href="<?php echo esc_url(add_query_arg(array('page' => 'youtuberCreateChannel'))); ?>" class="btn btn-link float-right">Add Youtuber Channel</a>
    </div>
    <div class="col-sm-12 clearfix">
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" width="70">ID</th>
                <th scope="col">API Key</th>
                <th scope="col">Channel ID</th>
                <th scope="col" width="100">Cols per row</th>
                <th scope="col">Shortcode</th>
                <th width="250"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($youtuberChannels as $channel): ?>
                <tr>
                    <td><?php echo $channel->id; ?></td>
                    <td><?php echo $channel->youtube_api_key; ?></td>
                    <td><?php echo $channel->youtube_chanel_id; ?></td>
                    <td><?php echo $channel->cols_per_row;?></td>
                    <td><?php echo htmlspecialchars('do_shortcode("[youtuber id="'.$channel->id.'"]");'); ?></td>
                    <td>
                        <a href="<?php echo esc_url(add_query_arg(array('page' => 'youtuberEditChannel', 'id' => $channel->id))); ?>" class="button-secondary">Edit</a>
                        <a href="<?php echo esc_url(add_query_arg(array('page' => 'youtuberDeleteChannel', 'id' => $channel->id))); ?>" class="button-secondary delete-channel">Delete</a>
                        <a href="<?php echo esc_url(add_query_arg(array('page' => 'youtuberClearCache', 'id' => $channel->id))); ?>" class="button-secondary">Clear Cache</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('.delete-channel').on('click', function () {
            const r = confirm("Please confirm channel deletion.");
            if (r !== true) {
                return false;
            }
        })
    });
</script>

<style>
    .widefat th, .widefat td {
        text-align: center;
    }
</style>

