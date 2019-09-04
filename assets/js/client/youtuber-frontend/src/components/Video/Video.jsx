import React from "react";
import styles from "./Video.css";
import { unescapeHTML, toTimestamp } from "../../utils/helpers";

export default ({ video, openVideo, lazyload, cols }) => {
    return (
        <div className={"col-sm-"+cols+" col-xs-6 " + styles.fix_padding}>
            <div className={styles.video_thumbnail}>
                <a href="#" onClick={e => {
                    e.preventDefault();
                    openVideo(video.id.videoId);
                }}>
                    { lazyload == 1 &&
                        <img data-src={video.snippet.thumbnails.medium.url} className={'lazyload ' + styles.video_thumbnail_img} />
                    }
                    { lazyload == 0 &&
                        <img src={video.snippet.thumbnails.medium.url} className={styles.video_thumbnail_img} />
                    }
                    <span className={styles.video_duration}>{video.statistics.duration}</span>
                </a>
                <a href="#" className={styles.video_title} onClick={e => {
                    e.preventDefault();
                    openVideo(video.id.videoId);
                }}>{unescapeHTML(video.snippet.title)}</a>
                <div>
                    {toTimestamp(video.snippet.publishedAt)} | {video.statistics.viewCount} views
                </div>
            </div>
        </div>
    );
};