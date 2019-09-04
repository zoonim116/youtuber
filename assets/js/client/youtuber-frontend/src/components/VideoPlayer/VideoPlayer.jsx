import React from "react"
import styles from "./VideoPlayer.css"

export default ({ idx, youtubeURL }) => {
    return (
        <div className={"row " + styles.video_player} key={idx}>
            <iframe src={youtubeURL}></iframe>
        </div>
    );
};