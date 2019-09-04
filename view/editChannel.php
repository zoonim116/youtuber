<div class="wrap">
    <div class="col-sm-6">
        <form method="post" name="edit_youtuber_list">
            <div class="form-group">
                <label>Youtube API</label>
                <input type="text" class="form-control" name="youtube_api_key" value="<?php echo $channel->youtube_api_key; ?>" placeholder="Youtube API key" required>
            </div>
            <div class="form-group">
                <label>Youtube Chanel ID</label>
                <input type="text" class="form-control" name="youtube_chanel_id" value="<?php echo $channel->youtube_chanel_id; ?>" placeholder="Youtube Chanel ID" required>
            </div>
            <div class="form-group">
                <label>Cols per row</label>
                <?php $rows = array_merge(range(1, 4), [6]); ?>
                <select name="youtube_cols_per_row" class="form-control" required="">
                    <?php $rows = array_merge(range(1, 4), [6]); ?>
                    <?php foreach ($rows as $row) { ?>
                        <?php if ($row == $channel->cols_per_row) { ?>
                            <option value="<?php echo $row ?>" selected><?php echo $row ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $row ?>"><?php echo $row ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Cache Refresh</label>
                <input type="text" class="form-control" name="youtube_cache_refresh" value="<?php echo $channel->cache_refresh; ?>" placeholder="Youtube Chanel ID" required>
            </div>
            <button type="submit" class="button-primary">Update</button>
        </form>
    </div>
</div>