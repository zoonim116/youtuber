<div class="wrap">
    <div class="col-sm-6">
        <form method="post" name="create_youtuber_list">
            <div class="form-group">
                <label>Youtube API</label>
                <input type="text" class="form-control" name="youtube_api_key" placeholder="Youtube API key" required>
            </div>
            <div class="form-group">
                <label>Youtube Chanel ID</label>
                <input type="text" class="form-control" name="youtube_chanel_id" placeholder="Youtube Chanel ID" required>
            </div>
            <div class="form-group">
                <label>Cols per row</label>
                <select name="youtube_cols_per_row" class="form-control" required="">
                    <?php $rows = array_merge(range(1, 4), [6]); ?>
                    <?php foreach ($rows as $row) { ?>
                        <option value="<?php echo $row ?>"><?php echo $row ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Cache Refresh (in minutes)</label>
                <input type="text" class="form-control" name="youtube_cache_refresh" placeholder="Cache lifetime" value="240" required>
            </div>
            <button type="submit" class="button-primary">Save</button>
        </form>
    </div>
</div>