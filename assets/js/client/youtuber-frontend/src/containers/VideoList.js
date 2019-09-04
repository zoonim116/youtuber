import React, { Component } from "react";
import { connect } from "react-redux";
import Video from "../components/Video/Video";
import PaginationButton from "../components/PaginationButton/PaginationButton";
import { openVideo, loadAllVideos } from "../actions";
import VideoPlayer from "../components/VideoPlayer/VideoPlayer";
import styles from "../components/Video/Video.css";

class VideoList extends Component{
    constructor(props) {
        super(props);
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        document.dispatchEvent(new Event('lazyload'));
    }

    render() {
        const cols = parseInt(this.props.cols);
        const bootstrapCols = 12 / this.props.cols;
        const rows = [...Array(Math.ceil(this.props.videos.length / cols))];
        const videoRows = rows.map((row, idx) => this.props.videos.slice(idx * cols, idx * cols + cols));
        const content = videoRows.map((row, idx) => (
            <div className={styles.clear} key={idx} >
                <div className="row" key={idx}>
                    {row.map(video => <Video video={video} cols={bootstrapCols} openVideo={this.props.openVideo} lazyload={this.props.lazyload} key={video.id.videoId} />)}
                </div>
                {row.map((video, idx) => {
                    if (video.id.videoId == this.props.currentVideoID) {
                        return <VideoPlayer idx={idx} youtubeURL={this.props.currentVideo} key={idx} />
                    }
                })
                }
            </div>
        ));
        return (
            <div className={styles.youtuber_video_list}>
                {content}
                {this.props.videos.length > 0 &&
                <div className="row">
                    <PaginationButton videosToLoad={this.props.videosToLoad} loadMore={this.props.loadMore} />
                </div>
                }
            </div>
        )
    }
}

const getCurrentVideo = (videos, current_id) => {
    if (current_id) {
        const video = videos.find(video => video.id.videoId == current_id);
        return `https://www.youtube.com/embed/${video.id.videoId}`;
    }
    return "";
};

const mapStateToProps = state => {
    return {
        videos: state.videos,
        videosToLoad: state.pageInfo.videosToLoad,
        lazyload: state.pageInfo.lazyload,
        currentVideo: getCurrentVideo(state.videos, state.currentVideoID),
        currentVideoID: state.currentVideoID,
        cols: state.cols
    };
};

const mapDispatchToProps = dispatch => {
    return {
        openVideo: id => {
            dispatch(openVideo(id));
        },
        loadMore: () => {
            dispatch(loadAllVideos());
        }
    };
};

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(VideoList);