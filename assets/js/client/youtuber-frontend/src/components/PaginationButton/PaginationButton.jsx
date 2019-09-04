import React from "react";
import styles from "./PaginationButton.css";

export default ({ videosToLoad, loadMore }) => {
    return (
        <button type="button" className={"btn btn-primary btn-lg btn-block " + styles.pagination_button} onClick={() => loadMore()}>{videosToLoad} more</button>
    );
};