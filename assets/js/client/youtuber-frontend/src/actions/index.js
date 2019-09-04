import { LOAD_VIDEOS, SET_CURRENT_VIDEO, SET_YOUTUBER_ID, SET_PAGE_INFO, SET_COLS } from "../constants/action-types";
import axios from "axios";

export const loadVideos = (videos) => {
  return { type: LOAD_VIDEOS, videos }
};

export const loadAllVideos = () => {
  return (dispatch, getState) => {
    const { youtuberID, pageInfo: { nextPageToken, videosToLoad } } = getState();
    let url = `/wp-json/youtuber/v1/videos/${youtuberID}`;
    if (nextPageToken) {
      url += `/${nextPageToken}`;
    }
    return axios.get(url)
      .then(response => {
        dispatch(loadVideos(response.data.items));
        dispatch(setPageInfo({
          nextPageToken: response.data.nextPageToken,
          channelID: response.data.channelID,
          lazyload: response.data.lazyload,
          videosToLoad: videosToLoad && videosToLoad > 0 ? videosToLoad - response.data.pageInfo.resultsPerPage : response.data.pageInfo.totalResults - response.data.pageInfo.resultsPerPage,
          ...response.data.pageInfo,
        }));
      })
      .catch(error => {
        console.log(error);
      });
  };
};

export const setCols = cols => {
  return { type: SET_COLS, cols};
};

export const openVideo = id => {
  return { type: SET_CURRENT_VIDEO, id }
};

export const setYouberID = id => {
  return { type: SET_YOUTUBER_ID, id }
};

export const setPageInfo = data => {
  return { type: SET_PAGE_INFO, data }
};